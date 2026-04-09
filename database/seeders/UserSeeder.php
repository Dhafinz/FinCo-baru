<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::query()->firstOrCreate([
            'email' => 'test@example.com',
        ], [
            'name' => 'Test User',
            'username' => 'testuser',
            'full_name' => 'Test User',
            'phone' => '081234567890',
            'date_of_birth' => '2000-01-01',
            'role' => 'user',
            'password' => Hash::make('password'),
        ]);

        Wallet::query()->firstOrCreate([
            'user_id' => $user->id,
        ], [
            'balance' => 0,
            'currency' => 'IDR',
            'is_active' => true,
        ]);

        DB::table('gamification_profiles')->updateOrInsert(
            ['user_id' => $user->id],
            [
                'current_level' => 1,
                'total_xp' => 0,
                'current_streak' => 0,
                'last_login_date' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
