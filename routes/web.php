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

// Routes voor alle geauthenticeerde gebruikers (basistoegang)
Route::middleware('auth')->group(function () {
    // Dashboard route na login
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
   
    // Profielroutes toegankelijk voor alle gebruikers
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
   
    // Uitloggen route
    Route::post('/logout', function () {
        Auth::logout();
        return redirect('/');
    })->name('logout');
   
    // Cell index route
    Route::get('/cells', [CellController::class, 'index'])->name('cells.index');
   
    // User logs bekijken
    Route::get('/user-logs', [UserLogController::class, 'index'])->name('user-logs.index');
    Route::get('/user-logs/{log}', [UserLogController::class, 'show'])->name('user-logs.show');
   
    // Prisoners routes - LET OP: volgorde is belangrijk!
    Route::get('/prisoners', [PrisonerController::class, 'index'])->name('prisoners.index');
    Route::get('/prisoners/create', [PrisonerController::class, 'create'])->name('prisoners.create');
    Route::post('/prisoners', [PrisonerController::class, 'store'])->name('prisoners.store');
    Route::get('/prisoners/{prisoner}/edit', [PrisonerController::class, 'edit'])->name('prisoners.edit');
    Route::put('/prisoners/{prisoner}', [PrisonerController::class, 'update'])->name('prisoners.update');
    Route::patch('/prisoners/{prisoner}', [PrisonerController::class, 'update']);
    Route::delete('/prisoners/{prisoner}', [PrisonerController::class, 'destroy'])->name('prisoners.destroy');
    Route::get('/prisoners/{prisoner}', [PrisonerController::class, 'show'])->name('prisoners.show');
   
    // Logboeken toevoegen (voor het geval dat een beheerder dit ook wil doen)
    Route::post('/prisoners/{prisoner}/logs', [PrisonerController::class, 'storeLog'])->name('prisoners.logs.store');
    Route::delete('/logs/{log}', [PrisonerController::class, 'deleteLog'])->name('prisoners.logs.delete');
   
    // Speciale acties voor gevangenen
    Route::post('/prisoners/{prisoner}/move', [PrisonerController::class, 'move'])->name('prisoners.move');
    Route::post('/prisoners/{prisoner}/release', [PrisonerController::class, 'release'])->name('prisoners.release');
   
    // Volledig celbeheer
    Route::resource('cells', CellController::class)->except(['index']);
});

// Openbare routes
Route::get('/', function () {
    return view('welcome');
});
Route::get('/contact', [ContactController::class, 'showForm'])->name('contact');
Route::post('/contact', [ContactController::class, 'handleForm'])->name('contact.submit');

// Auth routes (login, registratie, wachtwoordherstel)
require __DIR__.'/auth.php';