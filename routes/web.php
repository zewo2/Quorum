<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherSubjectController;
use App\Http\Controllers\TeacherDashboardController;
use App\Http\Controllers\TimetableController;
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
        Route::resource('courses', CourseController::class)->except(['index', 'show']);
        Route::resource('subjects', SubjectController::class);
        Route::resource('enrollments', EnrollmentController::class)->except(['show']);
        Route::resource('teacher-subjects', TeacherSubjectController::class)
            ->except(['show'])
            ->parameters(['teacher-subjects' => 'teacherSubject']);
        Route::resource('timetables', TimetableController::class)->parameters(['timetables' => 'timetable']);
        Route::resource('exams', ExamController::class)->parameters(['exams' => 'exam']);
        Route::resource('rooms', RoomController::class)->parameters(['rooms' => 'room']);
    });

    Route::resource('teacher', TeacherDashboardController::class)->only(['index'])->names('teacher');
    Route::get('/teacher/classes', [TeacherDashboardController::class, 'classes'])->name('teacher.classes');
    Route::get('/teacher/schedule', [TeacherDashboardController::class, 'schedule'])->name('teacher.schedule');
    Route::get('/teacher/attendance', [TeacherDashboardController::class, 'attendance'])->name('teacher.attendance');
    Route::post('/teacher/attendance', [TeacherDashboardController::class, 'storeAttendance'])->name('teacher.attendance.store');

    Route::resource('student', StudentDashboardController::class)->only(['index'])->names('student');
    Route::get('/student/schedule', [StudentDashboardController::class, 'schedule'])->name('student.schedule');
    Route::get('/student/subjects', [StudentDashboardController::class, 'subjects'])->name('student.subjects');
    Route::get('/student/grades', [StudentDashboardController::class, 'grades'])->name('student.grades');
    Route::get('/student/exams', [StudentDashboardController::class, 'exams'])->name('student.exams');
});

Route::prefix('api')->group(function () {
    Route::get('/timetables/teacher/{teacher}', [TimetableController::class, 'byTeacher'])->name('api.timetables.teacher');
    Route::middleware('auth')->get('/timetables/student', [TimetableController::class, 'byStudent'])->name('api.timetables.student');
});

Route::prefix('legal')->name('legal.')->group(function () {
    Route::view('/cookies', 'legal.cookies')->name('cookies');
    Route::view('/privacy', 'legal.privacy')->name('privacy');
    Route::view('/terms', 'legal.terms')->name('terms');
});

// Public user profiles
Route::prefix('profile')->name('profile.')->group(function () {
    Route::get('/{user}', [ProfileController::class, 'show'])->name('show');
    Route::middleware('auth')->group(function () {
        Route::get('/{user}/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/{user}', [ProfileController::class, 'update'])->name('update');
    });
});

Route::name('errors.')->group(function () {
    Route::get('/403', fn () => response()->view('errors.403', [], 403))->name('forbidden');
    Route::get('/404', fn () => response()->view('errors.404', [], 404))->name('not-found');
});

Route::fallback(function () {
    return response()->view('utils.fallback', [], 404);
});
