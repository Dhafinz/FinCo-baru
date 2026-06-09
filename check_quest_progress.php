<?php
$db = new PDO('sqlite:database/database.sqlite');

echo "=== USER 6 ACTIVE QUESTS ===\n";
$r = $db->query("SELECT id, name, status, start_date, end_date, reward_xp, criteria, category FROM challenges WHERE user_id = 6 ORDER BY id");
$quests = $r->fetchAll(PDO::FETCH_ASSOC);
if (count($quests) === 0) {
    echo "Tidak ada quest untuk user 6\n";
} else {
    foreach ($quests as $q) {
        echo "ID: {$q['id']}\n";
        echo "Name: {$q['name']}\n";
        echo "Status: {$q['status']}\n";
        echo "Start: {$q['start_date']} → End: {$q['end_date']}\n";
        echo "Reward XP: {$q['reward_xp']}\n";
        echo "Criteria: {$q['criteria']}\n";
        echo str_repeat('-', 40) . "\n";
    }
}

echo "\n=== USER 6 INCOME TRANSACTIONS ===\n";
$r = $db->query("SELECT id, amount, description, transaction_date, type, mode FROM transactions WHERE user_id = 6 AND type='income' ORDER BY id");
$incomes = $r->fetchAll(PDO::FETCH_ASSOC);
if (count($incomes) === 0) {
    echo "Tidak ada transaksi income\n";
} else {
    $total = 0;
    foreach ($incomes as $t) {
        echo "Rp " . number_format($t['amount'], 0) . " — {$t['description']} ({$t['transaction_date']})\n";
        $total += $t['amount'];
    }
    echo "TOTAL INCOME: Rp " . number_format($total, 0) . "\n";
}
