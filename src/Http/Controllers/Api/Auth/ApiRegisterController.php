<?php

namespace Meraki\Packages\Auth\Http\Controllers\Api\Auth;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Meraki\Packages\Auth\Contracts\AuthManager;
use Meraki\Packages\Auth\Http\Requests\Api\RegisterRequest;

class ApiRegisterController extends Controller
{
    public function __construct(private readonly AuthManager $authManager) {}

    public function store(RegisterRequest $request): JsonResponse
    {
        $result = $this->authManager->platform('api')->register($request->only('name', 'email', 'password'));

        return response()->json($result->data(), 201);
    }
}
