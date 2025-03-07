<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\ProductResource;
use App\Http\Resources\V1\SingleProductResource;
use App\Repositories\V1\ProductRepository;
use App\Traits\V1\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    use ApiResponseTrait;

    public function __construct(private readonly ProductRepository $productRepository)
    {
    }

    public function index(): JsonResponse
    {
        try {
            return $this->successResponse('success', SingleProductResource::collection($this->productRepository->newArrivalProducts()));
        } catch (\Exception $exception) {
            return $this->errorResponse('error', $exception->getMessage());
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $product = $this->productRepository->findProductById($id);
            $data = $product->loadMissing('reviews');

            return $this->successResponse('success', new ProductResource($data));
        } catch (\Exception $exception) {
            return $this->errorResponse('error', $exception->getMessage());
        }
    }
}
