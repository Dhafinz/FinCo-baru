<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('budgets', function (Blueprint $table) {
            if (!Schema::hasColumn('budgets', 'category_id')) {
                // For SQLite compatibility, use unsignedBigInteger instead of foreignId
                $table->unsignedBigInteger('category_id')
                    ->nullable()
                    ->after('category');
            }
        });

        $categoryMap = [
            'food' => 'Makanan & Minuman',
            'transport' => 'Transportasi',
            'utilities' => 'Tagihan',
            'entertainment' => 'Hiburan',
            'health' => 'Kesehatan',
            'education' => null,
            'shopping' => 'Belanja',
            'other' => null,
        ];

        foreach ($categoryMap as $budgetKey => $categoryName) {
            if (! $categoryName) {
                continue;
            }

            $categoryId = DB::table('categories')
                ->where('name', $categoryName)
                ->where('type', 'expense')
                ->value('id');

            if (! $categoryId) {
                continue;
            }

            DB::table('budgets')
                ->where('category', $budgetKey)
                ->whereNull('category_id')
                ->update(['category_id' => $categoryId]);
        }
    }

    public function down(): void
    {
        Schema::table('budgets', function (Blueprint $table) {
            if (Schema::hasColumn('budgets', 'category_id')) {
                $table->dropColumn('category_id');
            }
        });
    }
};
