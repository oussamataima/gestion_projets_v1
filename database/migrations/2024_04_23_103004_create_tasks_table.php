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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id(); // Primary key (auto-incrementing integer)
            $table->unsignedBigInteger('project_id'); // Foreign key referencing project ID
            $table->string('title');
            $table->text('description')->nullable(); // Allow null values
            $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending'); // Define task statuses
            $table->dateTime('due_date')->nullable(); // Allow null values for due dates
            $table->unsignedBigInteger('assigned_to')->nullable(); // Foreign key for assigned user (allow null)
            $table->unsignedInteger('estimated_completion_time')->nullable(); // Estimated completion time in hours
            $table->dateTime('start_time')->nullable(); // Time task was started
            $table->dateTime('end_time')->nullable(); // Time task was completed
            $table->timestamps(); // Created and updated at timestamps
    
            // New fields for system points
            $table->unsignedInteger('task_points')->nullable();  // Points assigned to the task based on difficulty
            $table->decimal('earned_points', 4, 2)->default(0.00); // Earned points based on formula calculation
    
            $table->foreign('project_id')->references('id')->on('projects'); // Define foreign key constraint
            $table->foreign('assigned_to')->references('id')->on('users')->onDelete('SET NULL'); // Define foreign key with onDelete option
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
