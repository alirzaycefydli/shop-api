<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\ReviewRequest;
use App\Http\Resources\V1\ReviewResource;
use App\Models\V1\Product;
use App\Services\V1\ReviewService;
use App\Traits\V1\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

class ReviewController extends Controller
{
    use ApiResponseTrait;

    public function __construct(private readonly ReviewService $reviewService)
    {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/reviews/{id}",
     *     summary="Fetch Reviews for a Product",
     *     description="Retrieve all reviews for a specific product. Does not require authentication.",
     *     tags={"Reviews"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the product",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Reviews Retrieved Successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="user_id", type="integer", example=26),
     *                     @OA\Property(property="product_id", type="integer", example=1),
     *                     @OA\Property(property="rating", type="integer", example=2),
     *                     @OA\Property(property="review", type="string", example="desc"),
     *                     @OA\Property(property="title", type="string", example="test"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-06T18:12:55.000000Z")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Failed to Retrieve Reviews",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Error message"),
     *             @OA\Property(property="errors", type="string", nullable=true, example=null)
     *         )
     *     )
     * )
     */
    public function index(Product $product): JsonResponse
    {
        try {
            return $this->successResponse('success', ReviewResource::collection($this->reviewService->getReviewByProduct($product)));
        } catch (\Exception $exception) {
            return $this->errorResponse('error', $exception->getMessage());
        }
    }


    /**
     * @OA\Post(
     *     path="/api/v1/reviews",
     *     summary="Store Review for a Product",
     *     description="Add a review for a product. All parameters are in the request body: product_id, user_id, rating, review, title.",
     *     tags={"Reviews"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="product_id", type="integer", example=1),
     *             @OA\Property(property="user_id", type="integer", example=54),
     *             @OA\Property(property="rating", type="integer", example=1),
     *             @OA\Property(property="review", type="string", example="review 1"),
     *             @OA\Property(property="title", type="string", example="title")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Review Stored Successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="user_id", type="string", example="54"),
     *                 @OA\Property(property="product_id", type="string", example="1"),
     *                 @OA\Property(property="rating", type="string", example="1"),
     *                 @OA\Property(property="review", type="string", example="review 1"),
     *                 @OA\Property(property="title", type="string", example="title"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-13T17:38:00.000000Z")
     *             )
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
     *                     property="user_id",
     *                     type="array",
     *                     @OA\Items(type="string", example="The selected user id is invalid.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Duplicate Review",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="error"),
     *             @OA\Property(property="errors", type="string", example="Review already exists")
     *         )
     *     )
     * )
     */
    public function store(ReviewRequest $request): JsonResponse
    {
        try {
            return $this->successResponse('success', new ReviewResource($this->reviewService->createReview($request)));
        } catch (\Exception $exception) {
            return $this->errorResponse('error', $exception->getMessage());
        }
    }
}
