<?php

use App\User;
use Faker\Generator as Faker;

$factory->define(User::class, function (Faker $faker) {
    return [
        'name'  => 'Fake User',
        'email' => 'fake@example.com',
    ];
});

$factory->state(User::class, 'admin', function (Faker $faker) {
    return [
        'name'  => 'Admin',
        'email' => 'admin@example.com',
    ];
});
