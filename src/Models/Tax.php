<?php

namespace Shura\Asset\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Shura\Asset\Facades\Auth;
use Shura\Asset\Helpers\Helper;
use Illuminate\Database\Eloquent\Builder;

class Tax extends Model
{
    protected $table = 'ass_setting';

    protected $hidden = [];

    static $authorized_user = null;


    function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        static::$authorized_user = User::find(auth()->id());
    }



    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model)
        {
            if(empty($model->organization_id)) {
                $model->organization_id = Helper::org()->id;
            }
            $model->key =  $model->key??'tax';
            $model->model = $model->model??'tax';
            $model->group = $model->group??'tax';
        });
        static::addGlobalScope('model', function (Builder $builder) {
            $builder->where('model', '=', 'tax');
        });

    }
    public static function getDefaultTax(){
        $tax = Tax::orderBy('order')->first();
        if(empty($tax)) {
            Tax::seedDefaultTax(Auth::workingOrganization()->id);
            $tax = Tax::orderBy('order')->first();
        }
        return $tax;
    }
    public static function seedDefaultTax($organization_id){
        $taxes = json_decode(Helper::getStaticData('tax.json'));
        foreach ($taxes as $static_tax) {
            $tax = new Tax();
            $tax->key = $static_tax->code;
            $tax->value = $static_tax->rate;
            $tax->model = 'tax';
            $tax->group = 'none';
            $tax->title = $static_tax->value;
            $tax->description = $static_tax->description;
            $tax->type = 'percent';
            $tax->order = $static_tax->order;
            $tax->organization_id = $organization_id;
            $tax->save();
        }
    }
}
