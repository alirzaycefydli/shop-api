<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CartRequest;
use App\Http\Requests\V1\UpdateCartItemRequest;
use App\Http\Resources\V1\CartResource;
use App\Models\V1\Product;
use App\Services\V1\CartService;
use App\Traits\V1\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

class CartController extends Controller
{
    use ApiResponseTrait;

    public function __construct(private readonly CartService $cartService)
    {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/cart",
     *     summary="Get User's Cart",
     *     description="Retrieve the current authenticated user's cart items. Requires a valid Bearer token.",
     *     tags={"Cart"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Cart Retrieved Successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=32),
     *                 @OA\Property(property="title", type="string", nullable=true, example=null),
     *                 @OA\Property(property="slug", type="string", nullable=true, example=null),
     *                 @OA\Property(property="discounted_price", type="number", format="float", nullable=true, example=null),
     *                 @OA\Property(property="quantity", type="string", example="1"),
     *                 @OA\Property(property="stock", type="integer", nullable=true, example=null),
     *                 @OA\Property(property="discount_percent", type="number", format="float", nullable=true, example=null),
     *                 @OA\Property(property="primary_image", type="string", nullable=true, example=null)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Token Missing or Invalid",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthorized"),
     *             @OA\Property(property="errors", type="string", nullable=true, example=null)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to Retrieve Cart",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Something went wrong"),
     *             @OA\Property(property="errors", type="string", nullable=true, example=null)
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        try {
            return $this->successResponse('Success', CartResource::collection($this->cartService->getCartItems()));
        } catch (\Exception $exception) {
            return $this->errorResponse('Error', $exception->getMessage(), $exception->getCode());
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/cart",
     *     summary="Store Product in Cart",
     *     description="Add a product to the authenticated user's cart. Requires a valid Bearer token.",
     *     tags={"Cart"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="product_id", type="integer", example=32),
     *             @OA\Property(property="quantity", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product Added Successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=32),
     *                 @OA\Property(property="title", type="string", nullable=true, example=null),
     *                 @OA\Property(property="slug", type="string", nullable=true, example=null),
     *                 @OA\Property(property="discounted_price", type="number", format="float", nullable=true, example=null),
     *                 @OA\Property(property="quantity", type="string", example="1"),
     *                 @OA\Property(property="stock", type="integer", nullable=true, example=null),
     *                 @OA\Property(property="discount_percent", type="number", format="float", nullable=true, example=null),
     *                 @OA\Property(property="primary_image", type="string", nullable=true, example=null)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Failed to Add Product",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Error"),
     *             @OA\Property(property="errors", type="string", example="Not enough stock!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Token Missing or Invalid",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthorized"),
     *             @OA\Property(property="errors", type="string", nullable=true, example=null)
     *         )
     *     )
     * )
     */
    public function store(CartRequest $request): JsonResponse
    {
        try {
            return $this->successResponse('Success', new CartResource($this->cartService->addToCart($request)));
        } catch (\Exception $exception) {
            return $this->errorResponse('Error', $exception->getMessage(), $exception->getCode());
        }
    }

    /**
     * @OA\Put(
     *     path="/api/v1/cart",
     *     summary="Update Product in Cart",
     *     description="Update the product to the authenticated user's cart. Requires a valid Bearer token.",
     *     tags={"Cart"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="product_id", type="integer", example=32),
     *             @OA\Property(property="quantity", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product Updated Successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=32),
     *                 @OA\Property(property="title", type="string", nullable=true, example=null),
     *                 @OA\Property(property="slug", type="string", nullable=true, example=null),
     *                 @OA\Property(property="discounted_price", type="number", format="float", nullable=true, example=null),
     *                 @OA\Property(property="quantity", type="string", example="1"),
     *                 @OA\Property(property="stock", type="integer", nullable=true, example=null),
     *                 @OA\Property(property="discount_percent", type="number", format="float", nullable=true, example=null),
     *                 @OA\Property(property="primary_image", type="string", nullable=true, example=null)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Failed to Update Product",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Error"),
     *             @OA\Property(property="errors", type="string", example="Not enough stock!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Token Missing or Invalid",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthorized"),
     *             @OA\Property(property="errors", type="string", nullable=true, example=null)
     *         )
     *     )
     * )
     */
    public function update(UpdateCartItemRequest $request, Product $product): JsonResponse
    {
        try {
            return $this->successResponse('Success', new CartResource($this->cartService->updateCartItem($request, $product)));
        } catch (\Exception $exception) {
            return $this->errorResponse('Error', $exception->getMessage(), $exception->getCode());
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/cart/{product_id}",
     *     summary="Delete Product from Cart",
     *     description="Remove a product from the authenticated user's cart. Requires a valid Bearer token.",
     *     tags={"Cart"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="product_id",
     *         in="path",
     *         required=true,
     *         description="ID of the product to remove from cart",
     *         @OA\Schema(type="integer", example=32)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product Removed Successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=32),
     *                 @OA\Property(property="title", type="string", nullable=true, example=null),
     *                 @OA\Property(property="slug", type="string", nullable=true, example=null),
     *                 @OA\Property(property="discounted_price", type="number", format="float", nullable=true, example=null),
     *                 @OA\Property(property="quantity", type="string", example="1"),
     *                 @OA\Property(property="stock", type="integer", nullable=true, example=null),
     *                 @OA\Property(property="discount_percent", type="number", format="float", nullable=true, example=null),
     *                 @OA\Property(property="primary_image", type="string", nullable=true, example=null)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Failed to Remove Product",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Error"),
     *             @OA\Property(property="errors", type="string", example="Not enough stock!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Token Missing or Invalid",
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
            return $this->successResponse('Success', new CartResource($this->cartService->deleteCartItem($product)));
        } catch (\Exception $exception) {
            return $this->errorResponse('Error', $exception->getMessage(), $exception->getCode());
        }
    }
}
