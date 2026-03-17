<?php
include __DIR__ . "/../config/db.php";
$tz = date_default_timezone_get();
if (empty($tz) || $tz === 'UTC') {
	date_default_timezone_set('Asia/Jakarta');
}
$t = date('H:i:00');
$d = date('l');
$sql = $conn->prepare("INSERT INTO schedules (bell_time,day,sound_id) VALUES (?, ?, ?)");
$sql->bind_param('ssi',$t,$d,$sid);
$sid = 1;
if (!$sql->execute()) { echo "ERR: " . $sql->error . "\n"; exit(1); }
echo "INSERTED:" . $conn->insert_id . "\n";
?>