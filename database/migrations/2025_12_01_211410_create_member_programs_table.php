// database/migrations/2024_01_01_000006_create_member_programs_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('member_programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('program_id')->constrained('workout_programs')->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('current_day')->default(1);
            $table->integer('completed_exercises')->default(0);
            $table->float('completion_percentage')->default(0);
            $table->enum('status', ['active', 'completed', 'paused'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_programs');
    }
};