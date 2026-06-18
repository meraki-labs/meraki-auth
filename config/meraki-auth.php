<?php

return [

    'enabled' => env('MERAKI_AUTH_ENABLED', true),

    'user_model' => env('MERAKI_AUTH_USER_MODEL', \Meraki\Packages\Auth\Models\User::class),

    'default_platform' => env('MERAKI_AUTH_PLATFORM', 'web'),

    'platforms' => [
        'web' => [
            'enabled'    => true,
            'driver'     => 'web',
            'routes'     => [
                'prefix'     => '',
                'middleware' => ['web'],
            ],
            'redirects'  => [
                'login'    => env('MERAKI_AUTH_REDIRECT_LOGIN', '/dashboard'),
                'logout'   => env('MERAKI_AUTH_REDIRECT_LOGOUT', '/'),
                'register' => env('MERAKI_AUTH_REDIRECT_REGISTER', '/dashboard'),
            ],
        ],
        'api' => [
            'enabled'    => env('MERAKI_AUTH_API_ENABLED', false),
            'driver'     => 'api',
            'routes'     => [
                'prefix'     => 'api/auth',
                'middleware' => ['api'],
            ],
            'token'      => [
                'name'      => env('MERAKI_AUTH_TOKEN_NAME', 'meraki-auth-token'),
                'expiry'    => env('MERAKI_AUTH_TOKEN_EXPIRY', null),
                'abilities' => ['*'],
            ],
        ],
        'spa' => [
            'enabled'    => env('MERAKI_AUTH_SPA_ENABLED', false),
            'driver'     => 'spa',
            'routes'     => [
                'prefix'     => 'spa/auth',
                'middleware' => ['web', 'auth:sanctum'],
            ],
        ],
    ],

    'features' => [
        'registration'       => env('MERAKI_AUTH_REGISTRATION', true),
        'email_verification' => env('MERAKI_AUTH_EMAIL_VERIFY', true),
        'password_reset'     => env('MERAKI_AUTH_PASSWORD_RESET', true),
        'remember_me'        => env('MERAKI_AUTH_REMEMBER_ME', true),
    ],

    'permissions' => [
        ['module' => 'auth', 'name' => 'auth.login',           'label' => 'Login'],
        ['module' => 'auth', 'name' => 'auth.register',        'label' => 'Register'],
        ['module' => 'auth', 'name' => 'auth.logout',          'label' => 'Logout'],
        ['module' => 'auth', 'name' => 'auth.password.change', 'label' => 'Change Password'],
        ['module' => 'auth', 'name' => 'auth.password.reset',  'label' => 'Reset Password'],
    ],

];
