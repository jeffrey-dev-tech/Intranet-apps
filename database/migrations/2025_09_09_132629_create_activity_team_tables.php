<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Activities Table
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('unit');
            $table->integer('level_count');
            $table->timestamps();
        });

        // Challenge Levels Table
        Schema::create('challenge_levels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->constrained('activities')->onDelete('cascade');
            $table->string('activity_name');
            $table->integer('level_number');
            $table->float('required_value');
            $table->integer('team_size');
            $table->timestamps();
        });

        // Teams Table
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('activity_id')->constrained('activities')->onDelete('cascade');
            $table->string('activity_name');
            $table->foreignId('level_id')->constrained('challenge_levels')->onDelete('cascade');
            $table->foreignId('captain_id')->constrained('users')->onDelete('cascade');
            $table->string('invite_code')->unique();
            $table->enum('status', ['pending', 'active', 'completed'])->default('pending');
            $table->timestamps();
        });

        // Team_User Pivot Table
        Schema::create('team_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained('teams')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('role', ['captain', 'member']);
            $table->float('progress_value')->default(0);
            $table->timestamp('joined_at')->nullable();
        });

        // Submissions Table
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained('teams')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('activity_id')->constrained('activities')->onDelete('cascade');
            $table->string('activity_name');
            $table->string('file_path');
            $table->float('progress_value');
            $table->enum('status', ['pending', 'approved'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submissions');
        Schema::dropIfExists('team_user');
        Schema::dropIfExists('teams');
        Schema::dropIfExists('challenge_levels');
        Schema::dropIfExists('activities');
    }
};
