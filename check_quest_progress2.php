<?php
$db = new PDO('sqlite:database/database.sqlite');

echo "=== ALL QUESTS (challenges) ===\n";
$r = $db->query("SELECT c.id, c.user_id, u.name as user_name, c.name, c.status, c.start_date, c.end_date, c.reward_xp, c.criteria 
    FROM challenges c LEFT JOIN users u ON c.user_id = u.id ORDER BY c.id");
foreach ($r as $q) {
    echo "ID:{$q['id']} User:{$q['user_name']}({$q['user_id']}) — {$q['name']} — {$q['status']} — {$q['start_date']}→{$q['end_date']}\n";
    echo "  Reward:{$q['reward_xp']}XP Criteria:{$q['criteria']}\n\n";
}

echo "\n=== ALL INCOME TRANSACTIONS ===\n";
$r = $db->query("SELECT t.id, t.user_id, u.name, t.amount, t.description, t.transaction_date, t.mode 
    FROM transactions t JOIN users u ON t.user_id = u.id WHERE t.type='income' ORDER BY t.id");
foreach ($r as $t) {
    echo "User:{$t['name']} Rp{$t['amount']} {$t['description']} {$t['transaction_date']} mode:{$t['mode']}\n";
}
