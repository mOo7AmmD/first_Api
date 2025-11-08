<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\cartController;
use App\Http\Controllers\FavoritesController;
use App\Http\Controllers\userAuthController;
use App\Http\Controllers\webController;

use Illuminate\Support\Facades\Route;

Route::middleware('jwt')->prefix("secur")->group(function(){
    Route::apiResource('cart',cartController::class);
    Route::apiResource('fav',FavoritesController::class);
});
Route::POST("register",[userAuthController::class,'register']);
Route::POST("login",[userAuthController::class,'login']);
Route::get('web',[webController::class, "index"]);
