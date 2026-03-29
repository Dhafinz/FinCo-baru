<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('category'); // e.g., 'Food', 'Transport', 'Entertainment'
            $table->decimal('limit_amount', 15, 2);
            $table->decimal('spent_amount', 15, 2)->default(0);
            $table->enum('period', ['daily', 'weekly', 'monthly', 'yearly'])->default('monthly');
            $table->date('period_start');
            $table->date('period_end');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['user_id', 'period_start']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budgets');
    }
};
