<?php

namespace Shura\Asset\Listeners;

use Shura\Asset\Helpers\Helper;
use Core\Organization\Listeners\Trails\UpdateDefaultGroupPermissions;

class UpdatePermissionForOrganizationOwner
{
    public $moduleName = 'shura\asset';
    use UpdateDefaultGroupPermissions;
    public function getPermissions(){
        return Helper::collect('permissions.json');
    }
    public function handle($event){
        $module = $event->module;
        if($event->organization && $module->module_name === $this->moduleName){
            $this->updateOrganizationOwnerPermissions($event->organization);
        }
    }
}
