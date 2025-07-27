<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\LoginUserRequest;
use App\Http\Requests\V1\RegisterUserRequest;
use App\Services\V1\AuthService;
use App\Traits\V1\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ApiResponseTrait;

    public function __construct(private readonly AuthService $authService)
    {
    }

    public function register(RegisterUserRequest $request): JsonResponse
    {
        $data = $this->authService->register($request->validated());
        return $this->successResponse('Registration Successful!', $data, 201);
    }

    public function login(LoginUserRequest $request): JsonResponse
    {
        $data = $this->authService->login($request->validated());

        return $this->successResponse('Login Successful!', $data);

    }

    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());

        return $this->successResponse('Logged out Successfully!');

    }
}
