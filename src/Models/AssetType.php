<?php

namespace RealEstateDoc\Asset\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AssetType extends Model
{
    protected $table = 'ass_type';

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
            $model->slug = str_slug($model->name);
        });
        static::updating(function ($model)
        {
            $model->slug = str_slug($model->name);
        });
    }

    public function building() : BelongsTo
    {
        return $this->belongsTo('RealEstateDoc\Asset\Models\Building')->withDefault();
    }
    public function scopeEnabled($query){
        return $query->where('enabled','=',true);
    }
    public static function addNewLocationType($data){
        $asset_type = new AssetType();
        $asset_type->name = $data['name'];
        $asset_type->category_id = $data['category_id']  ?? 0;
        $asset_type->description = $data['description'];
        $asset_type->save();
        return $asset_type;
    }
    public static function updateLocationType($data){
        $asset_type = AssetType::find($data['id']);
        $asset_type->name = $data['name'];
        $asset_type->category_id = $data['category_id']  ?? 0;
        $asset_type->description = $data['description'];
        $asset_type->update();
        return $asset_type;
    }
}
