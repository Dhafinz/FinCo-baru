<?php
$files = [
    'app/Http/Controllers/Admin/QuestController.php',
    'resources/views/admin/quests/index.blade.php',
    'resources/views/quests/index.blade.php',
    'database/migrations/2026_03_28_080054_create_quests_table.php',
];
foreach ($files as $f) {
    echo $f . ": " . (file_exists($f) ? "EXISTS" : "MISSING") . "\n";
}
