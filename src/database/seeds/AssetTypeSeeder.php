<?php
namespace Shura\Asset\Database\Seeds;

use Carbon\Carbon;
use Core\Admin\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Shura\Asset\Helpers\Helper;
use Shura\Asset\Models\AssetType;

class AssetTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $asset_types = Helper::getJsonFromStaticData('asset-type.json');
        foreach ($asset_types as $asset_type) {
            $category = Category::updateOrInsert([
                "name"=>$asset_type->category
            ],[
                "name"=>$asset_type->category,
                'slug' => Str::slug($asset_type->category),
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now(),
            ]);
            AssetType::updateOrInsert(
                [
                    'system_id' => $asset_type->system_id
                ],[
                'name' => $asset_type->name,
                'slug' => $asset_type->slug,
                'system_id' => $asset_type->system_id,
                'description'=>$asset_type->description,
                'category_id'=>$category->first()->id,
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now(),
            ]);
        }

    }
}
