<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;


Route::prefix('admin')->middleware(['auth:sanctum', 'can:admin'])->group(function () {
    Route::apiResource('users', UserController::class)->middleware(['auth:sanctum', 'can:admin']);
});
