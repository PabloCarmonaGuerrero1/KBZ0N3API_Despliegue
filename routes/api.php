<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserToken;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ShoppingCartController;

Route::post('/product', [ProductController::class, 'store']);
Route::get('/product', [ProductController::class, 'index']);
Route::put('/product/{product}', [ProductController::class, 'update']);  
Route::delete('/product/{product}', [ProductController::class, 'destroy']); 
Route::get('/product/{product}', [ProductController::class, 'show']);
Route::get('/products/search', [ProductController::class, 'search']);

Route::get('/users', [UserController::class, 'index']);        
Route::post('/users', [UserController::class, 'store']);     
Route::get('/users/{user}', [UserController::class, 'show']);  
Route::put('/users/{user}', [UserController::class, 'update']); 
Route::delete('/users/{user}', [UserController::class, 'destroy']);
Route::get('/users/search/{name}', [UserController::class, 'searchByName']);

Route::get('/reviews', [ReviewController::class, 'index']);        
Route::post('/reviews', [ReviewController::class, 'store']);       
Route::get('/reviews/{review}', [ReviewController::class, 'show']);  
Route::put('/reviews/{review}', [ReviewController::class, 'update']); 
Route::delete('/reviews/{review}', [ReviewController::class, 'destroy']); 

Route::get('/shopping_cart', [ShoppingCartController::class, 'allCarts']);
Route::post('/shopping_cart', [ShoppingCartController::class, 'store']);
Route::get('/shopping_cart/{userId}', [ShoppingCartController::class, 'index']);
Route::put('/shopping_cart/{id}', [ShoppingCartController::class, 'update']);
Route::delete('/shopping_cart/{id}', [ShoppingCartController::class, 'destroy']);
Route::get('/shopping_cart/{userId}/{productId}', [ShoppingCartController::class, 'getCartItem']);

Route::post('/login', function (Request $request) {
    $request->validate([
        'username' => 'required|string',
        'password' => 'required|string',
    ]);

    $user = User::where('name', $request->username)->first();

    if (!$user || $user->password !== $request->password) {
        return response()->json(['error' => 'Invalid credentials'], 401);
    }

    $plainToken = Str::random(60);
    $user->tokens()->create([
        'token' => hash('sha256', $plainToken),
    ]);

    return response()->json([
        'token' => $plainToken,
        'user' => $user->only('id', 'name'),
    ]);
});
Route::get('/me', function (Request $request) {
    $authHeader = $request->header('Authorization');

    if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
        return response()->json(['error' => 'Token no proporcionado'], 401);
    }

    $plainToken = substr($authHeader, 7);
    $hashed = hash('sha256', $plainToken);

    $userToken = UserToken::where('token', $hashed)->first();

    if (!$userToken) {
        return response()->json(['error' => 'Token inválido'], 403);
    }

    $user = $userToken->user;

    return response()->json([
        'id' => $user->id,
        'name' => $user->name,
        'DNI' => $user->DNI,
        'number' => $user->number,
        'adress' => $user->adress,
        'last_buy' => $user->last_buy,
        'fav_food' => $user->fav_food,
        'fav_drink' => $user->fav_drink,
    ]);
});
