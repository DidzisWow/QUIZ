<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\QuizController;

// ── Guest routes (not logged in) ─────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',[AuthController::class, 'register']);
});

// ── Auth routes (must be logged in) ──────────────────────
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/',                        [QuizController::class, 'index'])->name('quiz.index');
    Route::get('/quiz/{quiz}',             [QuizController::class, 'show'])->name('quiz.show');
    Route::post('/quiz/{quiz}/submit',     [QuizController::class, 'submit'])->name('quiz.submit');
    Route::get('/result/{attempt}',        [QuizController::class, 'result'])->name('quiz.result');
});
