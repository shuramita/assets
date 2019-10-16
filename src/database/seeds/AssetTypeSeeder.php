<?php
namespace RealEstateDoc\Asset\Database\Seeds;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RealEstateDoc\Asset\Helpers\Helper;

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
            DB::table(config('asset.schema_prefix').'type')->insert([
                'name' => $asset_type->name,
                'slug' => $asset_type->slug,
                'system_id' => $asset_type->system_id,
                'description'=>$asset_type->description,
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now(),
            ]);
        }

    }
}