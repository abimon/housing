<?php

use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::controller(UserController::class)->group(function(){
    Route::prefix('/user/')->group(function(){
        Route::post('Login','index');
        Route::post('Register','store');
        Route::put('update/{id}','update')->middleware('auth:sanctum');
    });
});