<?php

use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\AppartmentsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::controller(UserController::class)->prefix('/user')->group(function(){
        Route::post('/Login','index');
        Route::post('/Register','store');
        Route::put('/update/{id}','update')->middleware('auth:sanctum');
});
Route::controller(AppartmentsController::class)->prefix('/property')->group(function () {
    Route::get('/index','index');
    Route::post('/store','store')->middleware('auth:sanctum');
    Route::put('/update/{id}','update')->middleware('auth:sanctum'); 
});