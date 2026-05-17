<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Quest extends Model
{
    use HasFactory;

    protected $table = 'challenges';

    protected $fillable = [
        'user_id', 'name', 'description', 'difficulty', 'reward_xp',
        'start_date', 'end_date', 'status', 'category', 'criteria',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'criteria' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function daysRemaining(): int
    {
        return max(0, Carbon::today()->diffInDays($this->end_date, false));
    }

    public function isExpired(): bool
    {
        return Carbon::parse($this->end_date)->isPast() && $this->status !== 'completed';
    }
}