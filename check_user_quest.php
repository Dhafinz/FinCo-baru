<?php
$files = [
    'routes/web.php',
    'app/Http/Controllers/User/QuestController.php',
    'database/migrations/2026_03_28_080055_create_user_quests_table.php',
    'app/Models/UserQuest.php',
];
foreach ($files as $f) {
    echo $f . ': ' . (file_exists($f) ? 'EXISTS' : 'MISSING') . "\n";
}

echo "\n=== user_quests in codebase ===\n";
$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('app'));
foreach ($it as $f) {
    if ($f->isFile()) {
        $c = file_get_contents($f->getPathname());
        if (stripos($c, 'user_quest') !== false) {
            echo "  " . $f->getPathname() . "\n";
        }
    }
}
echo "No code references user_quests\n";
