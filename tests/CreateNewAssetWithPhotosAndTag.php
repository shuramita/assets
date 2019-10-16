<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Auth;
use RealEstateDoc\Asset\Helpers\Helper;
use RealEstateDoc\Asset\Models\Media;
use RealEstateDoc\Asset\Models\Role;
use RealEstateDoc\Asset\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use RealEstateDoc\Asset\Models\Tag;

//phpunit --filter CreateNewAssetWithPhotosAndTag

class CreateNewAssetWithPhotosAndTag extends TestCase
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
        $asset = factory(\RealEstateDoc\Asset\Models\Asset::class)->create();

        $photos = Media::inRandomOrder()->limit(2)->get();
        $asset->photos()->saveMany($photos);

        $tags = Tag::inRandomOrder()->limit(2)->get();
        $asset->tags()->saveMany($tags);

        $this->assertDatabaseHas(config('asset.schema_prefix').'assets',[
            'name'=>$asset->name,
            'building_id' => $user->building->id
        ]);

    }
}
