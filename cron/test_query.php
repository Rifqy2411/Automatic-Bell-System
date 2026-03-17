<?php
date_default_timezone_set('Asia/Jakarta');
include __DIR__ . "/../config/db.php";
$ct = date('H:i');
$cd = date('l');
$q = "SELECT schedules.*, sounds.filename FROM schedules JOIN sounds ON schedules.sound_id = sounds.id WHERE TIME_FORMAT(schedules.bell_time, '%H:%i') = '".$ct."' AND schedules.day = '".$cd."'";
file_put_contents(__DIR__ . '/bell_runner.log', "[test_query] CT={$ct} CD={$cd} Query={$q}\n", FILE_APPEND);
$r = $conn->query($q);
if (!$r) { file_put_contents(__DIR__ . '/bell_runner.log', "[test_query] ERR: " . $conn->error . "\n", FILE_APPEND); exit(1); }
file_put_contents(__DIR__ . '/bell_runner.log', "[test_query] rows=".$r->num_rows."\n", FILE_APPEND);
while ($row = $r->fetch_assoc()) {
    file_put_contents(__DIR__ . '/bell_runner.log', "[test_query] ROW:" . json_encode($row) . "\n", FILE_APPEND);
}
echo "OK\n";
?>