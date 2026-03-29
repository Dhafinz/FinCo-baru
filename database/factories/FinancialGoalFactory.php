<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FinancialGoal>
 */
class FinancialGoalFactory extends Factory
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
            'name' => $this->faker->catchPhrase(),
            'description' => $this->faker->sentence(10),
            'target_amount' => $this->faker->numberBetween(1000000, 50000000),
            'current_amount' => $this->faker->numberBetween(0, 5000000),
            'target_date' => $this->faker->dateTimeBetween('+1 month', '+1 year'),
            'status' => 'active',
            'category' => $this->faker->randomElement(['savings', 'vacation', 'investment', 'emergency_fund', 'education']),
        ];
    }
}
