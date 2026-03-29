<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Challenge extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'description', 'difficulty', 'reward_xp',
        'start_date', 'end_date', 'status', 'category', 'criteria',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function daysRemaining(): int
    {
        return max(0, $this->end_date->diffInDays(today(), false));
    }

    public function isExpired(): bool
    {
        return $this->end_date->isPast() && $this->status !== 'completed';
    }
}
