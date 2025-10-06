<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ExamController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware(['web','auth:sanctum', 'role:admin'])->group(function () {
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

Route::middleware(['web','auth:sanctum'])->get('/test-session', function (Request $request) {
    return response()->json([
        'success' => true,
        'message' => 'Session terbaca!',
        'user' => $request->user(),
    ]);
});