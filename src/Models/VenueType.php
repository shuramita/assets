<?php

namespace Shura\Asset\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Builder;

class VenueType extends Model
{
    protected $table = 'ass_static_data';

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
            $model->model = 'type';
        });
        static::updating(function ($model)
        {
            $model->model = 'type';
        });
        static::addGlobalScope('model', function (Builder $builder) {
            $builder->where('model', '=', 'type');
        });
    }
//    public function assets()
//    {
//        return $this->morphedByMany('Shura\Asset\Models\Asset', 'static','ass_asset_static_data','static_id','asset_id');
//    }
}
