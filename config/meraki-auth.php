<?php

return [

    'enabled' => env('MERAKI_AUTH_ENABLED', true),

    /*
     * The Eloquent user model used by the auth service.
     * Override with your own model if needed.
     */
    'user_model' => \Meraki\Packages\Auth\Models\User::class,

    /*
     * Permissions declared by this package.
     * Collected by PermissionRegistry (meraki-core) and consumed by IAM packages.
     */
    'permissions' => [
        ['module' => 'auth', 'name' => 'auth.login',           'label' => 'Login'],
        ['module' => 'auth', 'name' => 'auth.register',        'label' => 'Register'],
        ['module' => 'auth', 'name' => 'auth.logout',          'label' => 'Logout'],
        ['module' => 'auth', 'name' => 'auth.password.change', 'label' => 'Change Password'],
        ['module' => 'auth', 'name' => 'auth.password.reset',  'label' => 'Reset Password'],
    ],

    /*
     * UI theming — all values become CSS custom properties injected into every view.
     * Override any key after publishing config; no need to publish views.
     */
    'ui' => [
        'theme' => [
            'bg'           => '#FDFDFC',
            'bg_dark'      => '#0a0a0a',
            'text'         => '#1b1b18',
            'text_dark'    => '#EDEDEC',
            'primary'      => '#6c63ff',
            'primary_dark' => '#8b85ff',
            'border'       => '#e3e3e0',
            'border_dark'  => '#3E3E3A',
            'radius'       => '0.5rem',
            'font'         => '"Instrument Sans", sans-serif',
        ],
    ],

];
