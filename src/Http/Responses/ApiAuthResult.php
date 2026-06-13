<?php

namespace Meraki\Packages\Auth\Http\Responses;

use Illuminate\Http\JsonResponse;
use Meraki\Packages\Auth\Contracts\AuthResultContract;
use Symfony\Component\HttpFoundation\Response;

class ApiAuthResult implements AuthResultContract
{
    public function __construct(
        private readonly bool $succeeded,
        private readonly array $payload = [],
        private readonly int $statusCode = 200,
    ) {}

    public function success(): bool
    {
        return $this->succeeded;
    }

    public function toResponse(): Response
    {
        return new JsonResponse($this->payload, $this->statusCode);
    }

    public function data(): array
    {
        return $this->payload;
    }
}
