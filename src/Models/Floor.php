<?php

namespace Shura\Asset\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Shura\Asset\Helpers\Helper;

class Floor extends Model
{
    protected $table = 'ass_floor';

    protected $primaryKey = 'id';

    protected $hidden = [];

    public function getMapDataAttribute($value)
    {
        if(!empty($value) && gettype($value) == 'string') {
            return json_decode($value);
        }
    }
    public function setMapDataAttribute($value)
    {
        $this->attributes['map_data'] = gettype($value) != 'string' ? json_encode($value) : $value;
    }
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model)
        {
//            $model->slug = str_slug($model->name);
                $model->building_id = Helper::building()->id;
        });
        static::updating(function ($model)
        {
//            $model->slug = str_slug($model->name);
        });
    }
    public function assets()
    {
        return $this->hasMany('Shura\Asset\Models\Asset');
    }
    public function building()
    {
        return $this->belongsTo('Shura\Asset\Models\Building');
    }
    public function photo()
    {
        return $this->belongsTo('Shura\Asset\Models\Media','photo_id');
    }
    public function map()
    {
        return $this->belongsTo('Shura\Asset\Models\Media','map_id');
    }
    public static function addNewFloor($data){
        $floor = new Floor();
        $floor->name = $data['name'];
        $floor->code = $data['code'] ?? str_slug($floor->name);
        $floor->description = $data['description'] ?? '';
        $floor->map_data = $data['map_data'] ?? [];
        $floor->map_id = $data['map_id'] ?? 0;
        $floor->photo_id = $data['photo_id'] ?? 0;

        $floor->save();
        return $floor;
    }
    public static function updateFloor($data){
        $floor = Floor::find($data['id']);
        $floor->name = $data['name'] ?? $floor->name;
        $floor->code = $data['code'] ?? $floor->code;

        $floor->description = $data['description'] ??  $floor->description;
        $floor->map_data = $data['map_data'] ?? $floor->map_data;
        $floor->photo_id = $data['map_data'] ?? $floor->photo_id;
        $floor->map_id = $data['map_data'] ?? $floor->map_id;
        $floor->update();
        return $floor;
    }
}
