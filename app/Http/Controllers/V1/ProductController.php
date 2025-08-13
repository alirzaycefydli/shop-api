<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\ProductResource;
use App\Http\Resources\V1\SingleProductResource;
use App\Services\V1\ProductService;
use App\Traits\V1\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    use ApiResponseTrait;

    public function __construct(private readonly ProductService $productService)
    {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/products",
     *     summary="Fetch All Products",
     *     description="Retrieve a list of all products with details including reviews. Does not require authentication.",
     *     tags={"Products"},
     *     @OA\Response(
     *         response=200,
     *         description="Products Retrieved Successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="title", type="string", example="Product 1"),
     *                     @OA\Property(property="image", type="string", format="uri", example="https://picsum.photos/200/300"),
     *                     @OA\Property(property="short_description", type="string", example="desc"),
     *                     @OA\Property(property="brand", type="string", example="test"),
     *                     @OA\Property(property="price", type="number", format="float", example=111.11),
     *                     @OA\Property(property="rating", type="number", format="float", nullable=true, example=2.5),
     *                     @OA\Property(property="discounted_price", type="number", format="float", example=99.999),
     *                     @OA\Property(property="discount_percent", type="number", format="float", example=10),
     *                     @OA\Property(property="is_featured", type="integer", example=1),
     *                     @OA\Property(
     *                         property="reviews",
     *                         type="array",
     *                         @OA\Items(
     *                             @OA\Property(property="rating", type="integer", example=2)
     *                         )
     *                     )
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
    public function index(): JsonResponse
    {
        try {
            return $this->successResponse('success', SingleProductResource::collection($this->productService->newArrivalProducts()));
        } catch (\Exception $exception) {
            return $this->errorResponse('error', $exception->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/products/{id}",
     *     summary="Show Single Product",
     *     description="Retrieve full details of a single product, including all images and reviews. Does not require authentication.",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the product",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product Retrieved Successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Product 1"),
     *                 @OA\Property(property="slug", type="string", example="product-1"),
     *                 @OA\Property(property="description", type="string", example="desc"),
     *                 @OA\Property(property="short_description", type="string", example="desc"),
     *                 @OA\Property(property="brand", type="string", example="test"),
     *                 @OA\Property(property="price", type="number", format="float", example=111.11),
     *                 @OA\Property(property="discounted_price", type="number", format="float", example=99.999),
     *                 @OA\Property(property="quantity", type="integer", example=99),
     *                 @OA\Property(property="discount_percent", type="number", format="float", example=10),
     *                 @OA\Property(property="is_featured", type="integer", example=1),
     *                 @OA\Property(property="primary_image", type="string", format="uri", example="https://picsum.photos/200/300"),
     *                 @OA\Property(
     *                     property="images",
     *                     type="array",
     *                     @OA\Items(type="string", format="uri", example="https://picsum.photos/200/300")
     *                 ),
     *                 @OA\Property(
     *                     property="reviews",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="rating", type="integer", example=2),
     *                         @OA\Property(property="review", type="string", example="desc"),
     *                         @OA\Property(property="title", type="string", example="test"),
     *                         @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-06T18:12:55.000000Z")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Failed to Retrieve Product",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Error message"),
     *             @OA\Property(property="errors", type="string", nullable=true, example=null)
     *         )
     *     )
     * )
     */
    public function show(int $id): JsonResponse
    {
        try {
            $product = $this->productService->findProductById($id);
            $data = $product->loadMissing('reviews');

            return $this->successResponse('success', new ProductResource($data));
        } catch (\Exception $exception) {
            return $this->errorResponse('error', $exception->getMessage());
        }
    }
}
