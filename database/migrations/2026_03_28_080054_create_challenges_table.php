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
        Schema::create('challenges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('difficulty', ['easy', 'medium', 'hard'])->default('easy');
            $table->integer('reward_xp')->default(10);
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['active', 'completed', 'failed'])->default('active');
            $table->string('category')->nullable(); // e.g., 'No Spend Day', 'Weekly Save', 'Expense Control'
            $table->text('criteria')->nullable(); // JSON rules or description
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('challenges');
    }
};
