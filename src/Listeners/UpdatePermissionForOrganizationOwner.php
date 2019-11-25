<?php

namespace Shura\Asset\Listeners;

use Shura\Asset\Helpers\Helper;

class UpdatePermissionForOrganizationOwner extends \Core\Organization\Listeners\UpdatePermissionForOrganizationOwner
{
    public $moduleName = 'shura\asset';
    public function getPermissions(){
        return Helper::collect('permissions.json');
    }
}
