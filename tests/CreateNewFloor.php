<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Auth;
use Shura\Asset\Helpers\Helper;
use Shura\Asset\Models\Role;
use Shura\Asset\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
//phpunit --filter CreateNewFloor


class CreateNewFloor extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_create_new_floor_belong_to_current_organizaion()
    {
        $user_id = 1;
        $user = Auth::loginUsingId($user_id);
        $user = User::find($user_id);
        $floor = factory(\Shura\Asset\Models\Floor::class)->create();

        $this->assertDatabaseHas(config('asset.schema_prefix').'floor',[
            'name'=>$floor->name,
            'building_id'=>$user->building->id
        ]);

    }
}
