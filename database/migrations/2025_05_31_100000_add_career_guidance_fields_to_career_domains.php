<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('career_domains', function (Blueprint $table) {
            $table->string('salary_range')->nullable()->after('description');
            $table->string('job_outlook')->nullable()->after('salary_range');
            $table->string('education_path')->nullable()->after('job_outlook');
            $table->json('typical_roles')->nullable()->after('education_path');
            $table->json('key_skills')->nullable()->after('typical_roles');
            $table->text('day_in_life')->nullable()->after('key_skills');
            $table->text('sri_lanka_context')->nullable()->after('day_in_life');
            $table->text('who_should_choose')->nullable()->after('sri_lanka_context');
        });
    }

    public function down(): void
    {
        Schema::table('career_domains', function (Blueprint $table) {
            $table->dropColumn([
                'salary_range',
                'job_outlook',
                'education_path',
                'typical_roles',
                'key_skills',
                'day_in_life',
                'sri_lanka_context',
                'who_should_choose',
            ]);
        });
    }
};
