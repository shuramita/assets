<?php

namespace RealEstateDoc\Asset\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use RealEstateDoc\Asset\Facades\Auth;
use RealEstateDoc\Asset\Helpers\Helper;
use Illuminate\Database\Eloquent\Builder;

class Field extends Model
{
    protected $table = 'ass_setting';

    protected $hidden = [];


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
            $model->group = $model->group ?? 'field';

        });
        static::addGlobalScope('model', function (Builder $builder) {
            $organization = Helper::org();
            if(!empty($organization) && isset($organization->id)) {
                $builder->where('group', '=', 'field')->where('organization_id','=',Helper::org()->id);
            }
        });

    }
    public static function addNewField($data){
        $field = new Field();
        $field->key = $data['key'];
        $field->type = $data['type'];
        $field->title = $data['title'];
        $field->value = $data['value'] ?? '';
        $field->model = $data['model'];
        $field->description = $data['description'] ?? '';
        $field->save();
        return $field;
    }
}
