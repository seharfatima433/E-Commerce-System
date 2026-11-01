<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;

Route::prefix('v1')->group(function () {
    // Products
    Route::get('/products',            [ProductController::class, 'index']);
    Route::get('/products/{slug}',     [ProductController::class, 'show']);
    Route::get('/categories',          [ProductController::class, 'categories']);
});

// Health check
Route::get('/ping', fn() => response()->json(['status' => 'ok', 'app' => config('app.name')]));
