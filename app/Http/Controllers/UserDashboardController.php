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
use App\Http\Requests\StoreTransactionRequest;
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

    public function quests(Request $request)
    {
        return $this->renderFeature($request, 'quests', 'Quest Harian');
    }

    public function joinQuest(Request $request)
    {
        $validated = $request->validate([
            'quest_key' => ['required', 'string'],
        ]);

        $templates = collect($this->questTemplates());
        $template = $templates->firstWhere('key', $validated['quest_key']);

        if (! $template) {
            return redirect()->route('dashboard.quests')->with('error', 'Quest tidak ditemukan.');
        }

        $userId = $request->user()->id;

        $alreadyActive = Challenge::query()
            ->where('user_id', $userId)
            ->where('name', $template['name'])
            ->where('status', 'active')
            ->exists();

        if ($alreadyActive) {
            return redirect()->route('dashboard.quests')->with('error', 'Quest ini sudah aktif.');
        }

        Challenge::query()->create([
            'user_id' => $userId,
            'name' => $template['name'],
            'description' => $template['description'],
            'difficulty' => $template['difficulty'],
            'reward_xp' => $template['reward_xp'],
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays($template['duration_days'])->toDateString(),
            'status' => 'active',
            'category' => 'quest',
            'criteria' => json_encode($template['criteria']),
        ]);

        return redirect()->route('dashboard.quests')->with('success', 'Quest berhasil di-join.');
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

    public function storeTransaction(StoreTransactionRequest $request)
    {
        if (! $this->hasTransactionColumns()) {
            return redirect()->route('dashboard.transactions')
                ->with('error', 'Struktur tabel transaksi belum siap. Jalankan migrasi terbaru terlebih dahulu.');
        }

        $validated = $request->validated();
        $userId = $request->user()->id;

        // Handle Expense Mode (Budget-based)
        if ($validated['type'] === 'expense' && isset($validated['budget_id'])) {
            $budget = Budget::query()
                ->where('id', $validated['budget_id'])
                ->where('user_id', $userId)
                ->where('is_active', true)
                ->firstOrFail();

            // Get category ID: prefer category_id relationship, fallback to slug mapping
            $categoryId = null;
            
            if ($budget->category_id) {
                $categoryId = $budget->category_id;
            } else {
                // Fallback mapping untuk budget lama tanpa category_id
                $categoryMap = [
                    'food' => 'Makanan & Minuman',
                    'transport' => 'Transportasi',
                    'utilities' => 'Tagihan',
                    'entertainment' => 'Hiburan',
                    'health' => 'Kesehatan',
                    'education' => null,
                    'shopping' => 'Belanja',
                    'other' => null,
                ];
                
                $categoryName = $categoryMap[$budget->category] ?? null;
                if ($categoryName) {
                    $categoryId = Category::query()
                        ->where('name', $categoryName)
                        ->where('type', 'expense')
                        ->value('id');
                }
            }

            if (!$categoryId) {
                return redirect()->route('dashboard.transactions')
                    ->with('error', 'Kategori budget tidak ditemukan.');
            }
            $xpEarned = $this->calculateXp($validated['type'], (float) $validated['amount']);

            $transaction = Transaction::query()->create([
                'user_id' => $userId,
                'category_id' => $categoryId,
                'budget_id' => $budget->id,
                'type' => $validated['type'],
                'amount' => $validated['amount'],
                'description' => $validated['description'] ?? null,
                'transaction_date' => $validated['transaction_date'],
                'xp_earned' => $xpEarned,
            ]);

            $this->applyXpDelta($userId, $xpEarned);

            // Auto-sync budget
            $alertMsg = null;
            if ($categoryId) {
                $alertMsg = $this->syncBudgetWithTransaction($userId, $categoryId, $validated['transaction_date']);
            }

            $message = 'Pengeluaran berhasil dicatat.';
            if ($alertMsg) {
                $message .= ' ' . $alertMsg;
            }

            $questResult = $this->syncQuestProgressFromTransaction($userId, (string) $validated['transaction_date']);
            if ($questResult['completed_count'] > 0) {
                $message .= ' 🎯 ' . $questResult['completed_count'] . ' quest selesai! +' . $questResult['xp_rewarded'] . ' XP';
            }

            return redirect()->route('dashboard.transactions', ['mode' => 'expense'])
                ->with('success', $message);
        }

        // Handle Income Mode (Goal allocation)
        if ($validated['type'] === 'income') {
            $categoryId = $this->hasCategoryColumns() ? ($validated['category_id'] ?? null) : null;
            $goalId = (int) ($validated['goal_id'] ?? 0);

            if ($categoryId !== null && ! Category::query()->whereKey($categoryId)->exists()) {
                return redirect()->route('dashboard.transactions')
                    ->with('error', 'Kategori tidak ditemukan.');
            }

            $goal = FinancialGoal::query()
                ->where('id', $goalId)
                ->where('user_id', $userId)
                ->where('status', 'active')
                ->first();

            if (! $goal) {
                return redirect()->route('dashboard.transactions', ['mode' => 'income'])
                    ->with('error', 'Pilih goal terlebih dahulu.');
            }

            $xpEarned = $this->calculateXp($validated['type'], (float) $validated['amount']);

            $transaction = Transaction::query()->create([
                'user_id' => $userId,
                'category_id' => $categoryId,
                'type' => $validated['type'],
                'amount' => $validated['amount'],
                'description' => $validated['description'] ?? null,
                'transaction_date' => $validated['transaction_date'],
                'xp_earned' => $xpEarned,
            ]);

            $this->applyXpDelta($userId, $xpEarned);

            $allocationXp = 0;
            $completedGoals = [];
            $allocatedAmount = (float) $validated['amount'];

            // Create allocation record using the full income amount
            \App\Models\TransactionGoalAllocation::query()->create([
                'transaction_id' => $transaction->id,
                'goal_id' => $goal->id,
                'allocated_amount' => $allocatedAmount,
            ]);

            // Update goal progress
            $newAmount = (float) $goal->current_amount + $allocatedAmount;
            $goal->update(['current_amount' => (int) $newAmount]);

            // Award XP for goal contribution
            $allocationXp += intval($allocatedAmount / 10000); // 1 XP per 10k

            // Check if goal is completed
            if ($newAmount >= (float) $goal->target_amount && $goal->status === 'active') {
                $goal->update(['status' => 'completed']);
                $completedGoals[] = $goal;
                $allocationXp += 100; // Bonus 100 XP for completing goal
            }

            // Apply allocation XP
            if ($allocationXp > 0) {
                $this->applyXpDelta($userId, $allocationXp);
            }

            // Award badges for completed goals
            foreach ($completedGoals as $goal) {
                $this->awardBadge($userId, 'Goal Master');
            }

            $message = 'Pemasukan berhasil dicatat.';
            if (count($completedGoals) > 0) {
                $message .= ' 🎉 ' . count($completedGoals) . ' goal selesai!';
            }

            $questResult = $this->syncQuestProgressFromTransaction($userId, (string) $validated['transaction_date']);
            if ($questResult['completed_count'] > 0) {
                $message .= ' 🎯 ' . $questResult['completed_count'] . ' quest selesai! +' . $questResult['xp_rewarded'] . ' XP';
            }

            return redirect()->route('dashboard.transactions', ['mode' => 'income'])
                ->with('success', $message);
        }

        // Default mode: General
        $categoryId = $this->hasCategoryColumns() ? ($validated['category_id'] ?? null) : null;

        if ($categoryId !== null && ! Category::query()->whereKey($categoryId)->exists()) {
            return redirect()->route('dashboard.transactions')
                ->with('error', 'Kategori tidak ditemukan.');
        }

        $xpEarned = $this->calculateXp($validated['type'], (float) $validated['amount']);

        $transaction = Transaction::query()->create([
            'user_id' => $userId,
            'category_id' => $categoryId,
            'type' => $validated['type'],
            'amount' => $validated['amount'],
            'description' => $validated['description'] ?? null,
            'transaction_date' => $validated['transaction_date'],
            'xp_earned' => $xpEarned,
        ]);

        $this->applyXpDelta($userId, $xpEarned);

        // Auto-sync dengan budget jika expense dan ada kategori
        $alertMsg = null;
        if ($validated['type'] === 'expense' && $categoryId) {
            $alertMsg = $this->syncBudgetWithTransaction($userId, $categoryId, $validated['transaction_date']);
        }

        $message = 'Transaksi berhasil ditambahkan.';
        if ($alertMsg) {
            $message .= ' ' . $alertMsg;
        }

        $questResult = $this->syncQuestProgressFromTransaction($userId, (string) $validated['transaction_date']);
        if ($questResult['completed_count'] > 0) {
            $message .= ' 🎯 ' . $questResult['completed_count'] . ' quest selesai! +' . $questResult['xp_rewarded'] . ' XP';
        }

        return redirect()->route('dashboard.transactions')
            ->with('success', $message);
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

        // Auto-sync budget: jika ada category lama & baru, sync keduanya
        $alertMsg = null;
        $oldCategoryId = $transaction->getOriginal('category_id');
        if ($oldCategoryId && $transaction->type === 'expense') {
            $syncAlert = $this->syncBudgetWithTransaction($request->user()->id, $oldCategoryId, $transaction->getOriginal('transaction_date'));
            if ($syncAlert && !$alertMsg) $alertMsg = $syncAlert;
        }
        if ($categoryId && $validated['type'] === 'expense') {
            $syncAlert = $this->syncBudgetWithTransaction($request->user()->id, $categoryId, $validated['transaction_date']);
            if ($syncAlert && !$alertMsg) $alertMsg = $syncAlert;
        }

        $message = 'Transaksi berhasil diperbarui.';
        if ($alertMsg) {
            $message .= ' ' . $alertMsg;
        }

        return redirect()->route('dashboard.transactions')
            ->with('success', $message);
    }

    public function destroyTransaction(Request $request, Transaction $transaction)
    {
        if ($transaction->user_id !== $request->user()->id) {
            abort(403);
        }

        $xp = (int) ($transaction->xp_earned ?? 0);
        $categoryId = $transaction->category_id;
        $transactionDate = $transaction->transaction_date;
        $transactionType = $transaction->type;

        $transaction->delete();

        $this->applyXpDelta($request->user()->id, -$xp);

        // Auto-sync budget saat transaksi dihapus (jika berasal dari period aktif)
        if ($categoryId && $transactionType === 'expense') {
            $this->syncBudgetWithTransaction($request->user()->id, $categoryId, $transactionDate);
        }

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

        $categoryNameMap = [
            'food' => 'Makanan & Minuman',
            'transport' => 'Transportasi',
            'utilities' => 'Tagihan',
            'entertainment' => 'Hiburan',
            'health' => 'Kesehatan',
            'shopping' => 'Belanja',
        ];

        $categoryName = $categoryNameMap[$validated['category']] ?? $validated['category'];

        $category = Category::query()
            ->where('name', $categoryName)
            ->where('type', 'expense')
            ->first();

        $categoryId = $category?->id;

        Budget::query()->create([
            'user_id' => $request->user()->id,
            'category' => $validated['category'],
            'category_id' => $categoryId,
            'limit_amount' => $validated['limit_amount'],
            'spent_amount' => 0.00,
            'period' => $validated['period'],
            'period_start' => $validated['period_start'],
            'period_end' => $validated['period_end'],
            'status' => 'on_track',
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

        $categoryNameMap = [
            'food' => 'Makanan & Minuman',
            'transport' => 'Transportasi',
            'utilities' => 'Tagihan',
            'entertainment' => 'Hiburan',
            'health' => 'Kesehatan',
            'shopping' => 'Belanja',
        ];

        $categoryName = $categoryNameMap[$validated['category']] ?? $validated['category'];
        $categoryId = Category::query()
            ->where('name', $categoryName)
            ->where('type', 'expense')
            ->value('id');

        $budget->update($validated + [
            'category_id' => $categoryId,
            'is_active' => $request->has('is_active'),
        ]);

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
        $data['actionMode'] = $feature === 'transactions' ? $request->query('mode', 'general') : null;
        $data['editingTransactionId'] = (int) $request->query('edit', 0);
        $data['budgets'] = Budget::query()->where('user_id', $user->id)->where('is_active', true)->get();
        $data['goals'] = FinancialGoal::query()->where('user_id', $user->id)->where('status', 'active')->orderBy('target_date')->get();
        $data['challenges'] = Challenge::query()->where('user_id', $user->id)->where('status', 'active')->orderBy('end_date')->get();
        $data['allChallenges'] = Challenge::query()->where('user_id', $user->id)->orderByDesc('created_at')->get();
        $data['userBadges'] = UserBadge::query()->where('user_id', $user->id)->with('badge')->orderBy('earned_at', 'desc')->get();
        $data['editingBudgetId'] = (int) $request->query('edit_budget', 0);
        $data['editingGoalId'] = (int) $request->query('edit_goal', 0);
        $data['editingChallengeId'] = (int) $request->query('edit_challenge', 0);
        $data['leaderboardRows'] = $this->leaderboardRows();
        $data['leaderboardViewerId'] = $user->id;
        $data['badgeCatalog'] = $this->badgeCatalogForUser($user->id);
        $data['questActiveCards'] = $this->activeQuestCards($user->id);
        $data['questAvailableTemplates'] = $this->availableQuestTemplates($user->id);

        // Check & award budget badges saat budget page di-render
        if ($feature === 'budgets') {
            $this->checkAndAwardBudgetBadges($user->id);
        }
        // Check & award XP/level based badges when visiting badges page
        if ($feature === 'badges') {
            $this->checkAndAwardXpBadges($user->id);
            // refresh badge data after awarding
            $data['userBadges'] = UserBadge::query()->where('user_id', $user->id)->with('badge')->orderBy('earned_at', 'desc')->get();
            $data['badgeCatalog'] = $this->badgeCatalogForUser($user->id);
        }

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

    /**
     * Sync budget spent_amount dengan transaksi terbaru
     * Return alert message jika budget exceeded
     */
    private function syncBudgetWithTransaction(int $userId, int $categoryId, $transactionDate = null): ?string
    {
        if (! Schema::hasTable('budgets')) {
            return null;
        }

        $category = Category::query()->find($categoryId);
        if (! $category) {
            return null;
        }

        $transactionDate = $transactionDate ? \Illuminate\Support\Carbon::parse($transactionDate) : now();
        $alertMsg = null;

        // Find all active budgets untuk user ini dengan kategori yg sama
        // DAN periode yang mengandung transaction date tersebut
        $budgets = Budget::query()
            ->where('user_id', $userId)
            ->where('category_id', $category->id)
            ->where('is_active', true)
            ->where('period_start', '<=', $transactionDate)
            ->where('period_end', '>=', $transactionDate)
            ->get();

        foreach ($budgets as $budget) {
            /** @var Budget $budget */
            // Recalculate spent amount
            $oldStatus = $budget->status;
            $budget->recalculateSpent();

            // Check if status changed to exceeded
            if ($oldStatus !== 'exceeded' && $budget->status === 'exceeded') {
                $alertMsg = "⚠️ Budget '{$budget->category}' telah terlampaui! Spent: Rp " . 
                    number_format((float) $budget->spent_amount, 0, ',', '.') . 
                    ' / Rp ' . number_format((float) $budget->limit_amount, 0, ',', '.');
                
                // Award -20 XP penalty saat budget exceeded
                $this->applyXpDelta($userId, -20);
            } elseif ($oldStatus !== 'warning' && $budget->status === 'warning') {
                // Optional: warning saat mencapai 80%
                if(!$alertMsg) {
                    $alertMsg = "🟡 Budget '{$budget->category}' sudah mencapai 80%.";
                }
            }
        }

        return $alertMsg;
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

    /**
     * Check & award badge untuk completed budgets
     * Dijalankan saat budget page di-render
     */
    private function checkAndAwardBudgetBadges(int $userId): void
    {
        if (! Schema::hasTable('budgets')) {
            return;
        }

        // Find budgets yang sudah ended di period ini
        $completedBudgets = Budget::query()
            ->where('user_id', $userId)
            ->where('period_end', '<', now())
            ->where('status', '!=', 'exceeded')
            ->where('is_active', true)
            ->get();

        // Award 100 XP per successful budget + "Budget Master" badge
        foreach ($completedBudgets as $budget) {
            // Award XP
            $this->applyXpDelta($userId, 100);

            // Award badge jika belum
            $this->awardBadge($userId, 'Budget Master');

            // Mark as handled by setting is_active to false
            DB::table('budgets')
                ->where('id', $budget->id)
                ->update(['is_active' => false]);
        }
    }

    /**
     * Award badge ke user
     */
    private function awardBadge(int $userId, string $badgeName): void
    {
        if (! Schema::hasTable('badges') || ! Schema::hasTable('user_badges')) {
            return;
        }

        $badge = Badge::where('name', $badgeName)->first();
        if (! $badge) {
            return;
        }

        // Check if already earned
        $alreadyEarned = UserBadge::where('user_id', $userId)
            ->where('badge_id', $badge->id)
            ->exists();

        if (! $alreadyEarned) {
            UserBadge::create([
                'user_id' => $userId,
                'badge_id' => $badge->id,
                'earned_at' => now(),
            ]);
        }
    }

    private function questTemplates(): array
    {
        return [
            [
                'key' => 'tx_10',
                'name' => 'Catat 10 Transaksi',
                'description' => 'Catat 10 transaksi selama periode quest.',
                'difficulty' => 'easy',
                'reward_xp' => 50,
                'duration_days' => 7,
                'criteria' => ['type' => 'transaction_count', 'target' => 10, 'unit' => 'transaksi'],
            ],
            [
                'key' => 'save_500k',
                'name' => 'Hemat Rp 500.000',
                'description' => 'Capai selisih income - expense sebesar Rp 500.000 selama periode quest.',
                'difficulty' => 'medium',
                'reward_xp' => 100,
                'duration_days' => 14,
                'criteria' => ['type' => 'savings_target', 'target' => 500000, 'unit' => 'rupiah'],
            ],
            [
                'key' => 'no_spend_30',
                'name' => '30 Days No Spending',
                'description' => 'Lewati 30 hari dengan disiplin pengeluaran minimum.',
                'difficulty' => 'hard',
                'reward_xp' => 200,
                'duration_days' => 30,
                'criteria' => ['type' => 'no_spend_days', 'target' => 30, 'unit' => 'hari'],
            ],
        ];
    }

    private function availableQuestTemplates(int $userId): Collection
    {
        $activeNames = Challenge::query()
            ->where('user_id', $userId)
            ->where('status', 'active')
            ->pluck('name')
            ->all();

        return collect($this->questTemplates())
            ->reject(fn (array $template) => in_array($template['name'], $activeNames, true))
            ->values();
    }

    private function activeQuestCards(int $userId): Collection
    {
        $active = Challenge::query()
            ->where('user_id', $userId)
            ->where('status', 'active')
            ->orderBy('end_date')
            ->get();

        return $active->map(function (Challenge $challenge) use ($userId): array {
            $progress = $this->calculateQuestProgress($challenge, $userId);

            return [
                'id' => $challenge->id,
                'name' => $challenge->name,
                'description' => $challenge->description,
                'difficulty' => $challenge->difficulty,
                'reward_xp' => (int) $challenge->reward_xp,
                'end_date' => $challenge->end_date,
                'days_remaining' => $challenge->daysRemaining(),
                'status' => $challenge->status,
                'current' => $progress['current'],
                'target' => $progress['target'],
                'percentage' => $progress['percentage'],
                'label' => $progress['label'],
                'bar_color' => $progress['bar_color'],
                'is_completed' => $progress['is_completed'],
            ];
        });
    }

    /**
     * Auto-award badges based on current XP/level if criteria met.
     * Called when rendering badges feature so users with 100% progress get badges.
     */
    private function checkAndAwardXpBadges(int $userId): void
    {
        if (! Schema::hasTable('badges') || ! Schema::hasTable('user_badges')) {
            return;
        }

        $xp = $this->totalXp($userId);
        $level = $this->currentLevel($userId);

        $eligible = Badge::query()
            ->where(function ($q) use ($xp, $level) {
                $q->where('required_xp', '<=', $xp)
                  ->where('required_level', '<=', $level);
            })
            ->get();

        foreach ($eligible as $badge) {
            $already = UserBadge::query()
                ->where('user_id', $userId)
                ->where('badge_id', $badge->id)
                ->exists();

            if (! $already) {
                $this->awardBadge($userId, $badge->name);
            }
        }
    }

    private function calculateQuestProgress(Challenge $challenge, int $userId): array
    {
        $criteria = json_decode((string) ($challenge->criteria ?? ''), true);
        if (! is_array($criteria)) {
            $criteria = [];
        }

        $type = (string) ($criteria['type'] ?? 'transaction_count');
        $target = max(1.0, (float) ($criteria['target'] ?? 1));
        $startDate = Carbon::parse($challenge->start_date)->toDateString();
        $endDate = Carbon::parse($challenge->end_date)->toDateString();
        $current = 0.0;
        $label = '0 / ' . (int) $target;

        if ($type === 'savings_target') {
            $income = (float) Transaction::query()
                ->where('user_id', $userId)
                ->where('type', 'income')
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->sum('amount');

            $expense = (float) Transaction::query()
                ->where('user_id', $userId)
                ->where('type', 'expense')
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->sum('amount');

            $current = max(0.0, $income - $expense);
            $label = 'Rp ' . number_format($current, 0, ',', '.') . ' / Rp ' . number_format($target, 0, ',', '.');
        } elseif ($type === 'no_spend_days') {
            $totalDays = max(1, Carbon::parse($startDate)->diffInDays(min(Carbon::today(), Carbon::parse($endDate))) + 1);
            $expenseDays = (int) Transaction::query()
                ->where('user_id', $userId)
                ->where('type', 'expense')
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->selectRaw('DATE(transaction_date) as day_key')
                ->groupBy('day_key')
                ->get()
                ->count();

            $current = max(0, $totalDays - $expenseDays);
            $label = (int) $current . ' / ' . (int) $target . ' hari';
        } else {
            $current = (float) Transaction::query()
                ->where('user_id', $userId)
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->count();
            $label = (int) $current . ' / ' . (int) $target . ' transaksi';
        }

        $percentage = (int) min(100, round(($current / $target) * 100));
        $isCompleted = $current >= $target;
        $barColor = $percentage >= 100 ? '#16a34a' : ($percentage >= 80 ? '#f59e0b' : '#4b5563');

        return [
            'current' => $current,
            'target' => $target,
            'percentage' => $percentage,
            'label' => $label,
            'bar_color' => $barColor,
            'is_completed' => $isCompleted,
        ];
    }

    private function syncQuestProgressFromTransaction(int $userId, string $transactionDate): array
    {
        if (! Schema::hasTable('challenges')) {
            return ['completed_count' => 0, 'xp_rewarded' => 0];
        }

        $date = Carbon::parse($transactionDate)->toDateString();
        $activeChallenges = Challenge::query()
            ->where('user_id', $userId)
            ->where('status', 'active')
            ->where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->get();

        $completedCount = 0;
        $xpRewarded = 0;

        foreach ($activeChallenges as $challenge) {
            /** @var Challenge $challenge */
            $progress = $this->calculateQuestProgress($challenge, $userId);
            if (! $progress['is_completed']) {
                continue;
            }

            $challenge->update(['status' => 'completed']);
            $completedCount++;
            $xpReward = (int) $challenge->reward_xp;
            $xpRewarded += $xpReward;
            $this->applyXpDelta($userId, $xpReward);
            $this->awardBadge($userId, 'Quest Finisher');
        }

        return ['completed_count' => $completedCount, 'xp_rewarded' => $xpRewarded];
    }
}
