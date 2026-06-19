<?php

namespace Meraki\Packages\Auth\Facades;

use Illuminate\Support\Facades\Facade;
use Meraki\Packages\Auth\Services\AuthDriverManager;

class MerakiAuthDriver extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return AuthDriverManager::class;
    }
}
