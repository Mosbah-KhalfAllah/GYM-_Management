<?php
// app/Models/Exercise.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_id',
        'name',
        'description',
        'sets',
        'reps',
        'weight',
        'rest_seconds',
        'day_number',
        'video_url',
        'image_path',
    ];

    protected $casts = [
        'sets' => 'integer',
        'reps' => 'integer',
        'weight' => 'decimal:2',
        'rest_seconds' => 'integer',
        'day_number' => 'integer',
    ];

    // Relations
    public function program()
    {
        return $this->belongsTo(WorkoutProgram::class, 'program_id');
    }

    public function logs()
    {
        return $this->hasMany(ExerciseLog::class);
    }
}