<?php

use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\DatabaseController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Database management routes
Route::post('database/reset', [DatabaseController::class, 'reset']);

// User routes
Route::apiResource('users', UserController::class);
Route::get('users/{id}/posts', [UserController::class, 'posts']);
Route::get('users/{id}/comments', [UserController::class, 'comments']);

// Post routes
Route::apiResource('posts', PostController::class);
Route::get('posts/{id}/comments', [PostController::class, 'comments']);

// Comment routes
Route::apiResource('comments', CommentController::class); 