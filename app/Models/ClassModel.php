<?php
// app/Models/ClassModel.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassModel extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = [
        'name',
        'description',
        'coach_id',
        'schedule_time',
        'duration_minutes',
        'capacity',
        'registered_count',
        'status',
        'location',
        'price',
    ];

    protected $casts = [
        'schedule_time' => 'datetime',
        'duration_minutes' => 'integer',
        'capacity' => 'integer',
        'registered_count' => 'integer',
        'price' => 'decimal:2',
    ];

    // Relations
    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }

    public function bookings()
    {
        return $this->hasMany(ClassBooking::class, 'class_id');
    }
}