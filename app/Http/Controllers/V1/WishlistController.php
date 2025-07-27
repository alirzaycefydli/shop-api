<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\WishlistRequest;
use App\Http\Resources\V1\WishlistResource;
use App\Models\V1\Product;
use App\Repositories\V1\WishlistService;
use App\Traits\V1\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    use ApiResponseTrait;

    public function __construct(private readonly WishlistService $wishlistService)
    {
    }

    public function index(): JsonResponse
    {
        try {
            return $this->successResponse('Success', WishlistResource::collection($this->wishlistService->getWishlist()));
        } catch (\Exception $exception) {
            return $this->errorResponse('Error', $exception->getMessage());
        }
    }

    public function store(WishlistRequest $request): JsonResponse
    {
        try {
            return $this->successResponse('Success', $this->wishlistService->addToWishlist($request));
        } catch (\Exception $exception) {
            return $this->errorResponse('Error', $exception->getMessage());
        }
    }

    public function destroy(Product $product): JsonResponse
    {
        try {
            return $this->successResponse('Success', $this->wishlistService->removeFromWishlist($product));
        } catch (\Exception $exception) {
            return $this->errorResponse('Error', $exception->getMessage());
        }
    }
}
