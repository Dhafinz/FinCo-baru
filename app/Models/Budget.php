<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class Budget extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'category', 'category_id', 'limit_amount', 'spent_amount',
        'period', 'period_start', 'period_end', 'is_active', 'status'
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

    /**
     * Recalculate spent_amount dari transaksi dalam period
     */
    public function recalculateSpent(): float
    {
        $categoryId = $this->category_id ?: $this->resolveCategoryId();

        if ($categoryId && ! $this->category_id) {
            $this->category_id = $categoryId;
        }

        if (!$categoryId) {
            DB::table('budgets')
                ->where('id', $this->id)
                ->update(['spent_amount' => 0]);
            return 0;
        }

        $spent = DB::table('transactions')
            ->where('user_id', $this->user_id)
            ->where('category_id', $categoryId)
            ->where('type', 'expense')
            ->whereBetween('transaction_date', [$this->period_start, $this->period_end])
            ->sum('amount');

        $this->spent_amount = $spent;
        $this->updateStatus();
        $this->save();

        return (float) $spent;
    }

    public function resolveCategoryId(): ?int
    {
        $categoryNameMap = [
            'food' => 'Makanan & Minuman',
            'transport' => 'Transportasi',
            'utilities' => 'Tagihan',
            'entertainment' => 'Hiburan',
            'health' => 'Kesehatan',
            'shopping' => 'Belanja',
        ];

        $budgetCategory = strtolower(trim((string) $this->category));
        $categoryName = $categoryNameMap[$budgetCategory] ?? $this->category;

        return DB::table('categories')
            ->where('name', $categoryName)
            ->where('type', 'expense')
            ->value('id');
    }

    /**
     * Update status berdasarkan spent_amount vs limit
     */
    public function updateStatus(): self
    {
        $percentage = $this->percentageUsed();

        if ($percentage > 100) {
            $this->status = 'exceeded';
        } elseif ($percentage >= 80) {
            $this->status = 'warning';
        } else {
            $this->status = 'on_track';
        }

        return $this;
    }

    /**
     * Check apakah budget sudah expired
     */
    public function isExpired(): bool
    {
        return Carbon::parse($this->period_end)->isPast();
    }

    /**
     * Check apakah budget berhasil (tidak exceeded di akhir periode)
     */
    public function isSuccessful(): bool
    {
        return $this->isExpired() && $this->spent_amount <= $this->limit_amount;
    }

    /**
     * Get status label untuk display
     */
    public function getStatusLabel(): string
    {
        return match ($this->status) {
            'on_track' => '🟢 On Track',
            'warning' => '🟡 Warning',
            'exceeded' => '🔴 Exceeded',
            default => 'Unknown',
        };
    }

    /**
     * Get status color untuk styling
     */
    public function getStatusColor(): string
    {
        return match ($this->status) {
            'on_track' => '#16a34a',
            'warning' => '#ca8a04',
            'exceeded' => '#dc2626',
            default => '#6b7280',
        };
    }
}
