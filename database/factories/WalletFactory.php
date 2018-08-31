<?php

use Faker\Generator as Faker;

use App\{Wallet, User};

$factory->define(Wallet::class, function (Faker $faker) use ($factory) { 
    return [
        'user_id' => $factory->create(User::class)->id,
        'currency' => 'ETH',
        'address' => random_ethereum_address()
    ];
});
