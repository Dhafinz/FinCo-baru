<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Quest;
use App\Models\Budget;
use App\Models\Category;
use App\Models\FinancialGoal;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'users' => User::query()->count(),
            'transactions' => Transaction::query()->count(),
            'categories' => Category::query()->count(),
            'budgets' => Budget::query()->count(),
            'goals' => FinancialGoal::query()->count(),
            'quests' => Quest::query()->count(),
            'income' => Transaction::query()->income()->sum('amount'),
            'expense' => Transaction::query()->expense()->sum('amount'),
        ];

        $stats['users_trend'] = $this->calculateTrend(User::class, 'created_at');
        $stats['transactions_trend'] = $this->calculateTrend(Transaction::class, 'created_at');
        $stats['budgets_trend'] = $this->calculateTrend(Budget::class, 'created_at');

        $monthlyIncome = [];
        $monthlyExpense = [];
        $monthlyLabels = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $month = $date->format('m');
            $year = $date->format('Y');
            $monthlyLabels[] = $date->isoFormat('MMM');

            $income = Transaction::query()
                ->income()
                ->whereYear('transaction_date', $year)
                ->whereMonth('transaction_date', $month)
                ->sum('amount');

            $expense = Transaction::query()
                ->expense()
                ->whereYear('transaction_date', $year)
                ->whereMonth('transaction_date', $month)
                ->sum('amount');

            $monthlyIncome[] = (float) $income;
            $monthlyExpense[] = (float) $expense;
        }

        $recentTransactions = Transaction::query()
            ->with('user')
            ->latest()
            ->take(8)
            ->get()
            ->map(function ($t) {
                return [
                    'id' => $t->id,
                    'user_name' => $t->user?->name ?? 'Unknown',
                    'type' => $t->type,
                    'amount' => (float) $t->amount,
                    'description' => $t->description,
                    'date' => $t->transaction_date ? $t->transaction_date->isoFormat('DD MMM YYYY') : '-',
                ];
            });

        $categoryDistribution = Category::query()
            ->withCount('transactions')
            ->get()
            ->map(function ($c) {
                return [
                    'name' => $c->name,
                    'count' => $c->transactions_count,
                    'color' => $c->color ?? '#2563eb',
                ];
            });

        return view('admin.dashboard', compact(
            'stats',
            'monthlyLabels',
            'monthlyIncome',
            'monthlyExpense',
            'recentTransactions',
            'categoryDistribution'
        ));
    }

    private function calculateTrend(string $modelClass, string $dateColumn): string
    {
        $currentPeriod = $modelClass::where($dateColumn, '>=', now()->subDays(30))->count();
        $previousPeriod = $modelClass::whereBetween($dateColumn, [now()->subDays(60), now()->subDays(30)])->count();

        if ($previousPeriod === 0) {
            return $currentPeriod > 0 ? '+100%' : '0%';
        }

        $change = (($currentPeriod - $previousPeriod) / $previousPeriod) * 100;
        $prefix = $change >= 0 ? '+' : '';
        return $prefix . number_format($change, 0) . '%';
    }
}
