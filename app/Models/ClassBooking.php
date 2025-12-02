<?php
// app/Models/ClassBooking.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'member_id',
        'booking_code',
        'qr_code_path',
        'attended',
        'check_in_time',
        'status',
    ];

    protected $casts = [
        'attended' => 'boolean',
        'check_in_time' => 'datetime',
    ];

    // Relations
    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function member()
    {
        return $this->belongsTo(User::class, 'member_id');
    }
}