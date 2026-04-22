<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\AdminController;

// ── Guest ─────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',     [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',    [AuthController::class, 'login']);
    Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// ── Auth ──────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Quiz
    Route::get('/',                    [QuizController::class, 'index'])->name('quiz.index');
    Route::get('/quiz/{quiz}',         [QuizController::class, 'show'])->name('quiz.show');
    Route::post('/quiz/{quiz}/submit', [QuizController::class, 'submit'])->name('quiz.submit');
    Route::get('/result/{attempt}',    [QuizController::class, 'result'])->name('quiz.result');

    // History
    Route::get('/history', [HistoryController::class, 'index'])->name('history');
});

// ── Admin (admin role only) ───────────────────────────────
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/',                               [AdminController::class, 'index'])->name('admin.index');
    Route::get('/quiz/create',                    [AdminController::class, 'create'])->name('admin.quiz.create');
    Route::post('/quiz',                          [AdminController::class, 'store'])->name('admin.quiz.store');
    Route::get('/quiz/{quiz}/questions',          [AdminController::class, 'manageQuestions'])->name('admin.quiz.questions');
    Route::post('/quiz/{quiz}/questions',         [AdminController::class, 'storeQuestion'])->name('admin.quiz.storeQuestion');
    Route::delete('/question/{question}',         [AdminController::class, 'deleteQuestion'])->name('admin.question.delete');
    Route::post('/quiz/{quiz}/toggle',            [AdminController::class, 'togglePublish'])->name('admin.quiz.toggle');
    Route::delete('/quiz/{quiz}',                 [AdminController::class, 'deleteQuiz'])->name('admin.quiz.delete');
    Route::post('/category',                      [AdminController::class, 'storeCategory'])->name('admin.category.store');
});