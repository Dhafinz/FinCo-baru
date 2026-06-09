<?php
$db = new PDO("sqlite:database/database.sqlite");
$userId = 6;

echo "=== TRANSAKSI ===\n";
$r = $db->query("SELECT id, type, amount, description, created_at FROM transactions WHERE user_id = $userId ORDER BY id");
foreach ($r as $row) {
    echo json_encode($row) . "\n";
}

echo "\n=== WALLET ===\n";
$r = $db->query("SELECT id, balance FROM wallets WHERE user_id = $userId");
foreach ($r as $row) {
    echo json_encode($row) . "\n";
}

echo "\n=== TOP UP ===\n";
$r = $db->query("SELECT id, amount, status FROM top_ups WHERE user_id = $userId");
foreach ($r as $row) {
    echo json_encode($row) . "\n";
}
