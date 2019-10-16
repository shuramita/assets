<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Auth;
use Shura\Asset\Helpers\Helper;
use Shura\Asset\Models\Role;
use Shura\Asset\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
//phpunit --filter CreateNewAsset

class CreateNewAsset extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_create_new_asset_belong_to_current_building()
    {
        $user_id = 1;
        $user = Auth::loginUsingId($user_id);
        $user = User::find($user_id);
        $status = Helper::getJsonFromStaticData('asset-status.json');
//        var_dump($status);exit;

        $asset = factory(\Shura\Asset\Models\Asset::class)->create([
            'status' => $status[0]->id
        ]);


        $this->assertDatabaseHas(config('asset.schema_prefix').'assets',[
            'name'=>$asset->name,
            'building_id' => $user->building->id
        ]);

    }
}
