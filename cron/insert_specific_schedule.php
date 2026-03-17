<?php
if ($argc < 2) { echo "Usage: php insert_specific_schedule.php HH:MM:00\n"; exit(1); }
$time = $argv[1];
include __DIR__ . "/../config/db.php";
$tz = date_default_timezone_get();
if (empty($tz) || $tz === 'UTC') date_default_timezone_set('Asia/Jakarta');
$day = date('l');
$sql = $conn->prepare("INSERT INTO schedules (bell_time,day,sound_id) VALUES (?, ?, ?)");
$sql->bind_param('ssi', $time, $day, $sid);
$sid = 1;
if (!$sql->execute()) { echo "ERR: " . $sql->error . "\n"; exit(1); }
echo "INSERTED:" . $conn->insert_id . " TIME:" . $time . "\n";
?>