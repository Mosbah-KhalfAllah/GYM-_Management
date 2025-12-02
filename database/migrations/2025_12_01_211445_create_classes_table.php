// database/migrations/2024_01_01_000008_create_classes_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->foreignId('coach_id')->constrained('users')->onDelete('cascade');
            $table->dateTime('schedule_time');
            $table->integer('duration_minutes');
            $table->integer('capacity');
            $table->integer('registered_count')->default(0);
            $table->enum('status', ['scheduled', 'ongoing', 'completed', 'cancelled']);
            $table->string('location')->nullable();
            $table->decimal('price', 8, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};