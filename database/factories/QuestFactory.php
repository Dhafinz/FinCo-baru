<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Quest>
 */
class QuestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('-7 days', 'now');

        return [
            'user_id' => User::factory(),
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->sentence(10),
            'difficulty' => $this->faker->randomElement(['easy', 'medium', 'hard']),
            'reward_xp' => $this->faker->numberBetween(50, 500),
            'start_date' => $startDate,
            'end_date' => $this->faker->dateTimeBetween($startDate, '+30 days'),
            'status' => 'active',
            'category' => $this->faker->randomElement(['manual', 'auto']),
            'criteria' => [
                'template_key' => $this->faker->slug(),
                'mode' => 'auto',
                'tracking' => 'transaction_count',
                'target' => $this->faker->numberBetween(5, 20),
            ],
        ];
    }
}