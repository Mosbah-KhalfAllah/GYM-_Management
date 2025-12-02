// database/migrations/2024_01_01_000010_create_equipment_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category');
            $table->string('serial_number')->unique();
            $table->date('purchase_date');
            $table->date('last_maintenance_date');
            $table->date('next_maintenance_date');
            $table->enum('status', ['available', 'in_use', 'maintenance', 'broken']);
            $table->text('notes')->nullable();
            $table->string('location')->nullable();
            $table->decimal('purchase_price', 10, 2)->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipment');
    }
};