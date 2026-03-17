<?php
include "../config/db.php";
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$data = $conn->query("SELECT * FROM schedules WHERE id={$id}")->fetch_assoc();

if (isset($_POST['update'])) {
    $rawTime = isset($_POST['time']) ? trim($_POST['time']) : '';
    $day = $_POST['day'];

    if (preg_match('/^\d{2}:\d{2}(?::\d{2})?$/', $rawTime)) {
        $normTime = date('H:i:00', strtotime($rawTime));
        $stmt = $conn->prepare("UPDATE schedules SET bell_time=?, day=? WHERE id=?");
        if ($stmt) {
            $stmt->bind_param('ssi', $normTime, $day, $id);
            if (!$stmt->execute()) {
                $formError = 'Database error: ' . $stmt->error;
            } else {
                header("Location: schedule.php");
                exit;
            }
            $stmt->close();
        } else {
            $formError = 'Prepare failed: ' . $conn->error;
        }
    } else {
        $formError = 'Format waktu tidak valid. Gunakan HH:MM.';
    }
}
?>

<form method="POST">
    <input type="time" name="time" value="<?= date('H:i', strtotime($data['bell_time'])) ?>">
    <input type="text" name="day" value="<?= $data['day'] ?>">
    <button name="update">Update</button>
</form>
