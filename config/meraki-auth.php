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

];
