<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('timetables', 'class_date')) {
            Schema::table('timetables', function (Blueprint $table) {
                $table->date('class_date')->nullable()->after('day_of_week');
            });
        }

        try {
            Schema::table('timetables', function (Blueprint $table) {
                $table->index('teacher_subject_id', 'timetables_teacher_subject_id_idx');
            });
        } catch (\Throwable $e) {
        }

        try {
            Schema::table('timetables', function (Blueprint $table) {
                $table->dropUnique('timetables_teacher_subject_id_day_of_week_start_time_unique');
            });
        } catch (\Throwable $e) {
        }

        try {
            Schema::table('timetables', function (Blueprint $table) {
                $table->unique(['teacher_subject_id', 'class_date', 'start_time'], 'timetables_teacher_subject_id_class_date_start_time_unique');
            });
        } catch (\Throwable $e) {
        }
    }

    public function down(): void
    {
        try {
            Schema::table('timetables', function (Blueprint $table) {
                $table->dropUnique('timetables_teacher_subject_id_class_date_start_time_unique');
            });
        } catch (\Throwable $e) {
        }

        try {
            Schema::table('timetables', function (Blueprint $table) {
                $table->dropIndex('timetables_teacher_subject_id_idx');
            });
        } catch (\Throwable $e) {
        }

        if (Schema::hasColumn('timetables', 'class_date')) {
            Schema::table('timetables', function (Blueprint $table) {
                $table->dropColumn('class_date');
            });
        }

        try {
            Schema::table('timetables', function (Blueprint $table) {
                $table->unique(['teacher_subject_id', 'day_of_week', 'start_time'], 'timetables_teacher_subject_id_day_of_week_start_time_unique');
            });
        } catch (\Throwable $e) {
        }
    }
};
