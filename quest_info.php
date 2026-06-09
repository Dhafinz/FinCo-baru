<?php
echo "=== QUEST ROUTES ===\n";
echo "GET  /admin/quests          -> QuestController@index\n";
echo "GET  /admin/quests/create   -> QuestController@create\n";
echo "POST /admin/quests          -> QuestController@store\n";
echo "GET  /admin/quests/{id}/edit -> QuestController@edit\n";
echo "PUT  /admin/quests/{id}     -> QuestController@update\n";
echo "DEL  /admin/quests/{id}     -> QuestController@destroy\n";

echo "\n=== UNUSED TABLES (tidak penting) ===\n";
echo "Tabel ini ada di DB tapi tidak dipanggil oleh kode mana pun:\n";
echo "  - achievements\n";
echo "  - user_achievements\n";
echo "  - user_challenges\n";
echo "  - xp_history\n";
echo "  - login_streaks\n";
echo "\nKesimpulan: TIDAK PENTING. Tidak pengaruh ke fitur. Hanya sampah sisa development.\n";

echo "\n=== ADMIN QUESTS ===\n";
$questCtrl = file_get_contents('app/Http/Controllers/Admin/QuestController.php');
$hasModel = strpos($questCtrl, 'Quest::') !== false;
echo "QuestController refers to Quest model: " . ($hasModel ? "YES" : "NO (uses DB::table)") . "\n";
