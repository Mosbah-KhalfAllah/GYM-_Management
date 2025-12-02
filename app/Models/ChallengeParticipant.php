<?php
// app/Models/ChallengeParticipant.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChallengeParticipant extends Model
{
    use HasFactory;

    protected $table = 'challenge_participants';

    protected $fillable = [
        'challenge_id',
        'member_id',
        'current_progress',
        'points_earned',
        'completed',
        'completed_at',
    ];

    protected $casts = [
        'current_progress' => 'integer',
        'points_earned' => 'integer',
        'completed' => 'boolean',
        'completed_at' => 'date',
    ];

    // Relations
    public function challenge()
    {
        return $this->belongsTo(Challenge::class);
    }

    public function member()
    {
        return $this->belongsTo(User::class, 'member_id');
    }
}