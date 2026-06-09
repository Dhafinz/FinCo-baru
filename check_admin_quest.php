<?php
echo "=== QUEST MODEL ===\n";
$path = 'app/Models/Quest.php';
if (file_exists($path)) {
    echo file_get_contents($path) . "\n";
} else {
    echo "FILE NOT FOUND\n";
}

echo "=== ADMIN QUEST CONTROLLER ===\n";
echo file_get_contents('app/Http/Controllers/Admin/QuestController.php');
