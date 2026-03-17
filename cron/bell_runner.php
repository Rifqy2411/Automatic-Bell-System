<?php
include __DIR__ . "/../config/db.php";

// Get current server time & day
$tz = date_default_timezone_get();
if (empty($tz) || $tz === 'UTC') {
    date_default_timezone_set('Asia/Jakarta');
}

$currentTime = date("H:i");
$currentDay = date("l"); // Monday, Tuesday, etc

// Fetch matching schedules
$query = "SELECT schedules.*, sounds.filename 
          FROM schedules 
          JOIN sounds ON schedules.sound_id = sounds.id
          WHERE TIME_FORMAT(schedules.bell_time, '%H:%i') = '$currentTime'
          AND schedules.day = '$currentDay'";

$result = $conn->query($query);
if ($result === false) {
    error_log("[bell_runner] SQL error: " . $conn->error);
    exit;
}

// Simple file logging for diagnostics
$logFile = __DIR__ . '/bell_runner.log';
function lr($msg) {
    global $logFile;
    file_put_contents($logFile, date('[Y-m-d H:i:s] ') . $msg . PHP_EOL, FILE_APPEND);
}

// Simple locking to avoid concurrent runs (prevents overlapping mpg123 instances)
$lockFile = __DIR__ . '/bell_runner.lock';
$lockFp = fopen($lockFile, 'c');
if ($lockFp === false) {
    lr("Cannot open lock file {$lockFile}");
    exit;
}
if (!flock($lockFp, LOCK_EX | LOCK_NB)) {
    lr("Another instance is running, exiting");
    exit;
}

lr("Started. time={$currentTime} day={$currentDay}");
lr("Query: " . $query);

lr("SQL result rows: " . $result->num_rows);

while ($row = $result->fetch_assoc()) {
    $file = __DIR__ . "/../uploads/" . $row['filename'];
    lr("Found schedule id={$row['id']} file={$file}");

    if (!file_exists($file)) {
        lr("Missing file: {$file}");
        continue;
    }

    // Try PulseAudio (using user's runtime dir), then ALSA, then dummy output.
    $uid = function_exists('posix_getuid') ? posix_getuid() : null;
    $xrd = $uid ? "/run/user/{$uid}" : null;
    $candidates = [];
    if ($xrd) $candidates[] = "XDG_RUNTIME_DIR={$xrd} DISPLAY=:0 /usr/bin/mpg123 -o pulse " . escapeshellarg($file) . " 2>&1";
    $candidates[] = "DISPLAY=:0 /usr/bin/mpg123 -o alsa " . escapeshellarg($file) . " 2>&1";
    $candidates[] = "/usr/bin/mpg123 -o dummy " . escapeshellarg($file) . " 2>&1";

    $played = false;
    foreach ($candidates as $cmd) {
        lr("Running: {$cmd}");
        exec($cmd, $output, $rc);
        lr("Return code: {$rc}");
        if (!empty($output)) {
            foreach ($output as $ol) lr("OUT: " . $ol);
        }
        if ($rc === 0) { $played = true; break; }
    }
    if (!$played) {
        lr("All output modules failed for file {$file}");
    }
}

?>

lr("Finished run.");

// release lock
flock($lockFp, LOCK_UN);
fclose($lockFp);