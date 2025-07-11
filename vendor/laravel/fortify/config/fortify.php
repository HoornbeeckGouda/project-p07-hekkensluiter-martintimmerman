<?php

return [
    'guard' => 'web',
    'middleware' => ['web'],
    'features' => [
        \Laravel\Fortify\Features::registration(),
        \Laravel\Fortify\Features::resetPasswords(),
        \Laravel\Fortify\Features::emailVerification(),
        \Laravel\Fortify\Features::updateProfileInformation(),
        \Laravel\Fortify\Features::updatePasswords(),
        \Laravel\Fortify\Features::twoFactorAuthentication([
            'confirm' => true,
            'confirmPassword' => true,
        ]),
    ],
    'prefix' => '',
    'home' => '/dashboard',
    'limiters' => [
        'login' => 'login',
        'two-factor' => 'two-factor',
    ],
];