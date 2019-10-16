<?php

use Faker\Generator as Faker;
/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/


$factory->define(\RealEstateDoc\Asset\Models\AssetType::class, function (Faker $faker) use ($factory){
    $asset_types = collect(\RealEstateDoc\Asset\Helpers\Helper::getJsonFromStaticData('asset-type.json'));
    return [
        'name'=>$faker->name,
        'system_id'=>$asset_types->first()->id,
        'created_at'=>\Carbon\Carbon::now(),
        'updated_at'=>\Carbon\Carbon::now(),

    ];
});
