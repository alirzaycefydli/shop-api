<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\CategoryProductResource;
use App\Http\Resources\V1\CategoryResource;
use App\Models\V1\Category;
use App\Repositories\V1\CategoryRepository;
use App\Traits\V1\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use ApiResponseTrait;

    public function __construct(private readonly CategoryRepository $categoryRepository)
    {
    }

    public function index(): JsonResponse
    {
        try {
            return $this->successResponse('Success', CategoryResource::collection($this->categoryRepository->getAllCategories()));
        } catch (\Exception $exception) {
            return $this->errorResponse('Error', $exception->getMessage());
        }
    }

    public function show(Category $category, Request $request): JsonResponse
    {
        try {
            return $this->successResponse('Success', CategoryProductResource::collection($this->categoryRepository->getProductsByCategory($category, $request)));
        } catch (\Exception $exception) {
            return $this->errorResponse('Error', $exception->getMessage());
        }
    }
}
