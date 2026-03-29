<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'category', 'limit_amount', 'spent_amount',
        'period', 'period_start', 'period_end', 'is_active'
    ];

    protected $casts = [
        'limit_amount' => 'decimal:2',
        'spent_amount' => 'decimal:2',
        'period_start' => 'date',
        'period_end' => 'date',
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function percentageUsed(): float
    {
        if ($this->limit_amount <= 0) {
            return 0;
        }

        return min(100, ($this->spent_amount / $this->limit_amount) * 100);
    }

    public function remainingAmount(): float
    {
        return max(0, $this->limit_amount - $this->spent_amount);
    }
}
