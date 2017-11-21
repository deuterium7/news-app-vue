<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Comment::class, function (Faker $faker) {
    return [
        'article_id' => function () {
            return factory(\App\Models\Article::class)->create()->id;
        },
        'user_id'    => function () {
            return factory(\App\Models\User::class)->create()->id;
        },
        'body'       => $faker->paragraph,
    ];
});
