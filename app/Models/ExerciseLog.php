<?php
// app/Models/ExerciseLog.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExerciseLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_program_id',
        'exercise_id',
        'workout_date',
        'completed',
        'actual_sets',
        'actual_reps',
        'actual_weight',
        'notes',
        'rating',
    ];

    protected $casts = [
        'workout_date' => 'date',
        'completed' => 'boolean',
        'actual_sets' => 'integer',
        'actual_reps' => 'integer',
        'actual_weight' => 'decimal:2',
        'rating' => 'integer',
    ];

    // Relations
    public function memberProgram()
    {
        return $this->belongsTo(MemberProgram::class, 'member_program_id');
    }

    public function exercise()
    {
        return $this->belongsTo(Exercise::class, 'exercise_id');
    }
}