<?php

use App\Http\Controllers\TokenController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/get-token', [TokenController::class, 'getToken'])->name("api.get-token");
Route::post('/refresh-token', [TokenController::class, 'refreshToken'])->name("api.refresh-token");