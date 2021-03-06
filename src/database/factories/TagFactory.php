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


$factory->define(\Shura\Asset\Models\Tag::class, function (Faker $faker) use ($factory){
    $title = $faker->name;
    return [
        'value' => str_slug($title),
        'title'=>$title,
        'description'=>$faker->paragraph
    ];
});
