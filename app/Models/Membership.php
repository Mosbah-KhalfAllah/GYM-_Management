<?php
// app/Models/Membership.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'price',
        'start_date',
        'end_date',
        'status',
        'payment_method',
        'transaction_id',
        'auto_renewal',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'price' => 'decimal:2',
        'auto_renewal' => 'boolean',
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                     ->where('end_date', '>=', now());
    }

    public function scopeExpiringSoon($query, $days = 5)
    {
        return $query->where('status', 'active')
                     ->whereBetween('end_date', [now(), now()->addDays($days)]);
    }

    // Methods
    public function isExpired()
    {
        return $this->end_date < now();
    }

    public function daysUntilExpiration()
    {
        return now()->diffInDays($this->end_date, false);
    }
}