<?php
$db = new PDO('sqlite:database/database.sqlite');
echo "=== ALL TABLES IN DB ===\n";
$tables = $db->query("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name");
foreach ($tables as $t) {
    echo "  " . $t['name'] . "\n";
}

echo "\n=== MISSING TABLES CHECK ===\n";
$required = ['quests', 'user_quests', 'badges', 'user_badges', 'wallets', 'wallet_transactions', 'top_ups', 'friendships'];
$existing = $db->query("SELECT name FROM sqlite_master WHERE type='table'");
$existingNames = [];
foreach ($existing as $e) $existingNames[] = $e['name'];

foreach ($required as $t) {
    echo "  $t: " . (in_array($t, $existingNames) ? "EXISTS" : "MISSING") . "\n";
}

echo "\n=== FEATURE CHECKLIST ===\n";
$checks = [
    'users table' => 'users',
    'transactions table' => 'transactions',
    'categories table' => 'categories',
    'budgets table' => 'budgets',
    'financial_goals table' => 'financial_goals',
    'gamification_profiles table' => 'gamification_profiles',
    'wallets table' => 'wallets',
    'wallet_transactions table' => 'wallet_transactions',
    'top_ups table' => 'top_ups',
    'friendships table' => 'friendships',
    'badges table' => 'badges',
    'user_badges table' => 'user_badges',
    'personal_access_tokens table' => 'personal_access_tokens',
];

foreach ($checks as $name => $table) {
    $exists = in_array($table, $existingNames);
    echo ($exists ? "✅" : "❌") . " $name\n";
}

// Check gamification_profiles columns
echo "\n=== GAMIFICATION PROFILES COLUMNS ===\n";
$cols = $db->query("PRAGMA table_info(gamification_profiles)");
$colNames = [];
foreach ($cols as $c) {
    $colNames[] = $c['name'];
    echo "  {$c['name']} ({$c['type']})\n";
}

$requiredCols = ['xp_to_next_level', 'total_badges', 'total_achievements', 'challenges_completed', 'last_level_up'];
echo "\nRequired columns check:\n";
foreach ($requiredCols as $c) {
    echo "  $c: " . (in_array($c, $colNames) ? "✅" : "❌") . "\n";
}
