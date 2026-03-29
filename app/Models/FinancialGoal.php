<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialGoal extends Model
{
    use HasFactory;

    protected $table = 'financial_goals';

    protected $fillable = [
        'user_id', 'name', 'description', 'target_amount', 'current_amount',
        'target_date', 'status', 'category',
    ];

    protected $casts = [
        'target_amount' => 'decimal:2',
        'current_amount' => 'decimal:2',
        'target_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function progressPercentage(): float
    {
        if ($this->target_amount <= 0) {
            return 0;
        }

        return min(100, ($this->current_amount / $this->target_amount) * 100);
    }

    public function remainingAmount(): float
    {
        return max(0, $this->target_amount - $this->current_amount);
    }

    public function daysRemaining(): int
    {
        return max(0, $this->target_date->diffInDays(today(), false));
    }
}
