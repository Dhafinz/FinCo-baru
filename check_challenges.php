<?php
$db = new PDO('sqlite:database/database.sqlite');

echo "=== CHALLENGES TABLE ===\n";
$cols = $db->query("PRAGMA table_info(challenges)");
foreach ($cols as $c) echo "  {$c['name']} ({$c['type']})\n";

echo "\nChallenges count: " . $db->query("SELECT COUNT(*) FROM challenges")->fetchColumn() . "\n";

echo "\n=== USER_QUESTS TABLE ===\n";
$cols = $db->query("PRAGMA table_info(user_quests)");
if ($cols) {
    foreach ($cols as $c) echo "  {$c['name']} ({$c['type']})\n";
} else {
    echo "  Table does not exist\n";
}

echo "\n=== USER_CHALLENGES TABLE ===\n";
$cols = $db->query("PRAGMA table_info(user_challenges)");
if ($cols) {
    foreach ($cols as $c) echo "  {$c['name']} ({$c['type']})\n";
    echo "Count: " . $db->query("SELECT COUNT(*) FROM user_challenges")->fetchColumn() . "\n";
} else {
    echo "  Table does not exist\n";
}
