// database/migrations/2024_01_01_000012_create_challenges_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('challenges', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->enum('type', ['attendance', 'workout', 'class', 'weight_loss', 'muscle_gain']);
            $table->integer('target_value');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('points_reward');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('challenges');
    }
};