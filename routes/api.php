<?php

use App\Http\Controllers\V1\AuthController;
use App\Http\Controllers\V1\ProductController;
use App\Http\Controllers\V1\ReviewController;
use Illuminate\Support\Facades\Route;

Route::prefix('/v1')->group(function () {

    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register'])->name('auth.register');
        Route::post('login', [AuthController::class, 'login'])->name('auth.login');

        Route::post('logout', [AuthController::class, 'logout'])->name('auth.logout')->middleware('auth:sanctum');
    });

    Route::get('products', [ProductController::class, 'index'])->name('product.index');
    Route::get('products/{product}', [ProductController::class, 'show'])->name('product.show');

    Route::post('reviews', [ReviewController::class, 'store'])->name('review.store');
    Route::get('reviews/{product}', [ReviewController::class, 'index'])->name('review.index');
});
