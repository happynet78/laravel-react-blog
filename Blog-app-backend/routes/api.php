<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\TempImageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('blogs', [BlogController::class, 'index']);
Route::post('blogs', [BlogController::class, 'store']);
Route::post('save-temp-image', [TempImageController::class, 'store']);
Route::get('blogs/{id}', [BlogController::class, 'show']);
Route::put('blogs/{id}', [BlogController::class, 'update']);
Route::delete('blogs/{id}', [BlogController::class, 'destroy']);
Route::resource('posts', PostController::class);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
