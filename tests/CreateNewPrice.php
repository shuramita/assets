<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Auth;
use RealEstateDoc\Asset\Helpers\Helper;
use RealEstateDoc\Asset\Models\Role;
use RealEstateDoc\Asset\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
//phpunit --filter CreateNewPrice

class CreateNewPrice extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_create_new_price_belong_to_current_organization()
    {
        $user_id = 1;
        $user = Auth::loginUsingId($user_id);
        $user = User::find($user_id);
        $price = factory(\RealEstateDoc\Asset\Models\Price::class)->create();

        
        $this->assertDatabaseHas(config('asset.schema_prefix').'price',[
            'name'=>$price->name,
            'organization_id' => $user->organization->id
        ]);

    }
}
