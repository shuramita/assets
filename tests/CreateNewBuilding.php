<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Auth;
use Shura\Asset\Helpers\Helper;
use Shura\Asset\Models\Role;
use Shura\Asset\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
//phpunit --filter CreateNewBuilding

class CreateNewBuilding extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_create_new_building_belong_to_current_organizaion()
    {
        $user_id = 1;
        $user = Auth::loginUsingId($user_id);
        $user = User::find($user_id);
        $building = factory(\Shura\Asset\Models\Building::class)->create();

        $this->assertDatabaseHas(config('asset.schema_prefix').'building',[
            'name'=>$building->name,
            'organization_id'=>$user->organization->id
        ]);
        $this->assertDatabaseHas(config('asset.schema_prefix').'setting',[
            'key'=>$user_id,
            'organization_id'=>$user->organization->id
        ]);
        $this->assertEquals(app('Shura\Asset\Authenticated')->building->id,$building->id);
    }
}
