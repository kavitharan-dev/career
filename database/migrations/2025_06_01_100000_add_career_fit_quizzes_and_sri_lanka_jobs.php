<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('career_domains', function (Blueprint $table) {
            $table->json('sri_lanka_jobs')->nullable()->after('who_should_choose');
        });

        Schema::create('career_fit_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('career_domain_id')->constrained()->cascadeOnDelete();
            $table->string('question_key');
            $table->text('question_text');
            $table->json('options');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['career_domain_id', 'question_key']);
        });

        Schema::create('user_career_fit_quizzes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('career_domain_id')->constrained()->cascadeOnDelete();
            $table->json('answers');
            $table->unsignedTinyInteger('fit_score');
            $table->string('verdict');
            $table->text('verdict_summary')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'career_domain_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_career_fit_quizzes');
        Schema::dropIfExists('career_fit_questions');
        Schema::table('career_domains', function (Blueprint $table) {
            $table->dropColumn('sri_lanka_jobs');
        });
    }
};
