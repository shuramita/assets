<?php

namespace RealEstateDoc\Asset\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use RealEstateDoc\Asset\Facades\Auth;
use RealEstateDoc\Asset\Helpers\Helper;
use Illuminate\Database\Eloquent\Builder;

class Setting extends Model
{
    protected $table = 'ass_setting';

    protected $hidden = [];

//    static $authorized_user = null;
    public $timestamps = true;

    public $fillable = ['key','title','value'];

    function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model)
        {
            $model->organization_id = Helper::org()->id;
        });
        static::addGlobalScope('model', function (Builder $builder) {
            $builder->where('organization_id', '=', Helper::org()->id);
        });

    }
    public static function building(){

    }
}
