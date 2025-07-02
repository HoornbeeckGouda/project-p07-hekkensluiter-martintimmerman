<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PrisonerController;
use App\Http\Controllers\CellController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\UserLogController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\RoleController;

// Admin routes
Route::middleware(['auth', 'permission:admin.access'])->prefix('admin')->name('admin.')->group(function () {
    
    // Admin dashboard
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    
    // Role management routes (inclusief permission management)
    Route::middleware('permission:admin.roles.manage')->group(function () {
        Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
        Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
        Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
        Route::get('/roles/{role}', [RoleController::class, 'show'])->name('roles.show');
        Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
        Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
        Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
        
        // Permission management - nu binnen de roles.manage middleware
        Route::get('/roles/{role}/permissions', [RoleController::class, 'permissions'])->name('roles.permissions');
        Route::put('/roles/{role}/permissions', [RoleController::class, 'updatePermissions'])->name('roles.updatePermissions');
    });
    
    // User management routes
    Route::middleware('permission:admin.users.view')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        // Voeg andere user routes toe als nodig
    });
});

// Routes voor alle geauthenticeerde gebruikers
Route::middleware('auth')->group(function () {
    // Dashboard route na login
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
   
    // Profielroutes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
   
    // Uitloggen route
    Route::post('/logout', function () {
        Auth::logout();
        return redirect('/');
    })->name('logout');
   
    // Cell routes
    Route::get('/cells', [CellController::class, 'index'])->name('cells.index');
    Route::resource('cells', CellController::class)->except(['index']);
   
    // User logs
    Route::get('/user-logs', [UserLogController::class, 'index'])->name('user-logs.index');
    Route::get('/user-logs/{log}', [UserLogController::class, 'show'])->name('user-logs.show');
   
    // Prisoners routes
    Route::get('/prisoners', [PrisonerController::class, 'index'])->name('prisoners.index');
    Route::get('/prisoners/create', [PrisonerController::class, 'create'])->name('prisoners.create');
    Route::post('/prisoners', [PrisonerController::class, 'store'])->name('prisoners.store');
    Route::get('/prisoners/{prisoner}/edit', [PrisonerController::class, 'edit'])->name('prisoners.edit');
    Route::put('/prisoners/{prisoner}', [PrisonerController::class, 'update'])->name('prisoners.update');
    Route::patch('/prisoners/{prisoner}', [PrisonerController::class, 'update']);
    Route::delete('/prisoners/{prisoner}', [PrisonerController::class, 'destroy'])->name('prisoners.destroy');
    Route::get('/prisoners/{prisoner}', [PrisonerController::class, 'show'])->name('prisoners.show');
   
    // Prisoner logs
    Route::post('/prisoners/{prisoner}/logs', [PrisonerController::class, 'storeLog'])->name('prisoners.logs.store');
    Route::delete('/logs/{log}', [PrisonerController::class, 'deleteLog'])->name('prisoners.logs.delete');
   
    // Prisoner actions
    Route::post('/prisoners/{prisoner}/move', [PrisonerController::class, 'move'])->name('prisoners.move');
    Route::post('/prisoners/{prisoner}/release', [PrisonerController::class, 'release'])->name('prisoners.release');
});

// Openbare routes
Route::get('/', function () {
    return view('welcome');
});
Route::get('/contact', [ContactController::class, 'showForm'])->name('contact');
Route::post('/contact', [ContactController::class, 'handleForm'])->name('contact.submit');

// Auth routes
require __DIR__.'/auth.php';