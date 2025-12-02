<?php
// app/Models/WorkoutProgram.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkoutProgram extends Model
{
    use HasFactory;

    protected $fillable = [
        'coach_id',
        'title',
        'description',
        'level',
        'duration_days',
        'goal',
        'is_active',
    ];

    protected $casts = [
        'duration_days' => 'integer',
        'is_active' => 'boolean',
    ];

    // Relations
    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }

    public function exercises()
    {
        return $this->hasMany(Exercise::class, 'program_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'member_programs', 'program_id', 'member_id')
                    ->withPivot(['start_date', 'end_date', 'current_day', 'completion_percentage', 'status'])
                    ->withTimestamps();
    }

    public function memberPrograms()
    {
        return $this->hasMany(MemberProgram::class, 'program_id');
    }
}