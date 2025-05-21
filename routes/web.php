<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/wallets', [WalletController::class, 'index'])->name('wallets');
    Route::get('/wallets/data', [WalletController::class, 'getWalletsData'])->name('wallets.data');
    Route::post('/wallets/store', [WalletController::class, 'store'])->name('wallets.store');
});

require __DIR__.'/auth.php';
