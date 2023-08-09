<?php

use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\SessionController;
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


//--- Auth module ---
Route::post('login', [SessionController::class, 'store'])->name('login');
Route::post('logout', [SessionController::class, 'destroy'])->middleware('auth:sanctum');
Route::post('register', [RegisterController::class, 'store']);


//--- Posts module ---
Route::apiResource('posts', PostController::class)->middleware('auth:sanctum');
Route::prefix('posts')->group(function () {
    Route::get('latest', [PostController::class, 'latest'])->middleware('auth:sanctum');
});


//--- Comments module ---
Route::get('comments/{postId}', [CommentController::class, 'index']);
Route::apiResource('comments', CommentController::class)->only('store', 'destroy' , 'update')->middleware('auth:sanctum');


//--- Noficications module ---
Route::get('notifications/{userId}', [NotificationController::class, 'index']);
Route::get('notifications/{userId}/read-all', [NotificationController::class, 'readAll']);


//--- Admin module ---
Route::prefix('admin')->group(function () {
    Route::apiResource('users', UserController::class)->middleware(['auth:sanctum', 'can:admin']);
})->middleware(['auth:sanctum', 'can:admin']);
