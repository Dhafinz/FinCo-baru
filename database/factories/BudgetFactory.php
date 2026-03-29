<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Budget>
 */
class BudgetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'category' => $this->faker->randomElement(['food', 'transport', 'utilities', 'entertainment', 'health', 'education', 'shopping', 'other']),
            'limit_amount' => $this->faker->numberBetween(500000, 5000000),
            'spent_amount' => $this->faker->numberBetween(0, 1000000),
            'period' => 'monthly',
            'period_start' => now()->startOfMonth(),
            'period_end' => now()->endOfMonth(),
            'is_active' => true,
        ];
    }
}
