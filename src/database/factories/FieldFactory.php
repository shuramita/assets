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


$factory->define(\Shura\Asset\Models\Field::class, function (Faker $faker) use ($factory){
    $title = $faker->name;
    return [
        'key'=>$faker->uuid,
        'value' => $faker->name,
        'title'=>$faker->title,
        'type' => 'text',
        'description'=>$faker->paragraph
    ];
});
