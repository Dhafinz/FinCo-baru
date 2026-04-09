<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $badges = [
            [
                'name' => 'First Income',
                'description' => 'Catat pemasukan pertama kamu di FinCo',
                'icon' => '💰',
                'required_level' => 1,
                'required_xp' => 0,
            ],
            [
                'name' => 'Expense Tracker',
                'description' => 'Catat 10 pengeluaran di aplikasi',
                'icon' => '📊',
                'required_level' => 2,
                'required_xp' => 100,
            ],
            [
                'name' => 'Budget Master',
                'description' => 'Buat dan kelola 5 budget aktif',
                'icon' => '🎯',
                'required_level' => 3,
                'required_xp' => 250,
            ],
            [
                'name' => 'Goal Setter',
                'description' => 'Tetapkan 3 target finansial',
                'icon' => '🚀',
                'required_level' => 3,
                'required_xp' => 300,
            ],
            [
                'name' => 'Challenge Hunter',
                'description' => 'Selesaikan 5 challenge berhasil',
                'icon' => '🏆',
                'required_level' => 5,
                'required_xp' => 500,
            ],
            [
                'name' => 'Smart Saver',
                'description' => 'Pertahankan saldo positif selama 30 hari',
                'icon' => '💎',
                'required_level' => 5,
                'required_xp' => 750,
            ],
            [
                'name' => 'Financial Legend',
                'description' => 'Capai level 10 dan maintenance selama 60 hari',
                'icon' => '👑',
                'required_level' => 10,
                'required_xp' => 2000,
            ],
            [
                'name' => 'First Top Up',
                'description' => 'Lakukan top up pertama di wallet FinCo',
                'icon' => '✨',
                'required_level' => 1,
                'required_xp' => 50,
            ],
            [
                'name' => 'Top Up Master',
                'description' => 'Lakukan top up sebanyak 5 kali',
                'icon' => '💳',
                'required_level' => 2,
                'required_xp' => 150,
            ],
            [
                'name' => 'Social Saver',
                'description' => 'Punya 3 teman yang accepted',
                'icon' => '👥',
                'required_level' => 3,
                'required_xp' => 250,
            ],
            [
                'name' => 'Generous',
                'description' => 'Transfer ke teman sebanyak 3 kali',
                'icon' => '💸',
                'required_level' => 3,
                'required_xp' => 300,
            ],
        ];

        foreach ($badges as $badge) {
            Badge::query()->firstOrCreate($badge);
        }
    }
}
