<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Auth;
use Shura\Asset\Helpers\Helper;
use Shura\Asset\Models\Media;
use Shura\Asset\Models\Role;
use Shura\Asset\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
//phpunit --filter CreateNewAssetWithPhotos

class CreateNewAssetWithPhotos extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_create_new_asset_belong_to_current_building_with_media_photo()
    {
        $user_id = 1;
        $user = Auth::loginUsingId($user_id);
        $user = User::find($user_id);
        $asset = factory(\Shura\Asset\Models\Asset::class)->create();

        $photos = Media::inRandomOrder()->limit(2)->get();
        $asset->photos()->saveMany($photos);

        $this->assertDatabaseHas(config('asset.schema_prefix').'assets',[
            'name'=>$asset->name,
            'building_id' => $user->building->id
        ]);

    }
}
