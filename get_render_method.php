<?php
$c = file_get_contents('app/Http/Controllers/UserDashboardController.php');
$s = strpos($c, 'function renderFeature');
$e = strpos($c, 'function', $s + 30);
echo substr($c, $s, $e - $s);
