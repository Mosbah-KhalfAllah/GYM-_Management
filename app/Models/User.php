<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, Billable;

    protected $fillable = [
        'role',
        'first_name',
        'last_name',
        'email',
        'phone',
        'password',
        'avatar',
        'birth_date',
        'gender',
        'address',
        'emergency_contact',
        'qr_code_path',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'birth_date' => 'date',
        'is_active' => 'boolean',
    ];

    // Relations
    public function membership()
    {
        return $this->hasOne(Membership::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function programs()
    {
        return $this->belongsToMany(WorkoutProgram::class, 'member_programs', 'member_id', 'program_id')
                    ->withPivot(['start_date', 'end_date', 'current_day', 'completion_percentage', 'status'])
                    ->withTimestamps();
    }

    public function createdPrograms()
    {
        return $this->hasMany(WorkoutProgram::class, 'coach_id');
    }

    public function classesCoached()
    {
        return $this->hasMany(ClassModel::class, 'coach_id');
    }

    public function classes()
    {
        return $this->hasMany(ClassModel::class, 'coach_id');
    }

    public function bookings()
    {
        return $this->hasMany(ClassBooking::class, 'member_id');
    }

    public function equipment()
    {
        return $this->hasMany(Equipment::class, 'assigned_to');
    }

    public function challengeParticipants()
    {
        return $this->hasMany(ChallengeParticipant::class, 'member_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getMembershipStatusAttribute()
    {
        return $this->membership?->status ?? 'inactive';
    }

    public function getActiveProgramAttribute()
    {
        return $this->programs()->wherePivot('status', 'active')->first();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeMembers($query)
    {
        return $query->where('role', 'member');
    }

    public function scopeCoaches($query)
    {
        return $query->where('role', 'coach');
    }

    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }
}