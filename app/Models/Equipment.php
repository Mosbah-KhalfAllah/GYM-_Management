<?php
// app/Models/Equipment.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'serial_number',
        'purchase_date',
        'last_maintenance_date',
        'next_maintenance_date',
        'status',
        'notes',
        'location',
        'purchase_price',
        'assigned_to',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'last_maintenance_date' => 'date',
        'next_maintenance_date' => 'date',
        'purchase_price' => 'decimal:2',
    ];

    // Relations
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function maintenanceLogs()
    {
        return $this->hasMany(MaintenanceLog::class);
    }
}