<?php

namespace RealEstateDoc\Asset\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use RealEstateDoc\Asset\Facades\Auth;
use RealEstateDoc\Asset\Helpers\Helper as AssetHelper;
use Illuminate\Database\Eloquent\Builder;
use RealEstateDoc\Asset\Helpers\Helper;

class Organization extends Model
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
    /**
     * Boot function
     *
     * @return void
     */

//    public function getLogoAttribute($value)
//    {
//        if(!empty($value)) {
//            return collect(json_decode($value))->first();
//        }
//    }
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
//    public function getAssetTemplatesAttribute()
//    {
//        return Template::all();
//    }

//    public function getSystemDefaultTemplateAttribute()
//    {
//        return Template::systemDefaultTemplate();
//    }

    public function getStaticTaxesAttribute(){
        return collect(json_decode(AssetHelper::getStaticData('tax.json')));
    }

//    public function terms(){
//        return $this->hasMany('RealEstateDoc\Asset\Models\Term');
//    }
    public function getIsWorkingOrganizationAttribute()
    {
        return Helper::org() && $this->id == Helper::org()->id;
    }
    public function taxes(){
        return $this->hasMany('RealEstateDoc\Asset\Models\Tax');
    }
//    public function template(){
//        return $this->belongsTo('RealEstateDoc\Asset\Models\Template','Asset_template_id');
//    }
    public function getDefaultTaxAttribute(){
        return $this->taxes()->where('order','=', '1')->first();
    }
    public function logo() {
        return $this->belongsTo('RealEstateDoc\Asset\Models\Media','logo');
    }
    public function scopeOwner($query)
    {
        return $query->where('created_by', '=', auth()->id());
    }

    public function buildings(){
        return $this->hasMany('RealEstateDoc\Asset\Models\Building');
    }
    public function prices(){
        return $this->hasMany('RealEstateDoc\Asset\Models\Price');
    }
    public function fields(){
        return $this->hasMany('RealEstateDoc\Asset\Models\Field');
    }
    
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model)
        {
            $model->slug = str_slug($model->name);
            $model->client_portal_url = str_replace('_','-',$model->slug).'-'.rand(1000,1000000);
            $model->created_by = auth()->id();
            if(empty($model->language)) {
                $model->language = 'en'; // English
            }
        });
        static::updating(function ($model)
        {
            $model->slug = str_slug($model->name);

        });
        static::created(function($model){
            // create role for this organization
            $default_roles = json_decode(AssetHelper::getStaticData('role.json'));

            // reset current working organization
            DB::table(config('asset.schema_prefix').'user_role')
                ->where('user_id','=',$model->created_by)
                ->update(['is_default'=>false]);

            foreach ($default_roles as $default_role) {
                $role = new Role();
                $role->name             = $default_role->name;
                $role->description      = $default_role->description;
                $role->organization_id  = $model->id;
                $role->save();
                // set user created become admin of this organization
                if($role->name == 'admin') {
                    // set default working organization for created user
                    $role->users()->attach($model->created_by,['is_default' => true]);
                }
            }

            // seed term to database
//            Term::seedTermToDatabase($model->id);
            // seed Currency to database
            Currency::seedCurrencyToDatabase($model->id);

            // Seed default Asset Tax
            Tax::seedDefaultTax($model->id);

            // Seed default Asset template
//            $template = Template::seedDefaultTemplate($model->id);

//            $model->asset_template_id = $template->id;
            // Create default Price
            //TODO: It's not binding right after model created ,
            Helper::rebindingAuthenticated();
//            var_dump($model->id);exit;

//            var_dump(Helper::org(true));exit;
            Price::addNewPrice([
                'name'=>'Normal Price',
                'type'=>'normal',
                'unit'=>'daily',
                'description'=>'Default Price type that created by sytem'
            ],$model->id);

            $model->save();


        });

//        static::addGlobalScope('organization', function (Builder $builder) {
//            $builder->where('created_by', '=', static::$authorized_user->id);
//        });
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
