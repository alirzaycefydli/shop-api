<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\ReviewRequest;
use App\Http\Resources\V1\ReviewResource;
use App\Models\V1\Product;
use App\Repositories\V1\ReviewRepository;
use App\Traits\V1\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

class ReviewController extends Controller
{
    use ApiResponseTrait;

    public function __construct(private readonly ReviewRepository $reviewRepository)
    {
    }

    public function index(Product $product): JsonResponse
    {
        try {
            return $this->successResponse('success', ReviewResource::collection($this->reviewRepository->getReviewByProduct($product)));
        } catch (\Exception $exception) {
            return $this->errorResponse('error', $exception->getMessage());
        }
    }


    public function store(ReviewRequest $request): JsonResponse
    {
        try {
            return $this->successResponse('success', new ReviewResource($this->reviewRepository->createReview($request)));
        } catch (\Exception $exception) {
            return $this->errorResponse('error', $exception->getMessage());
        }
    }
}
