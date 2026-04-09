<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('top_ups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('wallet_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 15, 2);
            $table->enum('payment_method', ['bank_transfer', 'virtual_account', 'qris']);
            $table->enum('status', ['pending', 'success', 'failed'])->default('success');
            $table->string('reference_number', 50);
            $table->integer('xp_earned')->default(0);
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index('reference_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('top_ups');
    }
};
