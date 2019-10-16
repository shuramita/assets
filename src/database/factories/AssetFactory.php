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


$factory->define(\Shura\Asset\Models\Asset::class, function (Faker $faker) use ($factory){
    $building = \Shura\Asset\Helpers\Helper::building();
    $floors = $building->floors;
//    $photo
    return [
        'name'=>$faker->name,
        'code'=>'A'.$faker->randomAscii,
//        'slug'=>'auto handled in Model',
//        'category_id' =>0, // not required
//        'building_id'=>null, // auto handled by Auth()
        'asset_type_id' => \Shura\Asset\Models\AssetType::inRandomOrder()->first()->id,
        'floor_id'=> $building->floors->random()->id,
        'description'=>$faker->paragraph,
        'created_at'=>\Carbon\Carbon::now(),
        'updated_at'=>\Carbon\Carbon::now(),

    ];
});
