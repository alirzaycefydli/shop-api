<?php

use App\Http\Controllers\V1\AuthController;
use App\Http\Controllers\V1\CartController;
use App\Http\Controllers\V1\CategoryController;
use App\Http\Controllers\V1\ProductController;
use App\Http\Controllers\V1\ReviewController;
use App\Http\Controllers\V1\WishlistController;
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

    Route::get('cart', [CartController::class, 'index'])->name('cart.index')->middleware('auth:sanctum');
    Route::post('cart', [CartController::class, 'store'])->name('cart.store')->middleware('auth:sanctum');
    Route::put('cart/{product}', [CartController::class, 'update'])->name('cart.update')->middleware('auth:sanctum');
    Route::delete('cart/{product}', [CartController::class, 'destroy'])->name('cart.destroy')->middleware('auth:sanctum');

    Route::get('wishlist', [WishlistController::class, 'index'])->name('wishlist.index')->middleware('auth:sanctum');
    Route::post('wishlist', [WishlistController::class, 'store'])->name('wishlist.store')->middleware('auth:sanctum');
    Route::delete('wishlist/{product}', [WishlistController::class, 'destroy'])->name('wishlist.destroy')->middleware('auth:sanctum');

    Route::get('category', [CategoryController::class, 'index'])->name('category.index');
    Route::get('category/{category}', [CategoryController::class, 'show'])->name('category.show');

});
