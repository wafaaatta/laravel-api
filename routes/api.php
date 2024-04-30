<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {

    Route::get('/welcome', function (Request $request) {
        return response()->json([
            'title' => 'Stock Management App', 
        ]);
    });

    // Routes pour l'authentification
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login']);

    // Routes pour les opérations CRUD sur les utilisateurs
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/users', [UserController::class, 'index']);
        Route::get('/users/{id}', [UserController::class, 'show']);
        Route::post('/users', [UserController::class, 'store']);
        Route::put('/users/{id}', [UserController::class, 'update']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);

        // Routes pour les opérations CRUD sur les produits
        Route::get('/products', [ProductController::class, 'index']);
        Route::get('/products/{id}', [ProductController::class, 'show']);
        Route::post('/products', [ProductController::class, 'store']);
        Route::put('/products/{id}', [ProductController::class, 'update']);
        Route::delete('/products/{id}', [ProductController::class, 'destroy']);

        // Routes pour les opérations CRUD sur les catégories
        Route::get('/categories', [CategoryController::class, 'index']); 
        Route::get('/categories/{id}', [CategoryController::class, 'show']); 
        Route::post('/categories', [CategoryController::class, 'store']); 
        Route::put('/categories/{id}', [CategoryController::class, 'update']); 
        Route::delete('/categories/{id}', [CategoryController::class, 'destroy']); 
    });
});
