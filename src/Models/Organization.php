<?php

namespace Shura\Asset\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Shura\Asset\Facades\Auth;
use Shura\Asset\Helpers\Helper as AssetHelper;
use Illuminate\Database\Eloquent\Builder;
use Shura\Asset\Helpers\Helper;

class Organization extends \Core\Organization\Models\Organization
{
    protected $table = 'ass_organization';

    static $authorized_user = null;

    public $appends = ['is_working_organization'];
    protected $with = ['logo'];
    function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }
    protected $hidden = [];

    public function getBase64Attribute(){
        // if the organization has base 64 of logo somewhere, let use it
        $logo = $this->logo;
        if(isset($this->logo) && isset($this->logo->base_64)) {
            return $this->logo->base_64;
        }
        // if not, use default
        else{
            return AssetHelper::getStaticData('asset-logo.txt');
        }
    }
    public function getTimezonesAttribute()
    {
        return collect(json_decode(AssetHelper::getStaticData('timezone.json')));
    }
    public function getLanguagesAttribute()
    {
        return collect(json_decode(AssetHelper::getStaticData('language.json')));
    }
    /**
     *   @return Collection
     */
    public function getLocationsAttribute()
    {
        return collect(json_decode(AssetHelper::getStaticData('location.json')));
    }
    public function getCurrenciesAttribute()
    {
        return collect(json_decode(AssetHelper::getStaticData('currency.json')));
    }

    public function getStaticTaxesAttribute(){
        return collect(json_decode(AssetHelper::getStaticData('tax.json')));
    }

    public function getIsWorkingOrganizationAttribute()
    {
        return Helper::org() && $this->id == Helper::org()->id;
    }
    public function taxes(){
        return $this->hasMany('Shura\Asset\Models\Tax');
    }
    public function getDefaultTaxAttribute(){
        return $this->taxes()->where('order','=', '1')->first();
    }
    public function logo() {
        return $this->belongsTo('Shura\Asset\Models\Media','logo');
    }
    public function scopeOwner($query)
    {
        return $query->where('created_by', '=', auth()->id());
    }

    public function buildings(){
        return $this->hasMany('Shura\Asset\Models\Building');
    }
    public function prices(){
        return $this->hasMany('Shura\Asset\Models\Price');
    }
    public function fields(){
        return $this->hasMany('Shura\Asset\Models\Field');
    }
    

    public function scopeCreatedByMe($query)
    {
        return $query->where('created_by', auth()->id() ?? 0);
    }
    public static function addNewOrganization($data){
        $organization = new Organization();
        $organization->name = $data['name'];
        $organization->category_id = $data['category_id']   ?? 0;
        $organization->description = $data['description'] ?? '';

        $organization->location = $data['location'];
        $organization->currency = $data['currency'];
        $organization->language = $data['language'];
        $organization->timezone = $data['timezone'];
        $organization->logo = $data['logo'] ?? 0;

        $organization->save();
        return $organization;
    }
    public static function updateOrganization($data){
        $organization = Organization::find($data['id']);
        $organization->name = $data['name'] ?? $organization->name;
        $organization->category_id = $data['category_id'] ?? $organization->category_id;
        $organization->description = $data['description'] ?? $organization->description;

        $organization->location = $data['location'] ?? $organization->location;
        $organization->currency = $data['currency'] ?? $organization->currency;
        $organization->language = $data['language'] ?? $organization->language;
        $organization->timezone = $data['timezone'] ?? $organization->timezone;
        $organization->logo = $data['logo'] ?? $organization->logo;

        $organization->update();
        return $organization;
    }
    public function changeToOrganization(){
        // let check which role i current asociated to this organization
        $user = auth()->user();

//        $current_organization = Auth::workingOrganization();

        $organization_roles = Role::where('organization_id','=',$this->id)->get();
        $organization_role_ids = $organization_roles->map(function($organization_role){
            return $organization_role->id;
        })->toArray();
        // new organization has 3 roles and i'm currently
        $role = DB::table(config('Asset.schema_prefix')."user_role")
            ->where('user_id','=',$user->id)
            ->whereIn('role_id',$organization_role_ids)->get();

        // and my role is
        $my_role = $organization_roles->filter(function($organization_role) use($role){
            return $role->role_id = $organization_role->id;
        })->first();


        // set to new organization
        $my_role->users()->sync([$user->id =>['is_default' => true]]);

        // reset current working organization
        DB::table(config('asset.schema_prefix').'user_role')
            ->where('user_id','=',$user->id)
            ->where('role_id','<>',$my_role->id)
            ->update(['is_default'=>false]);

        // rebinding the singleton
        return Helper::org(true);
    }

}
