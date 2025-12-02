<?php
// app/Models/MemberProgram.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberProgram extends Model
{
    use HasFactory;

    protected $table = 'member_programs';

    protected $fillable = [
        'member_id',
        'program_id',
        'start_date',
        'end_date',
        'current_day',
        'completed_exercises',
        'completion_percentage',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'current_day' => 'integer',
        'completed_exercises' => 'integer',
        'completion_percentage' => 'float',
    ];

    // Relations
    public function member()
    {
        return $this->belongsTo(User::class, 'member_id');
    }

    public function program()
    {
        return $this->belongsTo(WorkoutProgram::class, 'program_id');
    }

    public function logs()
    {
        return $this->hasMany(ExerciseLog::class, 'member_program_id');
    }
}