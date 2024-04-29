<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function(){

    Route::get('/welcome', function (Request $request) {

        return reponse()->json
        ([
            'titel'=> 'stock Mangement App',
        ]);
    });

//products
Route::get('/products', [ProductController::class, 'index']);

Route::get('/products/{id}', [ProductController::class, 'show']);

Route::post('/products', [ProductController::class, 'store']);

Route::put('/products/{id}', [ProductController::class, 'update']);

Route::delete('/products/{id}', [ProductController::class, 'destroy']);


//user
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
});