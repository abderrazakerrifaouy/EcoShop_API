<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\api\CategoryController;
use App\Http\Controllers\api\OrderController;
use App\Http\Controllers\api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Js;
use Nette\Utils\Json;

Route::get('/user', function () {
    return Json::encode([
        'id' => 1,
        'name' => 'John Doe',
        'email' => 'john.doe@example.com'
    ]);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
    // user routes
    Route::post('/logout', [AuthController::class, 'logout']);
    // category routes
    Route::post('/categories/create', [CategoryController::class, 'store']);
    Route::get('/categories', [CategoryController::class, 'index']);
    // product routes
    Route::get('/products/user/{id}', [ProductController::class, 'getProductsByIdUser']);
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/category/{categoryId}', [ProductController::class, 'getProductsByCategoryId']);
    Route::get('/products/searchProducts/{query}', [ProductController::class, 'searchProducts']);
    Route::get('/products/show/{id}', [ProductController::class, 'show']);
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);

    // order routes

    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/user/{userId}', [OrderController::class, 'getOrdersByUserId']);
    Route::get('/orders/status/{status}', [OrderController::class, 'getOrdersByStatus']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    Route::put('/orders/{id}', [OrderController::class, 'update']);
    Route::delete('/orders/{id}', [OrderController::class, 'destroy']);
    Route::put('/orders/{id}/status', [OrderController::class, 'updateOrderStatus']);

});


