<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cs101 = Course::where('code', 'CS101')->first();
        $cs201 = Course::where('code', 'CS201')->first();
        $cs301 = Course::where('code', 'CS301')->first();
        $cs202 = Course::where('code', 'CS202')->first();
        $cs401 = Course::where('code', 'CS401')->first();
        $cs302 = Course::where('code', 'CS302')->first();
        $bus101 = Course::where('code', 'BUS101')->first();
        $bus201 = Course::where('code', 'BUS201')->first();

        $subjects = [
            // CS101 subjects
            [
                'code' => 'CS101-L1',
                'name' => 'Introduction to Programming',
                'description' => 'Basic programming concepts using Python',
                'credits' => 6,
                'course_id' => $cs101->id,
                'status' => 'active',
            ],
            [
                'code' => 'CS101-L2',
                'name' => 'Computer Architecture',
                'description' => 'Hardware fundamentals and computer organization',
                'credits' => 4,
                'course_id' => $cs101->id,
                'status' => 'active',
            ],

            // CS201 subjects
            [
                'code' => 'CS201-L1',
                'name' => 'Data Structures',
                'description' => 'Lists, stacks, queues, trees, and graphs',
                'credits' => 6,
                'course_id' => $cs201->id,
                'status' => 'active',
            ],
            [
                'code' => 'CS201-L2',
                'name' => 'Algorithm Analysis',
                'description' => 'Time complexity, sorting, and searching algorithms',
                'credits' => 6,
                'course_id' => $cs201->id,
                'status' => 'active',
            ],

            // CS301 subjects
            [
                'code' => 'CS301-L1',
                'name' => 'Relational Databases',
                'description' => 'SQL, normalization, and database design',
                'credits' => 5,
                'course_id' => $cs301->id,
                'status' => 'active',
            ],
            [
                'code' => 'CS301-L2',
                'name' => 'NoSQL Databases',
                'description' => 'MongoDB, Redis, and document-oriented databases',
                'credits' => 4,
                'course_id' => $cs301->id,
                'status' => 'active',
            ],

            // CS202 subjects
            [
                'code' => 'CS202-L1',
                'name' => 'Frontend Development',
                'description' => 'HTML, CSS, JavaScript, and React',
                'credits' => 6,
                'course_id' => $cs202->id,
                'status' => 'active',
            ],
            [
                'code' => 'CS202-L2',
                'name' => 'Backend Development',
                'description' => 'Node.js, Express, and RESTful APIs',
                'credits' => 5,
                'course_id' => $cs202->id,
                'status' => 'active',
            ],

            // CS401 subjects
            [
                'code' => 'CS401-L1',
                'name' => 'Machine Learning',
                'description' => 'Supervised and unsupervised learning algorithms',
                'credits' => 6,
                'course_id' => $cs401->id,
                'status' => 'active',
            ],
            [
                'code' => 'CS401-L2',
                'name' => 'Neural Networks',
                'description' => 'Deep learning and neural network architectures',
                'credits' => 5,
                'course_id' => $cs401->id,
                'status' => 'active',
            ],

            // CS302 subjects
            [
                'code' => 'CS302-L1',
                'name' => 'Software Design',
                'description' => 'Design patterns and architectural principles',
                'credits' => 5,
                'course_id' => $cs302->id,
                'status' => 'active',
            ],
            [
                'code' => 'CS302-L2',
                'name' => 'Agile Development',
                'description' => 'Scrum, Kanban, and agile methodologies',
                'credits' => 4,
                'course_id' => $cs302->id,
                'status' => 'active',
            ],

            // BUS101 subjects
            [
                'code' => 'BUS101-L1',
                'name' => 'Management Fundamentals',
                'description' => 'Planning, organizing, and leading',
                'credits' => 5,
                'course_id' => $bus101->id,
                'status' => 'active',
            ],
            [
                'code' => 'BUS101-L2',
                'name' => 'Organizational Behavior',
                'description' => 'Team dynamics and workplace psychology',
                'credits' => 4,
                'course_id' => $bus101->id,
                'status' => 'active',
            ],

            // BUS201 subjects
            [
                'code' => 'BUS201-L1',
                'name' => 'Marketing Strategy',
                'description' => 'Market research and strategic planning',
                'credits' => 5,
                'course_id' => $bus201->id,
                'status' => 'active',
            ],
            [
                'code' => 'BUS201-L2',
                'name' => 'Digital Marketing',
                'description' => 'Social media, SEO, and online advertising',
                'credits' => 5,
                'course_id' => $bus201->id,
                'status' => 'active',
            ],
        ];

        foreach ($subjects as $subject) {
            $createdSubject = Subject::create($subject);
            $createdSubject->courses()->syncWithoutDetaching([$subject['course_id']]);
        }
    }
}
