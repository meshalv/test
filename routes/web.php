<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\LuckyController;
use App\Http\Middleware\CheckLuckyLink;

Route::view('/', 'app');
Route::view('/lucky/{token}', 'app');

Route::prefix('api')->group(function () {
    Route::post('/registration', [RegistrationController::class, 'register']);

    Route::prefix('lucky')->middleware([CheckLuckyLink::class])->group(function () {
        Route::get('{token}', [LuckyController::class, 'main']);
        Route::post('{token}/regenerate', [LuckyController::class, 'regenerate']);
        Route::post('{token}/deactivate', [LuckyController::class, 'deactivate']);
        Route::post('{token}/lucky', [LuckyController::class, 'lucky']);
        Route::get('{token}/history', [LuckyController::class, 'history']);
    });
});
