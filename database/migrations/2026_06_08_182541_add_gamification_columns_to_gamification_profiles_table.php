<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gamification_profiles', function (Blueprint $table) {
            $table->integer('xp_to_next_level')->default(100)->after('total_xp');
            $table->integer('total_badges')->default(0)->after('xp_to_next_level');
            $table->integer('total_achievements')->default(0)->after('total_badges');
            $table->integer('challenges_completed')->default(0)->after('total_achievements');
            $table->timestamp('last_level_up')->nullable()->after('challenges_completed');
        });
    }

    public function down(): void
    {
        Schema::table('gamification_profiles', function (Blueprint $table) {
            $table->dropColumn(['xp_to_next_level', 'total_badges', 'total_achievements', 'challenges_completed', 'last_level_up']);
        });
    }
};
