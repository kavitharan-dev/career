<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_task_subtask_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('daily_task_template_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('daily_task_subtasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('daily_task_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_task_subtasks');
        Schema::dropIfExists('daily_task_subtask_templates');
    }
};
