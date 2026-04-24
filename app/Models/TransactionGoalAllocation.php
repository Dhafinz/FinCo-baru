<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionGoalAllocation extends Model
{
    use HasFactory;

    protected $table = 'transaction_goal_allocations';

    protected $fillable = ['transaction_id', 'goal_id', 'allocated_amount'];

    protected $casts = [
        'allocated_amount' => 'decimal:2',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function goal()
    {
        return $this->belongsTo(FinancialGoal::class);
    }
}
