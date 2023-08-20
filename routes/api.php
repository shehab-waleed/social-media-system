<?php

use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\OtpController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\SessionController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

//--- Auth module ---
Route::middleware('guest')->group(function () {
    Route::post('login', [SessionController::class, 'store'])->name('login');
    Route::post('register', [RegisterController::class, 'store'])->name('register');
});
Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [SessionController::class, 'destroy'])->name('logout');
    Route::post('otp/verify', [OtpController::class, 'verify'])->name('otp.verify');
});
Route::post('otp/generate', [OtpController::class, 'generate'])->name('otp.generate');

//--- Posts module ---
Route::apiResource('posts', PostController::class)->middleware(['auth:sanctum', 'verified']);
Route::prefix('posts')->middleware('auth:sanctum')->group(function () {
    Route::get('latest', [PostController::class, 'latest']);
});

//--- Comments module ---
Route::middleware(['auth:sanctum , verified'])->group(function () {
    Route::get('comments/{postId}', [CommentController::class, 'index']);
    Route::apiResource('comments', CommentController::class)->only('store', 'destroy', 'update');
});

//--- Notifications module ---
Route::prefix('notifications')->middleware('auth:sanctum')->group(function () {
    Route::get('', [NotificationController::class, 'index']);
    Route::get('read-all', [NotificationController::class, 'readAll']);
});

//--- Admin module ---
Route::prefix('admin')->middleware(['auth:sanctum', 'can:admin'])->group(function () {
    Route::apiResource('users', UserController::class)->middleware(['auth:sanctum', 'can:admin']);
});

//--- Likes module ---
Route::post('post/like', LikeController::class)->middleware('auth:sanctum')->name('post.like');
Route::post('comment/like', LikeController::class)->middleware('auth:sanctum')->name('comment.like');
