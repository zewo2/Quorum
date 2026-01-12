<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = [
            [
                'name' => 'Computer Science',
                'code' => 'CS101',
                'description' => 'Introduction to Computer Science and Programming',
                'department' => 'Engineering',
                'year' => 1,
                'semester' => 1,
                'capacity' => 60,
                'status' => 'active',
            ],
            [
                'name' => 'Data Structures and Algorithms',
                'code' => 'CS201',
                'description' => 'Advanced data structures and algorithm design',
                'department' => 'Engineering',
                'year' => 2,
                'semester' => 1,
                'capacity' => 50,
                'status' => 'active',
            ],
            [
                'name' => 'Database Systems',
                'code' => 'CS301',
                'description' => 'Relational databases, SQL, and NoSQL systems',
                'department' => 'Engineering',
                'year' => 3,
                'semester' => 1,
                'capacity' => 45,
                'status' => 'active',
            ],
            [
                'name' => 'Web Development',
                'code' => 'CS202',
                'description' => 'Modern web development with HTML, CSS, JavaScript, and frameworks',
                'department' => 'Engineering',
                'year' => 2,
                'semester' => 2,
                'capacity' => 55,
                'status' => 'active',
            ],
            [
                'name' => 'Artificial Intelligence',
                'code' => 'CS401',
                'description' => 'Machine learning, neural networks, and AI applications',
                'department' => 'Engineering',
                'year' => 4,
                'semester' => 1,
                'capacity' => 40,
                'status' => 'active',
            ],
            [
                'name' => 'Software Engineering',
                'code' => 'CS302',
                'description' => 'Software development lifecycle, design patterns, and best practices',
                'department' => 'Engineering',
                'year' => 3,
                'semester' => 2,
                'capacity' => 50,
                'status' => 'active',
            ],
            [
                'name' => 'Business Management',
                'code' => 'BUS101',
                'description' => 'Fundamentals of business management and organizational behavior',
                'department' => 'Business',
                'year' => 1,
                'semester' => 1,
                'capacity' => 70,
                'status' => 'active',
            ],
            [
                'name' => 'Marketing Principles',
                'code' => 'BUS201',
                'description' => 'Marketing strategies, consumer behavior, and digital marketing',
                'department' => 'Business',
                'year' => 2,
                'semester' => 1,
                'capacity' => 60,
                'status' => 'active',
            ],
        ];

        foreach ($courses as $course) {
            Course::create($course);
        }
    }
}
