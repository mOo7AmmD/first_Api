<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ImgController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\userController;
use App\Http\Controllers\userAuthController;
use Illuminate\Support\Facades\Route;


//Auth
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
//end Auth

Route::middleware('jwt')->prefix("auth")->group(function () {
    Route::get("view",[AdminController::class,"index"]);
    Route::get("select/{id}",[AdminController::class,"select"]);
    Route::post("view",[AdminController::class,"store"]);
    Route::PUT("update/{id}",[AdminController::class,"update"]);
    Route::delete("delete/{id}",[AdminController::class,"destroy"]);


    Route::get('user', [AuthController::class, 'getUser']);
    Route::put('user', [AuthController::class, 'updateUser']);
    Route::post('logout', [AuthController::class, 'logout']);
    //product

    Route::get('productView',[ProductController::class,'index']);
    Route::get('showProduct/{id}',[ProductController::class,'edit']);
    Route::post('insertProduct',[ProductController::class,'store']);
    Route::PUT('updateProduct/{id}',[ProductController::class,'update']);
    Route::delete('destroy/{id}',[ProductController::class,'destroy']);

    //end product
    //start category
    Route::get("categoryView",[CategoryController::class, 'index']);
    Route::delete("categoryDestroy/{id}",[CategoryController::class, 'destroy']);
    Route::post("categoryinsert",[CategoryController::class, 'store']);
    Route::post("categoryshow/{id}",[CategoryController::class, 'show']);
    Route::patch("categoryupdate/{id}",[CategoryController::class, 'update']);
    //end category

    //start user
    Route::get("users",[userController::class, 'index']);
    Route::POST("edit/{id}",[userController::class, 'edit']);
    Route::PUT("updateUser/{id}",[userController::class, 'update']);
    Route::delete("destroy/{id}",[userController::class, 'delete']);
    //end user
});


//start img
Route::get("imgView",[ImgController::class,'index']);
// end img
