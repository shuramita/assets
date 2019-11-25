<?php

namespace Shura\Asset\Database\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Junges\ACL\Exceptions\PermissionAlreadyExistsException;
use Junges\ACL\Http\Models\Permission;
use Shura\Asset\Helpers\Helper;


class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = Helper::collect('permissions.json');
        dump('run asset Permissions seeder');
        $permissions->map(function ($perm) {
            try {
                Artisan::call('permission:create', ["name" => $perm->name, "slug" => $perm->slug, "description" => $perm->description]);
            } catch (PermissionAlreadyExistsException $e) {
                Log::info($e);
                dump($e->getMessage());
            };
        });
    }
}
