<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\LoginUserRequest;
use App\Http\Requests\V1\RegisterUserRequest;
use App\Services\V1\AuthService;
use App\Traits\V1\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;


/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="My API Documentation",
 *      description="Swagger OpenAPI documentation for Laravel API",
 *      @OA\Contact(
 *          email="developer@example.com"
 *      )
 * )
 *
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="Local API Server"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
class AuthController extends Controller
{
    use ApiResponseTrait;

    public function __construct(private readonly AuthService $authService)
    {
    }

    /**
     * @OA\Post(
     *      path="/api/v1/auth/register",
     *      operationId="registerUser",
     *      tags={"Authentication"},
     *      summary="Register a new user",
     *      description="Registers a user and returns a JWT token.",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name","email","password"},
     *              @OA\Property(property="name", type="string", example="Ali Yucefaydali"),
     *              @OA\Property(property="email", type="string", format="email", example="ali@example.com"),
     *              @OA\Property(property="password", type="string", format="password", example="secret123"),
     *              @OA\Property(property="password_confirmation", type="string", format="password", example="secret123")
     *          )
     *      ),
     *      @OA\Response(
     *           response=201,
     *           description="User successfully registered",
     *           @OA\JsonContent(
     *               @OA\Property(property="success", type="boolean", example=true),
     *               @OA\Property(property="message", type="string", example="Registration Successful!"),
     *               @OA\Property(
     *                   property="data",
     *                   type="object",
     *                   @OA\Property(
     *                       property="user",
     *                       type="object",
     *                       @OA\Property(property="name", type="string", example="Ali"),
     *                       @OA\Property(property="email", type="string", example="ali@example.com"),
     *                       @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-12T16:01:30.000000Z"),
     *                       @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-12T16:01:30.000000Z"),
     *                       @OA\Property(property="id", type="integer", example=55)
     *                   ),
     *                   @OA\Property(property="token", type="string", example="71|eQNCkNZXCE3BAafXy3G3CFTuwRyBIRa3I1SIIotg3a2c456c")
     *               )
     *           )
     *       ),
     *      @OA\Response(
     *           response=422,
     *           description="Validation error",
     *           @OA\JsonContent(
     *               @OA\Property(property="success", type="boolean", example=false),
     *               @OA\Property(property="message", type="string", example="Validation failed"),
     *               @OA\Property(
     *                   property="errors",
     *                   type="object",
     *                   @OA\Property(
     *                       property="email",
     *                       type="array",
     *                       @OA\Items(type="string", example="The email has already been taken.")
     *                   )
     *               )
     *           )
     *       )
     * )
     */
    public function register(RegisterUserRequest $request): JsonResponse
    {
        $data = $this->authService->register($request->validated());
        return $this->successResponse('Registration Successful!', $data, 201);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/auth/login",
     *     summary="User Login",
     *     description="Authenticate a user and return a Bearer token.",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="ali@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="secret123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login Successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Login Successful!"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="user",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=54),
     *                     @OA\Property(property="name", type="string", example="Ali22"),
     *                     @OA\Property(property="email", type="string", format="email", example="ali3333@example.com"),
     *                     @OA\Property(property="email_verified_at", type="string", nullable=true, example=null),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-10T12:53:30.000000Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-10T12:53:30.000000Z")
     *                 ),
     *                 @OA\Property(property="token", type="string", example="73|ENPAtQGLhrK3BKQ4RnHJw0YHtUFurcLbcjM2ljG36ef50189")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Invalid credentials",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation failed"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="email",
     *                     type="array",
     *                     @OA\Items(type="string", example="Invalid credentials.")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function login(LoginUserRequest $request): JsonResponse
    {
        $data = $this->authService->login($request->validated());

        return $this->successResponse('Login Successful!', $data);

    }

    /**
     * @OA\Post(
     *     path="/api/v1/auth/logout",
     *     summary="User Logout",
     *     description="Invalidate the current user's access token and log them out.",
     *     tags={"Authentication"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Logout Successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Logged out Successfully!"),
     *             @OA\Property(property="data", type="string", nullable=true, example=null)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Logout Failed",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Something went wrong"),
     *             @OA\Property(property="errors", type="string", nullable=true, example=null)
     *         )
     *     )
     * )
     */
    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());

        return $this->successResponse('Logged out Successfully!');

    }
}
