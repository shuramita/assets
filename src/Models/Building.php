<?php

namespace Shura\Asset\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Shura\Asset\Helpers\Helper;

class Building extends \Core\Organization\Models\Building
{
    protected $hidden = [];

    public $appends = ['is_working_building'];

//    public function getLogoAttribute($value)
//    {
//        if(!empty($value) && gettype($value) == 'string') {
//            return json_decode($value);
//        }
//    }
//    public function getBackgroundPhotoAttribute($value)
//    {
//        if(!empty($value) && gettype($value) == 'string') {
//            return json_decode($value);
//        }
//    }
//    public function getPhotosAttribute($value)
//    {
//        if(!empty($value) && gettype($value) == 'string') {
//            return json_decode($value);
//        }
//    }
    public function getTypesAttribute(){
        return collect(Helper::getJsonFromStaticData('building-type.json'));
    }
    public function getIsWorkingBuildingAttribute()
    {
        return Helper::building() && $this->id == Helper::building()->id;
    }
//    public function setLogoAttribute($value)
//    {
//        $this->attributes['logo'] = gettype($value) != 'string' ? json_encode($value) : $value;
//    }
//    public function setBackgroundPhotoAttribute($value)
//    {
//        $this->attributes['background_photo'] = gettype($value) != 'string' ? json_encode($value) : $value;
//    }
//    public function setPhotosAttribute($value)
//    {
//        $this->attributes['photos'] = gettype($value) != 'string' ? json_encode($value) : $value;
//    }
    public function organization() {
        return $this->belongsTo('Shura\Asset\Models\Organization','organization_id');
    }
    public function logo() {
        return $this->belongsTo('Shura\Asset\Models\Media','logo_id');
    }
    public function photos() {
        return $this->morphToMany('Shura\Asset\Models\Media', 'mediable','ass_mediables');
    }
    public function background() {
        return $this->belongsTo('Shura\Asset\Models\Media','background_photo');
    }
    public function floors(){
        return $this->hasMany('Shura\Asset\Models\Floor');

    }
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model)
        {
            $model->slug = str_slug($model->name);

            $model->organization_id = Helper::org()->id;
        });
        static::updating(function ($model)
        {
            $model->slug = str_slug($model->name);
        });
        static::created(function($model){
            Building::changeDefaultBuilding($model);
        });
    }
    public static function addNewBuilding($data){
        $building = new Building();
        $building->name = $data['name'];
        $building->category_id = $data['category_id']  ?? 0;
        $building->description = $data['description'] ?? '';
        $building->logo_id = $data['logo_id'] ?? 0;
        $building->background_photo = $data['background_photo'] ?? 0;
//        $building->photos = $data['photos'] ?? [];


        $building->address = $data['address'] ?? '';
        $building->email = $data['email']  ?? '';
        $building->website = $data['website']  ?? '';
        $building->phone_number = $data['phone_number']  ?? '';


        $building->save();
        return $building;
    }
    public static function updateBuilding($data){
        $building = Building::find($data['id']);
        $building->name = $data['name'];
        $building->category_id = $data['category_id']  ?? 0;
        $building->description = $data['description'];
        $building->logo = $data['logo'] ?? $building->logo;
        $building->background_photo = $data['background_photo'] ?? $building->background_photo;
        $building->photos = $data['photos'] ?? $building->photos;

        $building->address = $data['address'] ?? $building->address;
        $building->email = $data['email'] ?? $building->email;
        $building->website = $data['website'] ?? $building->website;
        $building->phone_number = $data['phone_number'] ?? $building->phone_number;

        $building->update();
        return $building;
    }

    public static function changeDefaultBuilding($model) {
        // change default building to created
        $user = auth()->user();
//           $default_bulding = Setting::where('key','=',$user->id)->where('model','=','building')->where('organization_id','=',Helper::org()->id)->firstOrNew();
        $default_bulding = Setting::where(
            ['key' => $user->id,
                'model' => 'building']
        )->first();
//           var_dump($default_bulding);exit;
        if(empty($default_bulding)) {
            $default_bulding = new Setting();
            $default_bulding->title = 'Default Working Building';
            $default_bulding->key = $user->id;
            $default_bulding->description = 'Default Working Building';
        }
        $default_bulding->value = $model->id;
        $default_bulding->group = 'none';
        $default_bulding->model = 'building';
        $default_bulding->type = 'number';
        $default_bulding->save();
        Helper::rebindingAuthenticated();
    }
}
