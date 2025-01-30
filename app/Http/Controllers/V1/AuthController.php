<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\LoginUserRequest;
use App\Http\Requests\V1\RegisterUserRequest;
use App\Repositories\V1\AuthRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    public function __construct(private readonly AuthRepository $authRepository)
    {
    }

    public function register(RegisterUserRequest $request): JsonResponse
    {
        $data = $this->authRepository->register($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully',
            'data' => $data,
        ], 201);
    }

    public function login(LoginUserRequest $request): JsonResponse
    {
        $data = $this->authRepository->login($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => $data,
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $this->authRepository->logout($request->user());

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully',
        ]);
    }
}
