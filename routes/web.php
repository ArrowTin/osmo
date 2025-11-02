<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ExamUserController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::view('/','welcome')->name('welcome');

Route::middleware('web')->group(function () {
    Route::get('/leaderboard', [GuestController::class, 'leaderboard'])->name('leaderboard');
    Route::view('/instruction', 'guest.instruction')->name('instruction');
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

Route::middleware(['auth'])->get('dashboard', [HomeController::class, 'index'])->name('dashboard');
Route::middleware(['auth', 'role:admin'])->get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::view('/categories', 'admin.categories')->name('categories');
    Route::view('/questions', 'admin.questions')->name('questions');
    Route::view('/users', 'admin.users')->name('users');
    Route::view('/exams', 'admin.exams')->name('exams');
});

Route::middleware(['auth','role:student'])->prefix('user')->name('students.')->group(function () {
    Route::get('dashboard', [ExamUserController::class, 'dashboard'])->name('dashboard');
    Route::get('ujian', [ExamUserController::class, 'list'])->name('ujian.list');
    Route::get('ujian/{exam}/kerjakan', [ExamUserController::class, 'kerjakan'])->name('ujian.kerjakan');
    Route::post('ujian/{exam}/selesai', [ExamUserController::class, 'selesai'])->name('ujian.selesai');
    Route::get('ujian/{exam}/pembahasan', [ExamUserController::class, 'pembahasan'])->name('ujian.pembahasan');
    Route::get('ujian/{exam}/rangking', [ExamUserController::class, 'rangkingByExam'])->name('ujian.rangking');
    Route::get('perangkingan', [ExamUserController::class, 'perangkingan'])->name('perangkingan');
});

