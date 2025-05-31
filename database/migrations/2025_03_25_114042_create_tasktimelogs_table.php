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
        Schema::create('tasktimelogs', function (Blueprint $table) {
            $table->id();
            $table->timestamp('start_time')->nullable(); // When the timer started
            $table->timestamp('pause_time')->nullable(); // When the timer was paused
            $table->timestamp('stop_time')->nullable(); // When the timer was stopped
            $table->integer('elapsed_time')->default(0); // Stores total elapsed time in seconds
            
            $table->integer('users_id');
            $table->integer('tasks_id');
            $table->integer('tasks_projects_id');
            $table->integer('tasks_projects_workspaces_id');
            
            $table->boolean('isTrash')->default(0);
            $table->enum('status', ['running', 'paused', 'stopped'])->default('stopped'); // Tracks timer state
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasktimelogs');
    }
};
