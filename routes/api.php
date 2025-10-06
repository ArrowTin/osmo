<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ExamController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Halaman login
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::middleware('auth:sanctum')->post('/logout', [AuthenticatedSessionController::class, 'destroy']);


// 'auth:sanctum', 'role:admin'
Route::prefix('admin')->middleware([])->group(function () {
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('questions', QuestionController::class);
    Route::apiResource('users', UserController::class);
    Route::post('users/{user}/reset-password', [UserController::class,'reset']);
    Route::apiResource('exams', ExamController::class);
    Route::post('exams/{exam}/members', [ExamController::class, 'attachUser']);
    Route::get('exams/{exam}/members', [ExamController::class, 'members']);
    Route::post('exams/{exam}/questions', [ExamController::class, 'attachQuestion']);
    Route::get('exams/{exam}/questions', [ExamController::class, 'questions']);

});