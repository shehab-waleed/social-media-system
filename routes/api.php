<?php

use App\Http\Controllers\Api\Auth\OtpController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\SessionController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\FollowingController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\PostController;
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
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('comments/{postId}', [CommentController::class, 'index']);
    Route::apiResource('comments', CommentController::class)->only('store', 'destroy', 'update');
});

//--- Notifications module ---
Route::prefix('notifications')->middleware('auth:sanctum')->group(function () {
    Route::get('', [NotificationController::class, 'index']);
    Route::get('read-all', [NotificationController::class, 'readAll']);
});

//--- Likes module ---
Route::middleware('auth:sanctum')->group(function () {
    Route::post('post/like', LikeController::class)->name('post.like');
    Route::post('comment/like', LikeController::class)->name('comment.like');
});

//--- Following module ---
Route::controller(FollowingController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('followed', 'index')->name('followed.index');
    Route::get('followers', 'index')->name('followers.index');
    Route::post('follow/{followedUser}', 'store');
    Route::delete('unfollow/{followedUser}', 'destroy');
});
