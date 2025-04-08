<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PrisonerController;
use App\Http\Controllers\CellController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Auth;

// Contact routes
Route::get('/contact', [ContactController::class, 'showForm'])->name('contact');
Route::post('/contact', [ContactController::class, 'handleForm'])->name('contact.submit');

// Basisroute
Route::get('/', function () {
    return view('welcome'); 
});

// Dashboard route (controleert of de gebruiker is ingelogd)
Route::get('/dashboard', function () {
    if (!Auth::check()) {
        return redirect('/login'); 
    }
    return view('dashboard');
})->name('dashboard');

// Alleen voor ingelogde gebruikers
Route::middleware('auth')->group(function () {
    // Dashboard route na login
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Routes voor gevangenen
    Route::resource('prisoners', PrisonerController::class);
    Route::post('/prisoners/{prisoner}/move', [PrisonerController::class, 'move'])->name('prisoners.move');
    Route::post('/prisoners/{prisoner}/release', [PrisonerController::class, 'release'])->name('prisoners.release');
    
    // Routes voor cellen
    Route::resource('cells', CellController::class);
    
    // Profielroutes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // routes/web.php
    Route::get('/cells', [CellController::class, 'index'])->name('cells.index');
    // Prisoners
    Route::get('/prisoners', [App\Http\Controllers\PrisonerController::class, 'index'])->name('prisoners.index');

    // Logout route
    Route::post('/logout', function () {
        Auth::logout(); 
        return redirect('/'); 
    })->name('logout');

    // Gebruikersbeheer (zonder middleware voor rollen)
    Route::resource('users', UserController::class);
});

// Auth routes (login, registratie, wachtwoordherstel)
require __DIR__.'/auth.php';
