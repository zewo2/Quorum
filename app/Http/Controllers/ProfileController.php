<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Display the user's profile
     */
    public function show(User $user): View
    {
        // Get user's enrollment statistics
        $enrolledCourses = $user->enrollments()->with('course')->get();
        $courseCount = $enrolledCourses->count();

        $enrollmentsWithGrades = $enrolledCourses->filter(fn($e) => $e->grade !== null);
        $averageGrade = $enrollmentsWithGrades->count() > 0
            ? round($enrollmentsWithGrades->avg('grade'), 2)
            : 0;

        $gpa = $enrollmentsWithGrades->count() > 0
            ? round($enrollmentsWithGrades->avg(function ($enrollment) {
                return ($enrollment->grade / 20) * 4.0;
            }), 2)
            : 0;

        $totalCredits = $enrolledCourses->sum(function ($enrollment) {
            return $enrollment->course->credits ?? 0;
        });

        return view('profiles.show', [
            'user' => $user,
            'courseCount' => $courseCount,
            'averageGrade' => $averageGrade,
            'gpa' => $gpa,
            'totalCredits' => $totalCredits,
            'enrolledCourses' => $enrolledCourses,
        ]);
    }

    /**
     * Show the edit profile form
     */
    public function edit(User $user): View
    {
        // Ensure user can only edit their own profile
        if (Auth::id() !== $user->id && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        return view('profiles.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update the user's profile
     */
    public function update(UpdateProfileRequest $request, User $user): RedirectResponse
    {
        // Ensure user can only edit their own profile
        if (Auth::id() !== $user->id && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validated();

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old picture if exists
            if ($user->profile_picture && file_exists(storage_path('app/public/' . $user->profile_picture))) {
                unlink(storage_path('app/public/' . $user->profile_picture));
            }

            $file = $request->file('profile_picture');
            $path = $file->store('profiles', 'public');
            $validated['profile_picture'] = $path;
        }

        $user->update($validated);

        return redirect()->route('profile.show', $user)->with('success', 'Profile updated successfully!');
    }
}
