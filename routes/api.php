<?php

use App\Http\Controllers\Api\CourseApiController;
use App\Http\Controllers\Api\SubjectApiController;
use App\Http\Controllers\Api\DepartmentApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('quorum/api')->group(function () {
    // Courses API
    Route::get('/courses', [CourseApiController::class, 'index']);
    Route::get('/courses/{id}', [CourseApiController::class, 'show']);

    // Subjects API
    Route::get('/subjects', [SubjectApiController::class, 'index']);
    Route::get('/subjects/{id}', [SubjectApiController::class, 'show']);

    // Departments API
    Route::get('/departments', [DepartmentApiController::class, 'index']);
    Route::get('/departments/{id}', [DepartmentApiController::class, 'show']);
});
