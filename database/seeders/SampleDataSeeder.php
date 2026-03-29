<?php

namespace Database\Seeders;

use App\Models\Badge;
use App\Models\Budget;
use App\Models\Challenge;
use App\Models\FinancialGoal;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserBadge;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get test user (created by DatabaseSeeder)
        $user = User::where('username', 'testuser')->first();

        if (! $user) {
            return;
        }

        // Create sample transactions
        Transaction::query()->create([
            'user_id' => $user->id,
            'type' => 'income',
            'amount' => 5000000,
            'description' => 'Gaji bulan November',
            'category_id' => 1,
            'transaction_date' => Carbon::now()->subDays(5),
            'xp_earned' => 50,
        ]);

        Transaction::query()->create([
            'user_id' => $user->id,
            'type' => 'expense',
            'amount' => 1200000,
            'description' => 'Belanja mingguan di supermarket',
            'category_id' => 2,
            'transaction_date' => Carbon::now()->subDays(3),
            'xp_earned' => 25,
        ]);

        // Create sample budget
        Budget::query()->create([
            'user_id' => $user->id,
            'category' => 'food',
            'limit_amount' => 1500000,
            'spent_amount' => 1200000,
            'period' => 'monthly',
            'period_start' => Carbon::now()->startOfMonth(),
            'period_end' => Carbon::now()->endOfMonth(),
            'is_active' => true,
        ]);

        // Create sample goal
        FinancialGoal::query()->create([
            'user_id' => $user->id,
            'name' => 'Dana Liburan ke Bali',
            'description' => 'Terkumpul untuk liburan keluarga di Bali tahun depan',
            'target_amount' => 10000000,
            'current_amount' => 2500000,
            'target_date' => Carbon::now()->addMonth(6),
            'status' => 'active',
            'category' => 'vacation',
        ]);

        // Create sample challenge
        Challenge::query()->create([
            'user_id' => $user->id,
            'name' => 'No Spend Day Challenge',
            'description' => 'Jangan ada pengeluaran selama 7 hari berturut-turut',
            'difficulty' => 'easy',
            'reward_xp' => 100,
            'start_date' => Carbon::now()->subDays(2),
            'end_date' => Carbon::now()->addDays(5),
            'status' => 'active',
            'category' => 'spending_control',
            'criteria' => 'Tidak ada transaction dengan type expense selama 7 hari',
        ]);

        // Award sample badge
        $firstIncomeBadge = Badge::where('name', 'First Income')->first();
        if ($firstIncomeBadge) {
            UserBadge::query()->firstOrCreate([
                'user_id' => $user->id,
                'badge_id' => $firstIncomeBadge->id,
            ], [
                'earned_at' => Carbon::now()->subDays(10),
            ]);
        }
    }
}
