<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PrisonerController;
use App\Http\Controllers\CellController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\UserLogController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Auth\TwoFactorController;
use App\Http\Controllers\Auth\LoginController;

// Custom login route
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// Admin routes
Route::middleware(['auth', 'permission:admin.access'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Rollenbeheer routes
    Route::middleware('permission:admin.roles.manage')->group(function () {
        Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
        Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
        Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
        Route::get('/roles/{role}', [RoleController::class, 'show'])->name('roles.show');
        Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
        Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
        Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
        Route::get('/roles/{role}/permissions', [RoleController::class, 'permissions'])->name('roles.permissions');
        Route::put('/roles/{role}/permissions', [RoleController::class, 'updatePermissions'])->name('roles.updatePermissions');
    });

    // Gebruikersbeheer routes
    Route::middleware('permission:admin.users.view')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
    });
    Route::middleware('permission:admin.users.create')->group(function () {
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
    });
    Route::middleware('permission:admin.users.edit')->group(function () {
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    });
    Route::middleware('permission:admin.users.delete')->group(function () {
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });
});

// Routes for authenticated users
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/two-factor', [ProfileController::class, 'toggleTwoFactor'])->name('profile.two-factor.toggle');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/logout', function () {
        Auth::logout();
        return redirect('/');
    })->name('logout');

    Route::get('/cells', [CellController::class, 'index'])->name('cells.index');
    Route::resource('cells', CellController::class)->except(['index']);

    Route::get('/user-logs', [UserLogController::class, 'index'])->name('user-logs.index');
    Route::get('/user-logs/{log}', [UserLogController::class, 'show'])->name('user-logs.show');

    Route::get('/prisoners', [PrisonerController::class, 'index'])->name('prisoners.index');
    Route::get('/prisoners/create', [PrisonerController::class, 'create'])->name('prisoners.create');
    Route::post('/prisoners', [PrisonerController::class, 'store'])->name('prisoners.store');
    Route::get('/prisoners/{prisoner}/edit', [PrisonerController::class, 'edit'])->name('prisoners.edit');
    Route::put('/prisoners/{prisoner}', [PrisonerController::class, 'update'])->name('prisoners.update');
    Route::patch('/prisoners/{prisoner}', [PrisonerController::class, 'update']);
    Route::delete('/prisoners/{prisoner}', [PrisonerController::class, 'destroy'])->name('prisoners.destroy');
    Route::get('/prisoners/{prisoner}', [PrisonerController::class, 'show'])->name('prisoners.show');

    Route::post('/prisoners/{prisoner}/logs', [PrisonerController::class, 'storeLog'])->name('prisoners.logs.store');
    Route::delete('/logs/{log}', [PrisonerController::class, 'deleteLog'])->name('prisoners.logs.delete');

    Route::post('/prisoners/{prisoner}/move', [PrisonerController::class, 'move'])->name('prisoners.move');
    Route::post('/prisoners/{prisoner}/release', [PrisonerController::class, 'release'])->name('prisoners.release');
});

// Public routes
Route::get('/', function () {
    return view('welcome');
});
Route::get('/contact', [ContactController::class, 'showForm'])->name('contact');
Route::post('/contact', [ContactController::class, 'handleForm'])->name('contact.submit');

// 2FA routes with rate limiting
Route::middleware('throttle:6,1')->group(function () {
    Route::get('/two-factor-challenge', [TwoFactorController::class, 'show'])->name('two-factor.login');
    Route::post('/two-factor-challenge', [TwoFactorController::class, 'verify'])->name('two-factor.verify');
});

Route::post('prisoners/{prisoner}/antecedents', [PrisonerController::class, 'storeAntecedent'])->name('prisoners.antecedents.store');
Route::delete('prisoners/antecedents/{antecedent}', [PrisonerController::class, 'deleteAntecedent'])->name('prisoners.antecedents.delete');
Route::post('prisoners/{prisoner}/interrogations', [PrisonerController::class, 'storeInterrogation'])->name('prisoners.interrogations.store');
Route::delete('prisoners/interrogations/{interrogation}', [PrisonerController::class, 'deleteInterrogation'])->name('prisoners.interrogations.delete');
// Auth routes
Route::middleware('auth')->group(function () {
    // Beveiligde bestandsdownloads
    Route::get('/files/{type}/{filename}', [PrisonerController::class, 'viewFile'])
        ->where('type', 'interrogations|antecedents|logs')
        ->where('filename', '.*')
        ->name('files.view');
    
    Route::get('/files/download/{type}/{filename}', [PrisonerController::class, 'downloadFile'])
        ->where('type', 'interrogations|antecedents|logs')
        ->where('filename', '.*')
        ->name('files.download');
});
require __DIR__.'/auth.php';