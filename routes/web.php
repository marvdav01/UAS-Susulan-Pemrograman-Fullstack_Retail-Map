<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CabangTokoController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [CabangTokoController::class, 'index'])->name('toko.index');
Route::post('/toko', [CabangTokoController::class, 'store'])->name('toko.store');
Route::put('/toko/{id}', [CabangTokoController::class, 'update'])->name('toko.update');
Route::delete('/toko/{id}', [CabangTokoController::class, 'destroy'])->name('toko.destroy');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
