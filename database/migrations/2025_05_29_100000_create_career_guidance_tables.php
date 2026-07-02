<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('education_level')->nullable();
            $table->json('academic_marks')->nullable();
            $table->json('interests')->nullable();
            $table->unsignedTinyInteger('logical_score')->default(0);
            $table->unsignedTinyInteger('problem_solving_score')->default(0);
            $table->boolean('onboarding_completed')->default(false);
            $table->timestamps();
        });

        Schema::create('skills', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['slug', 'user_id']);
        });

        Schema::create('skill_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('skill_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('proficiency')->default(3);
            $table->timestamps();

            $table->unique(['user_id', 'skill_id']);
        });

        Schema::create('career_domains', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->json('trait_weights')->nullable();
            $table->json('required_skill_slugs')->nullable();
            $table->timestamps();
        });

        Schema::create('career_recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('career_domain_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('match_score');
            $table->text('reasoning')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });

        Schema::create('roadmap_step_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('career_domain_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('level');
            $table->unsignedSmallInteger('sort_order');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('daily_task_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('roadmap_step_template_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedSmallInteger('day_number');
            $table->timestamps();
        });

        Schema::create('roadmaps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('career_domain_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->unsignedTinyInteger('completion_percentage')->default(0);
            $table->timestamps();
        });

        Schema::create('roadmap_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('roadmap_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('level');
            $table->unsignedSmallInteger('sort_order');
            $table->text('description')->nullable();
            $table->boolean('is_unlocked')->default(false);
            $table->timestamps();
        });

        Schema::create('daily_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('roadmap_step_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedSmallInteger('day_number');
            $table->string('status')->default('pending');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('app_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type');
            $table->string('title');
            $table->text('message');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('role');
            $table->text('message');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
        Schema::dropIfExists('app_notifications');
        Schema::dropIfExists('daily_tasks');
        Schema::dropIfExists('roadmap_steps');
        Schema::dropIfExists('roadmaps');
        Schema::dropIfExists('daily_task_templates');
        Schema::dropIfExists('roadmap_step_templates');
        Schema::dropIfExists('career_recommendations');
        Schema::dropIfExists('career_domains');
        Schema::dropIfExists('skill_user');
        Schema::dropIfExists('skills');
        Schema::dropIfExists('student_profiles');
    }
};
