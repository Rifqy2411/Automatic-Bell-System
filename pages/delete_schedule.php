<?php
include "../config/db.php";
$id = $_GET['id'];
$conn->query("DELETE FROM schedules WHERE id=$id");
header("Location: schedule.php");
?>