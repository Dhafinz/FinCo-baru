<?php
echo "=== PHP SYNTAX CHECK ===\n";
$files = [
    'app/Http/Resources/DashboardResource.php',
    'app/Http/Controllers/User/TransactionController.php',
    'app/Services/GamificationService.php',
    'app/Models/GamificationProfile.php',
    'app/Http/Controllers/WalletController.php',
    'app/Http/Requests/StoreTransactionRequest.php',
    'resources/views/wallet/index.blade.php',
    'resources/views/wallet/withdraw.blade.php',
];
foreach ($files as $f) {
    $ext = pathinfo($f, PATHINFO_EXTENSION);
    if ($ext === 'php') {
        $r = shell_exec("php -l " . escapeshellarg($f) . " 2>&1");
        echo $f . ": " . (strpos($r, 'No syntax errors') !== false ? "OK" : "ERROR: $r") . "\n";
    } else {
        echo $f . ": (blade - skip syntax check)\n";
    }
}

echo "\n=== DATABASE CHECK ===\n";
try {
    $db = new PDO('sqlite:database/database.sqlite');
    echo "Database connected OK\n";

    $tables = ['users', 'transactions', 'categories', 'budgets', 'financial_goals',
               'gamification_profiles', 'badges', 'quests', 'wallets', 'wallet_transactions',
               'top_ups', 'friendships', 'personal_access_tokens'];
    foreach ($tables as $t) {
        $c = $db->query("SELECT COUNT(*) FROM $t")->fetchColumn();
        echo "  $t: $c rows\n";
    }

    // Check gamification_profiles columns
    echo "\nGamification Profiles columns:\n";
    $cols = $db->query("PRAGMA table_info(gamification_profiles)")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($cols as $col) {
        echo "  - {$col['name']} ({$col['type']})\n";
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

echo "\n=== BLADE FILES CHECK ===\n";
$bladeFiles = [
    'wallet/index.blade.php',
    'wallet/withdraw.blade.php',
    'wallet/topup.blade.php',
    'wallet/transfer.blade.php',
];
foreach ($bladeFiles as $f) {
    $path = "resources/views/$f";
    if (file_exists($path)) {
        $content = file_get_contents($path);
        $hasWithdraw = strpos($content, 'withdraw') !== false;
        echo "$f: exists, " . ($hasWithdraw ? "HAS withdraw link" : "NO withdraw link") . "\n";
    } else {
        echo "$f: NOT FOUND\n";
    }
}

echo "\nAll checks done.\n";
