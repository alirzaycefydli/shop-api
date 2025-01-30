<?php

use App\Http\Controllers\V1\AuthController;
use App\Http\Controllers\V1\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('/v1')->group(function () {

    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register'])->name('auth.register');
        Route::post('login', [AuthController::class, 'login'])->name('auth.login');

        Route::post('logout', [AuthController::class, 'logout'])->name('auth.logout')->middleware('auth:sanctum');
    });

    Route::get('/', [ProductController::class, 'index'])->name('product.index');


});
