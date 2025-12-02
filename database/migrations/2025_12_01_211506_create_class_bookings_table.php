// database/migrations/2024_01_01_000009_create_class_bookings_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained()->onDelete('cascade');
            $table->foreignId('member_id')->constrained('users')->onDelete('cascade');
            $table->string('booking_code')->unique();
            $table->string('qr_code_path')->nullable();
            $table->boolean('attended')->default(false);
            $table->timestamp('check_in_time')->nullable();
            $table->enum('status', ['confirmed', 'cancelled', 'no_show'])->default('confirmed');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_bookings');
    }
};