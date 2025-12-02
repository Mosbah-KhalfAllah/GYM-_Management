<?php
// app/Models/Challenge.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Challenge extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'type',
        'target_value',
        'start_date',
        'end_date',
        'points_reward',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'target_value' => 'integer',
        'points_reward' => 'integer',
        'is_active' => 'boolean',
    ];

    // Relations
    public function participants()
    {
        return $this->hasMany(ChallengeParticipant::class);
    }
}