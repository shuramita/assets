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


$factory->define(\Shura\Asset\Models\Tax::class, function (Faker $faker) use ($factory){
    $percent = random_int(5,12);
    return [
        'value' => "$percent",
        'title'=>"$percent% of Random Tax",
        'description'=>'Random Tax 7%',
        'type'=>'percent'
    ];
});
