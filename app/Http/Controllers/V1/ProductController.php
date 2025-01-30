<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\ProductResource;
use App\Repositories\V1\ProductRepository;
use App\Traits\V1\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use ApiResponseTrait;

    public function __construct(private readonly ProductRepository $productRepository)
    {
    }

    public function index(): JsonResponse
    {
        $data = $this->productRepository->newArrivalProducts();

        return $this->successResponse('success', ProductResource::collection($data));
    }
}
