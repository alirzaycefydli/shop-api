<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\CategoryProductResource;
use App\Http\Resources\V1\CategoryResource;
use App\Models\V1\Category;
use App\Services\V1\CategoryService;
use App\Traits\V1\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class CategoryController extends Controller
{
    use ApiResponseTrait;

    public function __construct(private readonly CategoryService $categoryService)
    {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/category",
     *     summary="Get All Categories",
     *     description="Retrieve a list of categories with their subcategories. Does not require authentication.",
     *     tags={"Categories"},
     *     @OA\Response(
     *         response=200,
     *         description="Categories Retrieved Successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="title", type="string", example="Category 1"),
     *                     @OA\Property(property="slug", type="string", example="category-1"),
     *                     @OA\Property(property="description", type="string", example="Description of Category 1"),
     *                     @OA\Property(property="parent_category_id", type="integer", nullable=true, example=null),
     *                     @OA\Property(
     *                         property="subcategories",
     *                         type="array",
     *                         @OA\Items(
     *                             @OA\Property(property="id", type="integer", example=3),
     *                             @OA\Property(property="title", type="string", example="Sub-Category 1"),
     *                             @OA\Property(property="slug", type="string", example="sub-category-1"),
     *                             @OA\Property(property="description", type="string", example="Description of Sub-Category 1"),
     *                             @OA\Property(property="parent_category_id", type="integer", example=1),
     *                             @OA\Property(property="created_at", type="string", nullable=true, example=null),
     *                             @OA\Property(property="updated_at", type="string", nullable=true, example=null)
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Failed to Retrieve Categories",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Error message"),
     *             @OA\Property(property="errors", type="string", nullable=true, example=null)
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        try {
            return $this->successResponse('Success', CategoryResource::collection($this->categoryService->getAllCategories()));
        } catch (\Exception $exception) {
            return $this->errorResponse('Error', $exception->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/category/{id}",
     *     summary="Show Products in Category",
     *     description="Retrieve the list of products belonging to a specific category. Does not require authentication.",
     *     tags={"Categories"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the category",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Products Retrieved Successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="title", type="string", example="Product 1"),
     *                     @OA\Property(property="slug", type="string", example="product-1"),
     *                     @OA\Property(property="short_description", type="string", example="desc"),
     *                     @OA\Property(property="brand", type="string", example="test"),
     *                     @OA\Property(property="price", type="number", format="float", example=111.11),
     *                     @OA\Property(property="discounted_price", type="number", format="float", example=99.999),
     *                     @OA\Property(property="discount_percent", type="number", format="float", example=10),
     *                     @OA\Property(property="primary_image", type="string", format="uri", example="https://picsum.photos/200/300"),
     *                     @OA\Property(property="rating", type="number", format="float", example=2.5)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Failed to Retrieve Products",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Error message"),
     *             @OA\Property(property="errors", type="string", nullable=true, example=null)
     *         )
     *     )
     * )
     */
    public function show(Category $category, Request $request): JsonResponse
    {
        try {
            return $this->successResponse('Success', CategoryProductResource::collection($this->categoryService->getProductsByCategory($category, $request)));
        } catch (\Exception $exception) {
            return $this->errorResponse('Error', $exception->getMessage());
        }
    }
}
