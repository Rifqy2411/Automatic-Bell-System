<?php
include __DIR__ . "/../config/db.php";
$res = $conn->query("UPDATE sounds SET filename='istirahat.mp3' WHERE id=1");
if (!$res) {
    echo "ERR: " . $conn->error . "\n";
    exit(1);
}
echo "UPDATED\n";
?>