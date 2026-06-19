<?php

namespace Meraki\Packages\Auth\Http\Responses;

use Meraki\Packages\Auth\Contracts\AuthResultContract;
use Symfony\Component\HttpFoundation\Response;

class WebAuthResult implements AuthResultContract
{
    public function __construct(
        private readonly bool $succeeded,
        private readonly string $redirectTo = '/',
        private readonly array $payload = [],
    ) {}

    public function success(): bool
    {
        return $this->succeeded;
    }

    public function toResponse(): Response
    {
        return redirect($this->redirectTo);
    }

    public function data(): array
    {
        return array_merge($this->payload, ['redirect' => $this->redirectTo]);
    }
}
