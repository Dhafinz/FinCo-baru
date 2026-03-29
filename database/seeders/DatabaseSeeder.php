<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            BadgeSeeder::class,
            SampleDataSeeder::class,
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'username' => 'testuser',
            'full_name' => 'Test User',
            'phone' => '081234567890',
            'date_of_birth' => '2000-01-01',
            'role' => 'user',
            'email' => 'test@example.com',
        ]);
    }
}
