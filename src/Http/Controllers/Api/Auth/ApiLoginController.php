<?php

namespace Meraki\Packages\Auth\Http\Controllers\Api\Auth;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Meraki\Packages\Auth\Contracts\AuthManager;
use Meraki\Packages\Auth\Http\Requests\Api\LoginRequest;

class ApiLoginController extends Controller
{
    public function __construct(private readonly AuthManager $authManager) {}

    public function store(LoginRequest $request): JsonResponse
    {
        $result = $this->authManager->platform('api')->login($request->only('email', 'password'));

        if (!$result->success()) {
            return response()->json($result->data(), 422);
        }

        return response()->json($result->data());
    }

    public function destroy(): JsonResponse
    {
        $this->authManager->platform('api')->logout();

        return response()->json(['message' => 'Successfully logged out.']);
    }
}
