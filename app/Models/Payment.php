<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'payment_id',
        'amount',
        'currency',
        'status',
        'payment_method',
        'payment_gateway',
        'description',
        'metadata',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'metadata' => 'array',
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
    }
    
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
    
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }
    
    public function scopeRefunded($query)
    {
        return $query->where('status', 'refunded');
    }
    
    // Accesseurs
    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 2) . ' ' . $this->currency;
    }
    
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'completed' => 'Complété',
            'pending' => 'En attente',
            'failed' => 'Échoué',
            'refunded' => 'Remboursé',
            default => 'Inconnu'
        };
    }
    
    public function getMethodLabelAttribute()
    {
        return match($this->payment_method) {
            'cash' => 'Espèces',
            'card' => 'Carte bancaire',
            'online' => 'Paiement en ligne',
            default => 'Autre'
        };
    }
}