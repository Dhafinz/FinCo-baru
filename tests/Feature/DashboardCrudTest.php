<?php

namespace Tests\Feature;

use App\Models\Budget;
use App\Models\Challenge;
use App\Models\FinancialGoal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardCrudTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create([
            'username' => 'testuser',
            'role' => 'user',
        ]);
    }

    /** @test */
    public function user_can_create_budget()
    {
        $response = $this->actingAs($this->user)->post(route('dashboard.budgets.store'), [
            'category' => 'food',
            'limit_amount' => 1500000,
            'period' => 'monthly',
            'period_start' => now()->toDateString(),
            'period_end' => now()->addMonth()->toDateString(),
            'is_active' => 1,
        ]);

        $response->assertRedirect(route('dashboard.budgets'));
        $this->assertDatabaseHas('budgets', [
            'user_id' => $this->user->id,
            'category' => 'food',
            'limit_amount' => 1500000,
        ]);
    }

    /** @test */
    public function user_can_update_budget()
    {
        $budget = Budget::factory()->for($this->user)->create([
            'category' => 'food',
            'limit_amount' => 1500000,
        ]);

        $response = $this->actingAs($this->user)->put(route('dashboard.budgets.update', $budget), [
            'category' => 'transport',
            'limit_amount' => 2000000,
            'period' => 'monthly',
            'period_start' => now()->toDateString(),
            'period_end' => now()->addMonth()->toDateString(),
            'is_active' => 1,
        ]);

        $response->assertRedirect(route('dashboard.budgets'));
        $budget->refresh();
        $this->assertEquals('transport', $budget->category);
        $this->assertEquals(2000000, $budget->limit_amount);
    }

    /** @test */
    public function user_can_delete_budget()
    {
        $budget = Budget::factory()->for($this->user)->create();

        $response = $this->actingAs($this->user)->delete(route('dashboard.budgets.destroy', $budget));

        $response->assertRedirect(route('dashboard.budgets'));
        $this->assertDatabaseMissing('budgets', ['id' => $budget->id]);
    }

    /** @test */
    public function user_can_create_goal()
    {
        $response = $this->actingAs($this->user)->post(route('dashboard.goals.store'), [
            'name' => 'Fund Holiday',
            'description' => 'Save for holiday in Bali',
            'target_amount' => 10000000,
            'target_date' => now()->addMonths(6)->toDateString(),
        ]);

        $response->assertRedirect(route('dashboard.goals'));
        $this->assertDatabaseHas('financial_goals', [
            'user_id' => $this->user->id,
            'name' => 'Fund Holiday',
        ]);
    }

    /** @test */
    public function user_can_update_goal()
    {
        $goal = FinancialGoal::factory()->for($this->user)->create([
            'name' => 'Original Goal',
            'target_amount' => 5000000,
        ]);

        $response = $this->actingAs($this->user)->put(route('dashboard.goals.update', $goal), [
            'name' => 'Updated Goal',
            'target_amount' => 7000000,
            'current_amount' => 2000000,
            'target_date' => now()->addMonths(6)->toDateString(),
            'status' => 'active',
        ]);

        $response->assertRedirect(route('dashboard.goals'));
        $goal->refresh();
        $this->assertEquals('Updated Goal', $goal->name);
        $this->assertEquals(7000000, $goal->target_amount);
    }

    /** @test */
    public function user_can_delete_goal()
    {
        $goal = FinancialGoal::factory()->for($this->user)->create();

        $response = $this->actingAs($this->user)->delete(route('dashboard.goals.destroy', $goal));

        $response->assertRedirect(route('dashboard.goals'));
        $this->assertDatabaseMissing('financial_goals', ['id' => $goal->id]);
    }

    /** @test */
    public function user_can_create_challenge()
    {
        $response = $this->actingAs($this->user)->post(route('dashboard.challenges.store'), [
            'name' => 'No Spend Week',
            'description' => 'No spending for 7 days',
            'difficulty' => 'easy',
            'reward_xp' => 100,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(7)->toDateString(),
        ]);

        $response->assertRedirect(route('dashboard.challenges'));
        $this->assertDatabaseHas('challenges', [
            'user_id' => $this->user->id,
            'name' => 'No Spend Week',
        ]);
    }

    /** @test */
    public function user_can_update_challenge()
    {
        $challenge = Challenge::factory()->for($this->user)->create([
            'name' => 'Original Challenge',
            'difficulty' => 'easy',
        ]);

        $response = $this->actingAs($this->user)->put(route('dashboard.challenges.update', $challenge), [
            'name' => 'Updated Challenge',
            'description' => 'Updated description',
            'difficulty' => 'hard',
            'reward_xp' => 200,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(30)->toDateString(),
            'status' => 'active',
        ]);

        $response->assertRedirect(route('dashboard.challenges'));
        $challenge->refresh();
        $this->assertEquals('Updated Challenge', $challenge->name);
        $this->assertEquals('hard', $challenge->difficulty);
    }

    /** @test */
    public function user_can_delete_challenge()
    {
        $challenge = Challenge::factory()->for($this->user)->create();

        $response = $this->actingAs($this->user)->delete(route('dashboard.challenges.destroy', $challenge));

        $response->assertRedirect(route('dashboard.challenges'));
        $this->assertDatabaseMissing('challenges', ['id' => $challenge->id]);
    }

    /** @test */
    public function user_cannot_modify_other_users_budget()
    {
        $otherUser = User::factory()->create();
        $budget = Budget::factory()->for($otherUser)->create();

        $response = $this->actingAs($this->user)->put(route('dashboard.budgets.update', $budget), [
            'category' => 'food',
            'limit_amount' => 1000000,
            'period' => 'monthly',
            'period_start' => now()->toDateString(),
            'period_end' => now()->addMonth()->toDateString(),
            'is_active' => 1,
        ]);

        $response->assertStatus(403);
    }
}
