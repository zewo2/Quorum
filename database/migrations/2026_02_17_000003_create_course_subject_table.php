<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_subject', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->unique(['course_id', 'subject_id']);
        });

        $subjectRows = DB::table('subjects')->select('id', 'course_id')->get();
        $now = now();

        foreach ($subjectRows as $row) {
            DB::table('course_subject')->updateOrInsert(
                [
                    'course_id' => $row->course_id,
                    'subject_id' => $row->id,
                ],
                [
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('course_subject');
    }
};
