<?php

namespace RealEstateDoc\Asset\Models;


class User extends \App\User
{
//    protected $hidden = ['api_token'];

    public function getOrganizationAttribute(){

        return $this->workingOrganization();
    }
    /**
     * @return  \RealEstateDoc\Asset\Models\Building
     */
    public function getBuildingAttribute(){
        $default_building = Setting::where('key' ,'=', auth()->id())->where('model','=','building')->first();
        if(empty($default_building)) return null;
        return Building::where('id','=',$default_building->value)->first();
    }
    public function roles()
    {
        return $this->belongsToMany(Role::class, config('asset.schema_prefix').'user_role', 'user_id', 'role_id')
            ->wherePivot('is_default',true);
    }
    public function isAdmin(){
        foreach ($this->roles  as $role) {
            if($role->name == 'admin') {
                return true;
            }
        }
        return false;
    }
    public function isManager(){
        foreach ($this->roles  as $role) {
            if($role->name == 'manager') {
                return true;
            }
        }
        return false;
    }
    public function isMaker(){
        foreach ($this->roles  as $role) {
            if($role->name == 'staff') {
                return true;
            }
        }
        return false;
    }
    public function workingOrganization(){
        return $this->roles->first()->organization ?? null;
    }

    public function registerToOrganization($organization_id,$role){
        // todo: this is only for demo scope
        // find the first organization and then register to
        $role = Role::where('organization_id','=',$organization_id)->where('name','=',$role)->first();
        // TODO: check if user already attached
        if(!empty($role)){
            $role->users()->attach($this->id,['is_default' => true]);
        }

    }
}
