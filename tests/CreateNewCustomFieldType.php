<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Auth;
use RealEstateDoc\Asset\Helpers\Helper;
use RealEstateDoc\Asset\Models\Role;
use RealEstateDoc\Asset\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
//phpunit --filter CreateNewCustomFieldType

class CreateNewCustomFieldType extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_create_new_custom_field_belong_to_organization()
    {
        $user_id = 1;
        $user = Auth::loginUsingId($user_id);
        $user = User::find($user_id);
        $status = Helper::getJsonFromStaticData('asset-status.json');
//        var_dump($status);exit;

        $field = factory(\RealEstateDoc\Asset\Models\Field::class)->create([
            'model'=>'asset'
        ]);


        $this->assertDatabaseHas(config('asset.schema_prefix').'setting',[
            'key'=>$field->key,
            'organization_id' => $user->organization->id
        ]);

    }
}
