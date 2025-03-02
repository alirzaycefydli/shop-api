<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CartRequest;
use App\Http\Requests\V1\UpdateCartItemRequest;
use App\Http\Resources\V1\CartResource;
use App\Models\V1\Cart;
use App\Models\V1\Product;
use App\Repositories\V1\CartRepository;
use App\Traits\V1\ApiResponseTrait;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;

class CartController extends Controller
{
    use ApiResponseTrait;

    public function __construct(private readonly CartRepository $cartRepository)
    {
    }

    public function index(): JsonResponse
    {
        try {
            return $this->successResponse('Success', CartResource::collection($this->cartRepository->getCartItems()));
        } catch (\Exception $exception) {
            return $this->errorResponse('Error', $exception->getMessage(), $exception->getCode());
        }
    }

    public function store(CartRequest $request): JsonResponse
    {
        try {
            return $this->successResponse('Success', new CartResource($this->cartRepository->addToCart($request)));
        } catch (\Exception $exception) {
            return $this->errorResponse('Error', $exception->getMessage(), $exception->getCode());
        }
    }

    public function update(UpdateCartItemRequest $request, Product $product): JsonResponse
    {
        try {
            return $this->successResponse('Success', new CartResource($this->cartRepository->updateCartItem($request, $product)));
        } catch (\Exception $exception) {
            return $this->errorResponse('Error', $exception->getMessage(), $exception->getCode());
        }
    }

    public function destroy(Product $product): JsonResponse
    {
        try {
            return $this->successResponse('Success', new CartResource($this->cartRepository->deleteCartItem($product)));
        } catch (\Exception $exception) {
            return $this->errorResponse('Error', $exception->getMessage(), $exception->getCode());
        }
    }
}
