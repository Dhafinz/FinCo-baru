<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopUp extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'wallet_id',
        'amount',
        'payment_method',
        'status',
        'reference_number',
        'xp_earned',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
}
