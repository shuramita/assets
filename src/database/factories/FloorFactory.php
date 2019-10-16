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


$factory->define(\RealEstateDoc\Asset\Models\Floor::class, function (Faker $faker) use ($factory){

    $floor_list = [
        'B2'=>'Basement 2',
        'B1'=>'Basement 1',
        'B'=>'Basement',
        'F1'=>'Floor 1 ',
        'F2'=>'Floor 2 ',
        'F3'=>'Floor 3 ',
    ];
    $code = array_rand($floor_list);
    return [
        'name'=>$floor_list[$code],
        'code'=>$code,
        
        'created_at'=>\Carbon\Carbon::now(),
        'updated_at'=>\Carbon\Carbon::now(),

    ];
});
