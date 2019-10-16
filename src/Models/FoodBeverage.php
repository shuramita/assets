<?php

namespace RealEstateDoc\Asset\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FoodBeverage extends Model
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
        static::addGlobalScope('model', function (Builder $builder) {
            $builder->where('model', '=', 'food_beverage');
        });
    }
    
}
