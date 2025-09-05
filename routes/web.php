<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

// require __DIR__.'/auth.php';



use App\Http\Controllers\ShortUrlController;
use App\Http\Controllers\InvitationController;

Route::middleware(['auth'])->group(function () {
    Route::get('/', function () { return view('dashboard'); })->name('dashboard');

    Route::get('/urls', [ShortUrlController::class, 'index'])->name('urls.index');
    Route::post('/urls', [ShortUrlController::class, 'store'])->name('urls.store');

    // protected redirect (not public)
    Route::get('/s/{code}', [ShortUrlController::class, 'redirect'])->name('short.redirect');

    // invitation routes
    Route::get('/invite', [InvitationController::class, 'create'])->name('invite.create'); // optional UI
    Route::post('/invite', [InvitationController::class, 'store'])->name('invite.store');
});
