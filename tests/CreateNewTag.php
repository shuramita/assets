<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Auth;
use Shura\Asset\Models\Role;
use Shura\Asset\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
//phpunit --filter CreateNewTag

class CreateNewTag extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_create_new_tag_belong_to_current_organizaion()
    {
        $user_id = 1;
        $user = Auth::loginUsingId($user_id);
        $user = User::find($user_id);
        $tag = factory(\Shura\Asset\Models\Tag::class)->create();

        $this->assertDatabaseHas(config('asset.schema_prefix').'setting',[
            'key'=>'tag',
            'title'=>$tag->title,
            'organization_id'=>$user->organization->id
        ]);
    }
}
