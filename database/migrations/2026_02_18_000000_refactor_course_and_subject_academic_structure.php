<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            if (!Schema::hasColumn('courses', 'total_years')) {
                $table->unsignedTinyInteger('total_years')->default(3)->after('department');
            }

            if (Schema::hasColumn('courses', 'year')) {
                $table->dropColumn('year');
            }

            if (Schema::hasColumn('courses', 'semester')) {
                $table->dropColumn('semester');
            }
        });

        Schema::table('subjects', function (Blueprint $table) {
            if (!Schema::hasColumn('subjects', 'year')) {
                $table->unsignedTinyInteger('year')->default(1)->after('credits');
            }

            if (!Schema::hasColumn('subjects', 'semester')) {
                $table->unsignedTinyInteger('semester')->default(1)->after('year');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            if (Schema::hasColumn('subjects', 'semester')) {
                $table->dropColumn('semester');
            }

            if (Schema::hasColumn('subjects', 'year')) {
                $table->dropColumn('year');
            }
        });

        Schema::table('courses', function (Blueprint $table) {
            if (!Schema::hasColumn('courses', 'year')) {
                $table->unsignedTinyInteger('year')->default(1)->after('department');
            }

            if (!Schema::hasColumn('courses', 'semester')) {
                $table->unsignedTinyInteger('semester')->default(1)->after('year');
            }

            if (Schema::hasColumn('courses', 'total_years')) {
                $table->dropColumn('total_years');
            }
        });
    }
};
