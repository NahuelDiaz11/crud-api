<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/users', [UserController::class, 'index']);
Route::post('/save', [UserController::class, 'store']);
Route::delete('/user/{id}', [UserController::class, 'destroy']);
Route::put('/user/{id}', [UserController::class, 'update']);
