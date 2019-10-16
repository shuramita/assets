<?php

namespace RealEstateDoc\Asset\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Role extends Model
{
    protected $table = 'ass_role';

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
        });
        static::updating(function ($model)
        {
//            $model->slug = str_slug($model->name);
        });
    }
    public static function addNewRole($data){
        $role = new Role();
        $role->name = $data['name'];
        $role->description = $data['description'];
        $role->save();
        return $role;
    }
    public static function updateRole($data){
        $role = Role::find($data['id']);
        $role->name = $data['name'];
        $role->description = $data['description'];
        $role->update();
        return $role;
    }
    public function organization(){
        return $this->belongsTo('RealEstateDoc\Asset\Models\Organization');
    }
    /**
     * Relation with users
     *
     * @return mixed
     */
    public function users()
    {
        return $this->belongsToMany(User::class, config('asset.schema_prefix').'user_role', 'role_id', 'user_id');
    }
}
