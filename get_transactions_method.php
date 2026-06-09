<?php
$c = file_get_contents('app/Http/Controllers/UserDashboardController.php');
// Find transactions method
$start = strpos($c, 'function transactions');
$end = strpos($c, 'function', $start + 20);
echo substr($c, $start, $end - $start);
