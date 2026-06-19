<?php

namespace Meraki\Packages\Auth\Adapters;

use Illuminate\Support\Facades\Auth;
use Meraki\Core\Contracts\AuthDriver;

class AuthDriverAdapter implements AuthDriver
{
    public function check(): bool
    {
        return Auth::check();
    }

    public function id(): mixed
    {
        return Auth::id();
    }

    public function user(): ?object
    {
        return Auth::user();
    }
}
