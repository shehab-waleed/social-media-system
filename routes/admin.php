<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware(['auth:sanctum', 'can:admin'])->group(function () {
    Route::apiResource('users', UserController::class)->middleware(['auth:sanctum', 'can:admin']);
});
