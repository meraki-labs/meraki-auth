<?php

namespace Meraki\Packages\Auth\Contracts;

use Symfony\Component\HttpFoundation\Response;

interface AuthResultContract
{
    public function success(): bool;

    public function toResponse(): Response;

    public function data(): array;
}
