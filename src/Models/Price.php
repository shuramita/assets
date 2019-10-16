<?php

namespace Shura\Asset\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Shura\Asset\Helpers\Helper;

class Price extends Model
{
    protected $table = 'ass_price';

//    public static $default_date = 'WD'; // WEEK DAY

    protected $hidden = [];
    /**
     * Boot function
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model)
        {
//            $model->slug = str_slug($model->name);
//            var_dump(Helper::org(true));exit;
            if(empty($model->organization_id)) {
                if(!empty(Helper::org()) && !empty(Helper::org()->id)) {
                    $model->organization_id = Helper::org(true)->id;
                }
            }

        });
        static::updating(function ($model)
        {
//            $model->slug = str_slug($model->name);
        });
    }
    public function getRangeAttribute($value)
    {
        if(!empty($value)) {
            return json_decode($value);
        }
    }
    public function setRangeAttribute($value)
    {
        $this->attributes['range'] = gettype($value) != 'string' ? json_encode($value) : $value;
    }
    public function getTypesAttribute()
    {
        return Helper::getJsonFromStaticData('price-option.json')->type;
    }
    public function getDefaultTypeAttribute(){
        return "normal";
    }
    public static function addNewPrice($data, $organization_id=null){
        $price = new Price();
        $price->name = $data['name'];
        $price->type = $data['type'];
        $price->unit = $data['unit'];
        $price->available_at = $data['available_at'] ?? 'all';
        $price->range = $data['range'] ?? [];
        $price->description = $data['description'] ?? '';
        if(!empty($organization_id)) {
            var_dump($organization_id);
            $price->organization_id = $organization_id;
        }
        $price->save();
        return $price;
    }
    public static function updatePrice($data){
        $price = Price::find($data['id']);
        $price->name = $data['name'] ?? $price->name;
        $price->type = $data['type']  ?? $price->type;
        $price->type = $data['unit']  ?? $price->unit;
        $price->range = $data['range']  ?? $price->range;
        $price->description = $data['description'] ?? $price->description;
        $price->update();
        return $price;
    }
}
