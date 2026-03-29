<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\Budget;
use App\Models\Challenge;
use App\Models\FinancialGoal;
use App\Models\Badge;
use App\Models\User;
use App\Models\UserBadge;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UserDashboardController extends Controller
{
    public function overview(Request $request)
    {
        return $this->renderFeature($request, 'overview', 'Overview');
    }

    public function transactions(Request $request)
    {
        return $this->renderFeature($request, 'transactions', 'Transaksi');
    }

    public function budgets(Request $request)
    {
        return $this->renderFeature($request, 'budgets', 'Budget');
    }

    public function goals(Request $request)
    {
        return $this->renderFeature($request, 'goals', 'Goals');
    }

    public function challenges(Request $request)
    {
        return $this->renderFeature($request, 'challenges', 'Challenges');
    }

    public function badges(Request $request)
    {
        return $this->renderFeature($request, 'badges', 'Badges');
    }

    public function reports(Request $request)
    {
        return $this->renderFeature($request, 'reports', 'Laporan');
    }

    public function settings(Request $request)
    {
        return $this->renderFeature($request, 'settings', 'Pengaturan');
    }

    public function leaderboard(Request $request)
    {
        return $this->renderFeature($request, 'leaderboard', 'Leaderboard');
    }

    public function storeTransaction(Request $request)
    {
        if (! $this->hasTransactionColumns()) {
            return redirect()->route('dashboard.transactions')
                ->with('error', 'Struktur tabel transaksi belum siap. Jalankan migrasi terbaru terlebih dahulu.');
        }

        $validated = $request->validate([
            'type' => ['required', 'in:income,expense'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'description' => ['nullable', 'string', 'max:255'],
            'transaction_date' => ['required', 'date'],
            'category_id' => ['nullable', 'integer'],
        ]);

        $categoryId = $this->hasCategoryColumns() ? ($validated['category_id'] ?? null) : null;

        if ($categoryId !== null && ! Category::query()->whereKey($categoryId)->exists()) {
            return redirect()->route('dashboard.transactions')
                ->with('error', 'Kategori tidak ditemukan.');
        }

        $xpEarned = $this->calculateXp($validated['type'], (float) $validated['amount']);

        Transaction::query()->create([
            'user_id' => $request->user()->id,
            'category_id' => $categoryId,
            'type' => $validated['type'],
            'amount' => $validated['amount'],
            'description' => $validated['description'] ?? null,
            'transaction_date' => $validated['transaction_date'],
            'xp_earned' => $xpEarned,
        ]);

        $this->applyXpDelta($request->user()->id, $xpEarned);

        return redirect()->route('dashboard.transactions')
            ->with('success', 'Transaksi berhasil ditambahkan.');
    }

    public function updateTransaction(Request $request, Transaction $transaction)
    {
        if ($transaction->user_id !== $request->user()->id) {
            abort(403);
        }

        $validated = $request->validate([
            'type' => ['required', 'in:income,expense'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'description' => ['nullable', 'string', 'max:255'],
            'transaction_date' => ['required', 'date'],
            'category_id' => ['nullable', 'integer'],
        ]);

        $categoryId = $this->hasCategoryColumns() ? ($validated['category_id'] ?? null) : null;

        if ($categoryId !== null && ! Category::query()->whereKey($categoryId)->exists()) {
            return redirect()->route('dashboard.transactions')
                ->with('error', 'Kategori tidak ditemukan.');
        }

        $oldXp = (int) ($transaction->xp_earned ?? 0);
        $newXp = $this->calculateXp($validated['type'], (float) $validated['amount']);

        $transaction->update([
            'category_id' => $categoryId,
            'type' => $validated['type'],
            'amount' => $validated['amount'],
            'description' => $validated['description'] ?? null,
            'transaction_date' => $validated['transaction_date'],
            'xp_earned' => $newXp,
        ]);

        $this->applyXpDelta($request->user()->id, $newXp - $oldXp);

        return redirect()->route('dashboard.transactions')
            ->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function destroyTransaction(Request $request, Transaction $transaction)
    {
        if ($transaction->user_id !== $request->user()->id) {
            abort(403);
        }

        $xp = (int) ($transaction->xp_earned ?? 0);

        $transaction->delete();

        $this->applyXpDelta($request->user()->id, -$xp);

        return redirect()->route('dashboard.transactions')
            ->with('success', 'Transaksi berhasil dihapus.');
    }

    public function storeBudget(Request $request)
    {
        $validated = $request->validate([
            'category' => ['required', 'string', 'max:100'],
            'limit_amount' => ['required', 'numeric', 'min:0.01'],
            'period' => ['required', 'in:daily,weekly,monthly,yearly'],
            'period_start' => ['required', 'date'],
            'period_end' => ['required', 'date', 'after:period_start'],
        ]);

        Budget::query()->create([
            'user_id' => $request->user()->id,
            'category' => $validated['category'],
            'limit_amount' => $validated['limit_amount'],
            'spent_amount' => 0,
            'period' => $validated['period'],
            'period_start' => $validated['period_start'],
            'period_end' => $validated['period_end'],
            'is_active' => true,
        ]);

        return redirect()->route('dashboard.budgets')
            ->with('success', 'Budget berhasil dibuat.');
    }

    public function updateBudget(Request $request, Budget $budget)
    {
        if ($budget->user_id !== $request->user()->id) {
            abort(403);
        }

        $validated = $request->validate([
            'category' => ['required', 'string', 'max:100'],
            'limit_amount' => ['required', 'numeric', 'min:0.01'],
            'period' => ['required', 'in:daily,weekly,monthly,yearly'],
            'period_start' => ['required', 'date'],
            'period_end' => ['required', 'date', 'after:period_start'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $budget->update($validated + ['is_active' => $request->has('is_active')]);

        return redirect()->route('dashboard.budgets')
            ->with('success', 'Budget berhasil diperbarui.');
    }

    public function destroyBudget(Request $request, Budget $budget)
    {
        if ($budget->user_id !== $request->user()->id) {
            abort(403);
        }

        $budget->delete();

        return redirect()->route('dashboard.budgets')
            ->with('success', 'Budget berhasil dihapus.');
    }

    public function storeGoal(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'target_amount' => ['required', 'numeric', 'min:0.01'],
            'target_date' => ['required', 'date', 'after:today'],
            'category' => ['nullable', 'string', 'max:100'],
        ]);

        FinancialGoal::query()->create([
            'user_id' => $request->user()->id,
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'target_amount' => $validated['target_amount'],
            'current_amount' => 0,
            'target_date' => $validated['target_date'],
            'status' => 'active',
            'category' => $validated['category'] ?? null,
        ]);

        return redirect()->route('dashboard.goals')
            ->with('success', 'Goal berhasil dibuat.');
    }

    public function updateGoal(Request $request, FinancialGoal $goal)
    {
        if ($goal->user_id !== $request->user()->id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'target_amount' => ['required', 'numeric', 'min:0.01'],
            'current_amount' => ['nullable', 'numeric', 'min:0'],
            'target_date' => ['required', 'date', 'after:today'],
            'status' => ['required', 'in:active,completed,cancelled'],
            'category' => ['nullable', 'string', 'max:100'],
        ]);

        $goal->update($validated);

        return redirect()->route('dashboard.goals')
            ->with('success', 'Goal berhasil diperbarui.');
    }

    public function destroyGoal(Request $request, FinancialGoal $goal)
    {
        if ($goal->user_id !== $request->user()->id) {
            abort(403);
        }

        $goal->delete();

        return redirect()->route('dashboard.goals')
            ->with('success', 'Goal berhasil dihapus.');
    }

    public function storeChallenge(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'difficulty' => ['required', 'in:easy,medium,hard'],
            'reward_xp' => ['required', 'integer', 'min:1'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'category' => ['nullable', 'string', 'max:100'],
        ]);

        Challenge::query()->create([
            'user_id' => $request->user()->id,
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'difficulty' => $validated['difficulty'],
            'reward_xp' => $validated['reward_xp'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'status' => 'active',
            'category' => $validated['category'] ?? null,
        ]);

        return redirect()->route('dashboard.challenges')
            ->with('success', 'Challenge berhasil dibuat.');
    }

    public function updateChallenge(Request $request, Challenge $challenge)
    {
        if ($challenge->user_id !== $request->user()->id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'difficulty' => ['required', 'in:easy,medium,hard'],
            'reward_xp' => ['required', 'integer', 'min:1'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'status' => ['required', 'in:active,completed,failed'],
            'category' => ['nullable', 'string', 'max:100'],
        ]);

        $challenge->update($validated);

        return redirect()->route('dashboard.challenges')
            ->with('success', 'Challenge berhasil diperbarui.');
    }

    public function destroyChallenge(Request $request, Challenge $challenge)
    {
        if ($challenge->user_id !== $request->user()->id) {
            abort(403);
        }

        $challenge->delete();

        return redirect()->route('dashboard.challenges')
            ->with('success', 'Challenge berhasil dihapus.');
    }

    private function renderFeature(Request $request, string $feature, string $featureTitle)
    {
        $user = $request->user();

        $data = $this->buildBaseData($user->id);

        $data['user'] = $user;
        $data['activeFeature'] = $feature;
        $data['featureTitle'] = $featureTitle;
        $data['categoryOptions'] = $this->categoryOptions();
        $data['editingTransactionId'] = (int) $request->query('edit', 0);
        $data['budgets'] = Budget::query()->where('user_id', $user->id)->where('is_active', true)->get();
        $data['goals'] = FinancialGoal::query()->where('user_id', $user->id)->where('status', 'active')->orderBy('target_date')->get();
        $data['challenges'] = Challenge::query()->where('user_id', $user->id)->where('status', 'active')->orderBy('end_date')->get();
        $data['userBadges'] = UserBadge::query()->where('user_id', $user->id)->with('badge')->orderBy('earned_at', 'desc')->get();
        $data['editingBudgetId'] = (int) $request->query('edit_budget', 0);
        $data['editingGoalId'] = (int) $request->query('edit_goal', 0);
        $data['editingChallengeId'] = (int) $request->query('edit_challenge', 0);
        $data['leaderboardRows'] = $this->leaderboardRows();
        $data['leaderboardViewerId'] = $user->id;
        $data['badgeCatalog'] = $this->badgeCatalogForUser($user->id);

        return view('dashboard', $data);
    }

    private function leaderboardRows(): Collection
    {
        return User::query()
            ->where('role', 'user')
            ->get(['id', 'name', 'username'])
            ->map(function (User $user): array {
                $transactionCount = (int) Transaction::query()
                    ->where('user_id', $user->id)
                    ->count();

                $activeDays = (int) Transaction::query()
                    ->where('user_id', $user->id)
                    ->selectRaw('DATE(transaction_date) as day_key')
                    ->groupBy('day_key')
                    ->get()
                    ->count();

                $streak = $this->activityStreakDays($user->id);
                $goalProgress = $this->averageGoalProgress($user->id);
                $completedChallenges = (int) Challenge::query()
                    ->where('user_id', $user->id)
                    ->where('status', 'completed')
                    ->count();
                $xp = $this->totalXp($user->id);
                $badgesCount = (int) UserBadge::query()
                    ->where('user_id', $user->id)
                    ->count();
                $badgesPreview = UserBadge::query()
                    ->where('user_id', $user->id)
                    ->with('badge:id,name')
                    ->latest('earned_at')
                    ->take(2)
                    ->get()
                    ->map(fn (UserBadge $badge) => $badge->badge?->name)
                    ->filter()
                    ->values()
                    ->all();

                $score = (int) round(
                    ($transactionCount * 4)
                    + ($activeDays * 3)
                    + ($streak * 15)
                    + ($goalProgress * 2)
                    + ($completedChallenges * 20)
                    + ($xp * 0.3)
                    + ($badgesCount * 30)
                );

                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'transactions' => $transactionCount,
                    'active_days' => $activeDays,
                    'streak' => $streak,
                    'goal_progress' => $goalProgress,
                    'xp' => $xp,
                    'badges_count' => $badgesCount,
                    'badges_preview' => $badgesPreview,
                    'score' => $score,
                ];
            })
            ->sortByDesc('score')
            ->values();
    }

    private function badgeCatalogForUser(int $userId): Collection
    {
        $earnedBadgeIds = UserBadge::query()
            ->where('user_id', $userId)
            ->pluck('badge_id')
            ->all();

        $xp = $this->totalXp($userId);
        $level = $this->currentLevel($userId);

        return Badge::query()
            ->orderBy('required_level')
            ->orderBy('required_xp')
            ->get()
            ->map(function (Badge $badge) use ($earnedBadgeIds, $xp, $level): array {
                $xpTarget = max(0, (int) ($badge->required_xp ?? 0));
                $levelTarget = max(1, (int) ($badge->required_level ?? 1));

                $xpPercent = $xpTarget > 0 ? min(100, (int) round(($xp / $xpTarget) * 100)) : 100;
                $levelPercent = $levelTarget > 0 ? min(100, (int) round(($level / $levelTarget) * 100)) : 100;
                $progressPercent = min($xpPercent, $levelPercent);

                return [
                    'id' => $badge->id,
                    'name' => $badge->name,
                    'icon' => $badge->icon,
                    'description' => $badge->description,
                    'required_xp' => $xpTarget,
                    'required_level' => $levelTarget,
                    'is_earned' => in_array($badge->id, $earnedBadgeIds, true),
                    'progress_percent' => $progressPercent,
                    'how_to_get' => sprintf(
                        'Capai minimal level %d dan total XP %s.',
                        $levelTarget,
                        number_format($xpTarget, 0, ',', '.')
                    ),
                ];
            });
    }

    private function averageGoalProgress(int $userId): int
    {
        $goals = FinancialGoal::query()
            ->where('user_id', $userId)
            ->where('status', '!=', 'cancelled')
            ->get(['target_amount', 'current_amount']);

        if ($goals->isEmpty()) {
            return 0;
        }

        $average = $goals->avg(function (FinancialGoal $goal): float {
            $target = (float) $goal->target_amount;
            if ($target <= 0) {
                return 0;
            }

            return min(100, ((float) $goal->current_amount / $target) * 100);
        });

        return (int) round((float) $average);
    }

    private function activityStreakDays(int $userId): int
    {
        $dates = Transaction::query()
            ->where('user_id', $userId)
            ->orderByDesc('transaction_date')
            ->get(['transaction_date'])
            ->map(fn (Transaction $transaction) => Carbon::parse($transaction->transaction_date)->toDateString())
            ->unique()
            ->values();

        if ($dates->isEmpty()) {
            return 0;
        }

        $dateSet = array_flip($dates->all());
        $cursor = Carbon::parse($dates->first());
        $streak = 0;

        while (isset($dateSet[$cursor->toDateString()])) {
            $streak++;
            $cursor->subDay();
        }

        return $streak;
    }

    private function buildBaseData(int $userId): array
    {
        [$totalIncome, $totalExpense, $transactionCount, $recentTransactions] = $this->transactionSummary($userId);

        return [
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'balance' => $totalIncome - $totalExpense,
            'transactionCount' => $transactionCount,
            'currentLevel' => $this->currentLevel($userId),
            'totalXp' => $this->totalXp($userId),
            'recentTransactions' => $recentTransactions,
        ];
    }

    private function transactionSummary(int $userId): array
    {
        if (! $this->hasTransactionColumns()) {
            return [0.0, 0.0, 0, collect()];
        }

        $query = Transaction::query()->where('user_id', $userId);

        $totalIncome = (float) (clone $query)->where('type', 'income')->sum('amount');
        $totalExpense = (float) (clone $query)->where('type', 'expense')->sum('amount');
        $transactionCount = (int) (clone $query)->count();

        $recentTransactions = (clone $query)
            ->latest('transaction_date')
            ->take(20)
            ->get();

        return [$totalIncome, $totalExpense, $transactionCount, $recentTransactions];
    }

    private function hasTransactionColumns(): bool
    {
        return Schema::hasTable('transactions')
            && Schema::hasColumns('transactions', ['user_id', 'type', 'amount', 'transaction_date', 'xp_earned']);
    }

    private function hasCategoryColumns(): bool
    {
        return Schema::hasTable('categories')
            && Schema::hasColumns('categories', ['name', 'type']);
    }

    private function categoryOptions(): Collection
    {
        if (! $this->hasCategoryColumns()) {
            return collect();
        }

        return Category::query()
            ->orderBy('type')
            ->orderBy('name')
            ->get(['id', 'name', 'type']);
    }

    private function calculateXp(string $type, float $amount): int
    {
        if ($type === 'expense') {
            return 1;
        }

        return max(1, (int) floor($amount / 10000));
    }

    private function applyXpDelta(int $userId, int $delta): void
    {
        if (! Schema::hasTable('gamification_profiles')
            || ! Schema::hasColumns('gamification_profiles', ['user_id', 'current_level', 'total_xp'])) {
            return;
        }

        $profile = DB::table('gamification_profiles')->where('user_id', $userId)->first();

        if (! $profile) {
            $startXp = max($delta, 0);
            DB::table('gamification_profiles')->insert([
                'user_id' => $userId,
                'current_level' => $this->calculateLevel($startXp),
                'total_xp' => $startXp,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return;
        }

        $totalXp = max(0, (int) ($profile->total_xp ?? 0) + $delta);

        DB::table('gamification_profiles')
            ->where('user_id', $userId)
            ->update([
                'total_xp' => $totalXp,
                'current_level' => $this->calculateLevel($totalXp),
                'updated_at' => now(),
            ]);
    }

    private function calculateLevel(int $totalXp): int
    {
        return intdiv($totalXp, 100) + 1;
    }

    private function currentLevel(int $userId): int
    {
        if (! Schema::hasTable('gamification_profiles') || ! Schema::hasColumn('gamification_profiles', 'user_id')) {
            return 1;
        }

        $profile = DB::table('gamification_profiles')->where('user_id', $userId)->first();

        if (! $profile || ! isset($profile->current_level)) {
            return 1;
        }

        return (int) $profile->current_level;
    }

    private function totalXp(int $userId): int
    {
        if (! Schema::hasTable('gamification_profiles') || ! Schema::hasColumn('gamification_profiles', 'user_id')) {
            return 0;
        }

        $profile = DB::table('gamification_profiles')->where('user_id', $userId)->first();

        if (! $profile || ! isset($profile->total_xp)) {
            return 0;
        }

        return (int) $profile->total_xp;
    }
}
