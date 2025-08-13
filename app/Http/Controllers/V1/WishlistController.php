<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\WishlistRequest;
use App\Http\Resources\V1\WishlistResource;
use App\Models\V1\Product;
use App\Services\V1\WishlistService;
use App\Traits\V1\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class WishlistController extends Controller
{
    use ApiResponseTrait;

    public function __construct(private readonly WishlistService $wishlistService)
    {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/wishlist",
     *     summary="Fetch Wishlist Products",
     *     description="Retrieve the list of products in the authenticated user's wishlist. Requires authentication.",
     *     tags={"Wishlist"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Wishlist Retrieved Successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(type="object")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthorized"),
     *             @OA\Property(property="errors", type="string", nullable=true, example=null)
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        try {
            return $this->successResponse('Success', WishlistResource::collection($this->wishlistService->getWishlist()));
        } catch (\Exception $exception) {
            return $this->errorResponse('Error', $exception->getMessage());
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/wishlist",
     *     summary="Add Product to Wishlist",
     *     description="Add a product to the authenticated user's wishlist. Requires product_id in the request body.",
     *     tags={"Wishlist"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="product_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product Added Successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Success"),
     *             @OA\Property(property="data", type="string", nullable=true, example=null)
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation Failed",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation failed"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="product_id",
     *                     type="array",
     *                     @OA\Items(type="string", example="The selected product id is invalid.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthorized"),
     *             @OA\Property(property="errors", type="string", nullable=true, example=null)
     *         )
     *     )
     * )
     */
    public function store(WishlistRequest $request): JsonResponse
    {
        try {
            return $this->successResponse('Success', $this->wishlistService->addToWishlist($request));
        } catch (\Exception $exception) {
            return $this->errorResponse('Error', $exception->getMessage());
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/wishlist/{product_id}",
     *     summary="Remove Product from Wishlist",
     *     description="Remove a product from the authenticated user's wishlist. Requires product_id in the URL.",
     *     tags={"Wishlist"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="product_id",
     *         in="path",
     *         required=true,
     *         description="ID of the product to remove from wishlist",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product Removed Successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Success"),
     *             @OA\Property(property="data", type="string", nullable=true, example=null)
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthorized"),
     *             @OA\Property(property="errors", type="string", nullable=true, example=null)
     *         )
     *     )
     * )
     */
    public function destroy(Product $product): JsonResponse
    {
        try {
            return $this->successResponse('Success', $this->wishlistService->removeFromWishlist($product));
        } catch (\Exception $exception) {
            return $this->errorResponse('Error', $exception->getMessage());
        }
    }
}
