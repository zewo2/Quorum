<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Database\Seeder;

class EnrollmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = User::where('role', 'student')->get();
        $courses = Course::all();

        // Enroll first 3 named students in specific courses
        $alice = User::where('email', 'alice.williams@student.quorum.edu')->first();
        $bob = User::where('email', 'bob.martinez@student.quorum.edu')->first();
        $emma = User::where('email', 'emma.davis@student.quorum.edu')->first();

        $cs101 = Course::where('code', 'CS101')->first();
        $cs201 = Course::where('code', 'CS201')->first();
        $cs301 = Course::where('code', 'CS301')->first();
        $cs202 = Course::where('code', 'CS202')->first();
        $bus101 = Course::where('code', 'BUS101')->first();

        // Alice's enrollments
        if ($alice && $cs101) {
            Enrollment::create([
                'student_id' => $alice->id,
                'course_id' => $cs101->id,
                'status' => 'completed',
                'final_grade' => 18.5,
                'notes' => 'Excellent performance throughout the semester',
            ]);
        }

        if ($alice && $cs201) {
            Enrollment::create([
                'student_id' => $alice->id,
                'course_id' => $cs201->id,
                'status' => 'active',
            ]);
        }

        // Bob's enrollments
        if ($bob && $cs101) {
            Enrollment::create([
                'student_id' => $bob->id,
                'course_id' => $cs101->id,
                'status' => 'active',
            ]);
        }

        if ($bob && $bus101) {
            Enrollment::create([
                'student_id' => $bob->id,
                'course_id' => $bus101->id,
                'status' => 'active',
            ]);
        }

        // Emma's enrollments
        if ($emma && $cs201) {
            Enrollment::create([
                'student_id' => $emma->id,
                'course_id' => $cs201->id,
                'status' => 'active',
            ]);
        }

        if ($emma && $cs202) {
            Enrollment::create([
                'student_id' => $emma->id,
                'course_id' => $cs202->id,
                'status' => 'active',
            ]);
        }

        // Enroll random students in random courses
        $remainingStudents = $students->filter(function($student) use ($alice, $bob, $emma) {
            return $student->id !== $alice?->id
                && $student->id !== $bob?->id
                && $student->id !== $emma?->id;
        });

        foreach ($remainingStudents as $student) {
            // Each student enrolls in 1-3 random courses
            $numEnrollments = rand(1, 3);
            $selectedCourses = $courses->random(min($numEnrollments, $courses->count()));

            foreach ($selectedCourses as $course) {
                // Check if enrollment already exists
                $exists = Enrollment::where('student_id', $student->id)
                    ->where('course_id', $course->id)
                    ->exists();

                if (!$exists) {
                    $status = ['active', 'active', 'active', 'completed'][rand(0, 3)];

                    Enrollment::create([
                        'student_id' => $student->id,
                        'course_id' => $course->id,
                        'status' => $status,
                        'final_grade' => $status === 'completed' ? rand(100, 200) / 10 : null,
                    ]);
                }
            }
        }
    }
}
