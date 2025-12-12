// database/migrations/2024_01_01_000005_create_exercises_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exercises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained('workout_programs')->onDelete('cascade');
            $table->string('name');
            $table->text('description');
            $table->integer('sets');
            $table->integer('reps');
            $table->decimal('weight', 8, 2)->nullable();
            $table->integer('rest_seconds')->default(60);
            $table->integer('day_number');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exercises');
    }
};