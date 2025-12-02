// database/migrations/2024_01_01_000007_create_exercise_logs_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exercise_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_program_id')->constrained()->onDelete('cascade');
            $table->foreignId('exercise_id')->constrained()->onDelete('cascade');
            $table->date('workout_date');
            $table->boolean('completed')->default(false);
            $table->integer('actual_sets')->nullable();
            $table->integer('actual_reps')->nullable();
            $table->decimal('actual_weight', 8, 2)->nullable();
            $table->text('notes')->nullable();
            $table->integer('rating')->nullable()->comment('1-5 scale');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exercise_logs');
    }
};