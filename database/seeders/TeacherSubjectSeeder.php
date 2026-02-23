<?php

namespace Database\Seeders;

use App\Models\Subject;
use App\Models\TeacherSubject;
use App\Models\User;
use Illuminate\Database\Seeder;

class TeacherSubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teachers = User::where('role', 'teacher')->get();
        $subjects = Subject::all();

        // Assign named teachers to specific subjects
        $john = User::where('email', 'john.smith@quorum.edu')->first();
        $sarah = User::where('email', 'sarah.johnson@quorum.edu')->first();

        // John teaches CS subjects
        $cs101L1 = Subject::where('code', 'CS101-L1')->first();
        $cs101L2 = Subject::where('code', 'CS101-L2')->first();
        $cs201L1 = Subject::where('code', 'CS201-L1')->first();

        if ($john && $cs101L1) {
            TeacherSubject::create([
                'teacher_id' => $john->id,
                'subject_id' => $cs101L1->id,
                'academic_year' => 2025,
                'semester' => 1,
                'class_capacity' => 50,
                'status' => 'active',
            ]);
        }

        if ($john && $cs101L2) {
            TeacherSubject::create([
                'teacher_id' => $john->id,
                'subject_id' => $cs101L2->id,
                'academic_year' => 2025,
                'semester' => 1,
                'class_capacity' => 50,
                'status' => 'active',
            ]);
        }

        if ($john && $cs201L1) {
            TeacherSubject::create([
                'teacher_id' => $john->id,
                'subject_id' => $cs201L1->id,
                'academic_year' => 2025,
                'semester' => 2,
                'class_capacity' => 45,
                'status' => 'active',
            ]);
        }

        // Sarah teaches Web and Business subjects
        $cs202L1 = Subject::where('code', 'CS202-L1')->first();
        $bus101L1 = Subject::where('code', 'BUS101-L1')->first();
        $bus201L1 = Subject::where('code', 'BUS201-L1')->first();

        if ($sarah && $cs202L1) {
            TeacherSubject::create([
                'teacher_id' => $sarah->id,
                'subject_id' => $cs202L1->id,
                'academic_year' => 2025,
                'semester' => 2,
                'class_capacity' => 40,
                'status' => 'active',
            ]);
        }

        if ($sarah && $bus101L1) {
            TeacherSubject::create([
                'teacher_id' => $sarah->id,
                'subject_id' => $bus101L1->id,
                'academic_year' => 2025,
                'semester' => 1,
                'class_capacity' => 60,
                'status' => 'active',
            ]);
        }

        if ($sarah && $bus201L1) {
            TeacherSubject::create([
                'teacher_id' => $sarah->id,
                'subject_id' => $bus201L1->id,
                'academic_year' => 2025,
                'semester' => 2,
                'class_capacity' => 50,
                'status' => 'active',
            ]);
        }

        // Assign CS302 subjects
        $cs302L1 = Subject::where('code', 'CS302-L1')->first();
        $cs302L2 = Subject::where('code', 'CS302-L2')->first();

        if ($john && $cs302L1) {
            TeacherSubject::create([
                'teacher_id' => $john->id,
                'subject_id' => $cs302L1->id,
                'academic_year' => 2025,
                'semester' => 2,
                'class_capacity' => 45,
                'status' => 'active',
            ]);
        }

        if ($sarah && $cs302L2) {
            TeacherSubject::create([
                'teacher_id' => $sarah->id,
                'subject_id' => $cs302L2->id,
                'academic_year' => 2025,
                'semester' => 2,
                'class_capacity' => 45,
                'status' => 'active',
            ]);
        }

        // Assign remaining teachers to random subjects
        $remainingTeachers = $teachers->filter(function($teacher) use ($john, $sarah) {
            return $teacher->id !== $john?->id && $teacher->id !== $sarah?->id;
        });

        foreach ($remainingTeachers as $teacher) {
            // Each teacher gets 2-4 subjects
            $numAssignments = rand(2, 4);
            $selectedSubjects = $subjects->random(min($numAssignments, $subjects->count()));

            foreach ($selectedSubjects as $subject) {
                // Random academic year and semester
                $academicYear = rand(2024, 2026);
                $semester = rand(1, 2);

                // Check if assignment already exists
                $exists = TeacherSubject::where('teacher_id', $teacher->id)
                    ->where('subject_id', $subject->id)
                    ->where('academic_year', $academicYear)
                    ->where('semester', $semester)
                    ->exists();

                if (!$exists) {
                    TeacherSubject::create([
                        'teacher_id' => $teacher->id,
                        'subject_id' => $subject->id,
                        'academic_year' => $academicYear,
                        'semester' => $semester,
                        'class_capacity' => rand(30, 60),
                        'status' => ['active', 'active', 'inactive'][rand(0, 2)],
                    ]);
                }
            }
        }
    }
}
