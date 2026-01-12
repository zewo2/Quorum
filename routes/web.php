<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\TeacherDashboardController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::view('/home', 'general.home')->name('home');

Route::view('/', 'general.home')->name('home2');

// Role-aware portal redirection
Route::get('/portal', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    $role = Auth::user()->role ?? 'student';
    return match ($role) {
        'admin' => redirect()->route('dashboard.admin.index'),
        'teacher' => redirect()->route('dashboard.teacher.index'),
        default => redirect()->route('dashboard.student.index'),
    };
})->name('portal');

Route::prefix('dashboard')->name('dashboard.')->group(function () {
    Route::resource('admin', AdminDashboardController::class)->only(['index'])->names('admin');
    Route::get('/admin/courses', [AdminDashboardController::class, 'courses'])->name('admin.courses');
    Route::get('/admin/timetables', [AdminDashboardController::class, 'timetables'])->name('admin.timetables');

    // User Management CRUD
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::post('users/bulk-action', [UserController::class, 'bulkAction'])->name('users.bulk-action');
        Route::resource('users', UserController::class);
    });

    Route::resource('teacher', TeacherDashboardController::class)->only(['index'])->names('teacher');
    Route::get('/teacher/classes', [TeacherDashboardController::class, 'classes'])->name('teacher.classes');
    Route::get('/teacher/attendance', [TeacherDashboardController::class, 'attendance'])->name('teacher.attendance');

    Route::resource('student', StudentDashboardController::class)->only(['index'])->names('student');
    Route::get('/student/schedule', [StudentDashboardController::class, 'schedule'])->name('student.schedule');
    Route::get('/student/subjects', [StudentDashboardController::class, 'subjects'])->name('student.subjects');
    Route::get('/student/grades', [StudentDashboardController::class, 'grades'])->name('student.grades');
    Route::get('/student/exams', [StudentDashboardController::class, 'exams'])->name('student.exams');
});

Route::prefix('legal')->name('legal.')->group(function () {
    Route::view('/cookies', 'legal.cookies')->name('cookies');
    Route::view('/privacy', 'legal.privacy')->name('privacy');
    Route::view('/terms', 'legal.terms')->name('terms');
});

Route::name('errors.')->group(function () {
    Route::get('/403', fn () => response()->view('errors.403', [], 403))->name('forbidden');
    Route::get('/404', fn () => response()->view('errors.404', [], 404))->name('not-found');
});

Route::fallback(function () {
    return response()->view('utils.fallback', [], 404);
});
