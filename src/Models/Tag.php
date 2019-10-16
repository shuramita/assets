<?php

namespace RealEstateDoc\Asset\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use RealEstateDoc\Asset\Facades\Auth;
use RealEstateDoc\Asset\Helpers\Helper;
use Illuminate\Database\Eloquent\Builder;

class Tag extends Model
{
    protected $table = 'ass_setting';

    protected $hidden = [];

    static $authorized_user = null;


    function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        static::$authorized_user = User::find(auth()->id());
    }

    public function assets(){
        return $this->belongsToMany('RealEstateDoc\Asset\Models\Asset','ass_asset_tag','tag_id','asset_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model)
        {
            $model->organization_id = Helper::org()->id;
            $model->key =  $model->key ?? 'TAG';
            $model->model = 'tag';
            $model->group = $model->group ?? 'asset';
            $model->type = 'string';


        });
        static::addGlobalScope('model', function (Builder $builder) {
            $builder->where('model', '=', 'tag')->where('organization_id','=',Helper::org()->id);
        });

    }
}
