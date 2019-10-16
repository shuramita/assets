<?php

namespace Tests\Unit;

use App\User;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Shura\Asset\Models\Role;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
//phpunit --filter NewUserAndIntitAllSettingWhenCreateNewOrganizationTest
class NewUserAndIntitAllSettingWhenCreateNewOrganizationTest extends TestCase
{

//    use RefreshDatabase;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_create_new_user_and_term_currency_role_and_default_template_will_be_generated_when_new_organization_created()
    {
        $user  = factory(\App\User::class)->create();

        Auth::login($user);

        $organization = factory(\Shura\Asset\Models\Organization::class)->create();
//        var_dump($organization);
        $this->assertDatabaseHas(config('asset.schema_prefix').'role',[
            'name'=>'admin',
            'organization_id'=>$organization->id
        ]);

        // role associated
        $this->assertDatabaseHas(config('asset.schema_prefix').'user_role',[
            'user_id'=>$user->id,
            'role_id'=>Role::where([
                'name'=>'admin',
                'organization_id'=>$organization->id
            ])->first()->id,
            'is_default'=>true
        ]);
    }

}
