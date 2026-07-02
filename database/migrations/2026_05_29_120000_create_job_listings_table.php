<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('career_domain_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('company');
            $table->string('location')->nullable();
            $table->string('apply_url')->nullable();
            $table->text('apply_note')->nullable();
            $table->date('expires_at');
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_listings');
    }
};
