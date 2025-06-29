<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmploiController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Route for the homepage '/'
Route::get('/', function () {
    // if (Auth::check()) {
    //     if (Auth::user()->usertype === 'admin') {
    //         return redirect()->route('admin.dashboard');
    //     }
    //     return redirect()->route('dashboard');
    // }
    return view('welcome');
});

// Route for testing the welcome view directly (optional)
Route::get('/test-welcome', function () {
    return view('welcome');
});

// Protected routes (only for authenticated users)
Route::middleware('auth')->group(function () {
    Route::resource('emploi', EmploiController::class);
    Route::resource('emplois', EmploiController::class);

    Route::get('/masse', [EmploiController::class, 'masse'])->name('emploi.masse');

    // Show the masse horaire form
    Route::get('/masse-horaire', [EmploiController::class, 'masseHoraireForm'])->name('masse.form');

    // Get dependent dropdowns
    Route::get('/get-niveaux/{filiere}', [EmploiController::class, 'getNiveaux']);
    Route::get('/get-semestres/{filiere}/{niveau}', [EmploiController::class, 'getSemestres']);
    Route::get('/get-matieres/{filiere}/{niveau}/{semestre}', [EmploiController::class, 'getMatieres']);

    // Handle form submission
    Route::post('/masse-horaire', [EmploiController::class, 'calculateMasseHoraire'])->name('masse.calculate');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Breeze auth routes
require __DIR__.'/auth.php';

// Routes for normal users with 'userMiddlware'
Route::middleware(['auth', 'userMiddlware'])->group(function () {
    Route::get('/dashboard', [UserController::class, 'index'])->name('dashboard');
    Route::resource('emploi', EmploiController::class);
    Route::resource('emplois', EmploiController::class);
});

// Routes for admins with 'adminMiddlware'
Route::middleware(['auth', 'adminMiddlware'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

        // CRUD كامل للـ emploi

        Route::resource('emploi', EmploiController::class);
    Route::get('/emploi/create', [EmploiController::class, 'create'])->name('emploi.create');
    Route::post('/emploi', [EmploiController::class, 'store'])->name('emploi.store');
    Route::get('/emploi/{emploi}/edit', [EmploiController::class, 'edit'])->name('emploi.edit');
    Route::put('/emploi/{emploi}', [EmploiController::class, 'update'])->name('emploi.update');
    Route::delete('/emploi/{emploi}', [EmploiController::class, 'destroy'])->name('emploi.destroy');

    // باقي صفحات الادمن
    Route::get('/masse', [EmploiController::class, 'masse'])->name('emploi.masse');

    Route::get('/get-niveaux/{filiere}', [EmploiController::class, 'getNiveaux']);
    Route::get('/get-semestres/{filiere}/{niveau}', [EmploiController::class, 'getSemestres']);
    Route::get('/get-matieres/{filiere}/{niveau}/{semestre}', [EmploiController::class, 'getMatieres']);
});
