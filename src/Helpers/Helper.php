<?php

namespace Shura\Asset\Helpers;


use Shura\Asset\Constants\Option;
use Shura\Asset\Constants\Status;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Shura\Asset\Models\User;

class Helper extends \App\Helpers\Helper
{
    public static $static_data_path = __DIR__.'/../database/static/';

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
