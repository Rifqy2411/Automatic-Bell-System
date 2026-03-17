<?php
include "../config/db.php";
$id = $_GET['id'];

$data = $conn->query("SELECT * FROM schedules WHERE id=$id")->fetch_assoc();

if (isset($_POST['update'])) {
    $time = $_POST['time'];
    $day = $_POST['day'];

    $conn->query("UPDATE schedules SET bell_time='$time', day='$day' WHERE id=$id");
    header("Location: schedule.php");
}
?>

<form method="POST">
    <input type="time" name="time" value="<?= $data['bell_time'] ?>">
    <input type="text" name="day" value="<?= $data['day'] ?>">
    <button name="update">Update</button>
</form>
