<?php

namespace Meraki\Packages\Auth\Facades;

use Illuminate\Support\Facades\Facade;
use Meraki\Packages\Auth\Contracts\AuthManager;

class MerakiAuth extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return AuthManager::class;
    }
}
