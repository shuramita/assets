<?php

namespace Shura\Asset\Helpers;


use Shura\Asset\Constants\Option;
use Shura\Asset\Constants\Status;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Shura\Asset\Models\User;

class Helper
{
    public static $static_data_path = '/../database/static/';

    public static function getStaticData($file){
        $file_path = __DIR__.static::$static_data_path.$file;
        if(File::exists($file_path)) {
            return File::get($file_path);
        }else{
            return null;
        }

    }
    public static function getJsonFromStaticData($file){
        $data = static::getStaticData($file);
        if(!empty($data)) {
            return json_decode($data);
        }else{
            return null;
        }

    }
    public static function collect($file) {
        return collect(static::getJsonFromStaticData($file));
    }
    public static function getSystemDefaultTemplate(){
        return Template::systemDefaultTemplate();
    }
    public static function rebindingAuthenticated(){
        // rebinding the singleton
        //TODO: rebinding not working on created
        app()->rebinding('Shura\Asset\Authenticated',function(){
            return User::find(auth()->id());
        });
        return app('Shura\Asset\Authenticated');
    }
    /**
     * @return  \Shura\Asset\Models\Organization
     */
    public static function org($rebind = false){
        $user = app('Shura\Asset\Authenticated');
        if($rebind || empty($user->organization) ) {
            $user =  Helper::rebindingAuthenticated();
        }
        return $user->organization ?? null;
    }
    /**
     * @return  \Shura\Asset\Models\Building
     */
    public static function building($rebind = false){
        $user = app('Shura\Asset\Authenticated');
        if(empty($user)) return null;
        if($rebind || empty($user->building) ) {
            $user =  Helper::rebindingAuthenticated();
        }
        return $user->building;
    }

}
