<?php

use Faker\Generator as Faker;
use Shura\Asset\Helpers\Helper;
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


$factory->define(\Shura\Asset\Models\Price::class, function (Faker $faker) use ($factory){
    $price_option = Helper::getJsonFromStaticData('price_option.json');
    $price_type = $faker->randomElement($price_option->type);
    $price_unit = $faker->randomElement($price_option->unit);
    $price_name = "Price Of $price_type per $price_unit";
    return [
        'name'=>$price_name,
        'type'=>$price_type,
        'unit'=>$price_unit,
        'created_at'=>\Carbon\Carbon::now(),
        'updated_at'=>\Carbon\Carbon::now(),

    ];
});
