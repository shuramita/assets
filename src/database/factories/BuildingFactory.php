<?php

use Faker\Generator as Faker;
use \RealEstateDoc\Asset\Models\Building;
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


$factory->define(\RealEstateDoc\Asset\Models\Building::class, function (Faker $faker) use ($factory){
    $building = new Building();
    return [
        'name'=>$faker->company,
        'building_type'=>$building->getTypesAttribute()->first()->id,
        'phone_country'=>'+65',
        'phone_number'=>'63547892',
        'created_at'=>\Carbon\Carbon::now(),
        'updated_at'=>\Carbon\Carbon::now(),

    ];
});
