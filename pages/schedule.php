<?php
include "../auth/auth_check.php";
include "../config/db.php";

// CREATE
if (isset($_POST['add'])) {
    $time = $_POST['time'];
    $day = $_POST['day'];
    $sound_id = $_POST['sound_id'];

    $conn->query("INSERT INTO schedules (bell_time, day, sound_id) VALUES ('$time','$day','$sound_id')");
}

$sounds = $conn->query("SELECT * FROM sounds");
$schedules = $conn->query("SELECT schedules.*, sounds.filename FROM schedules JOIN sounds ON schedules.sound_id = sounds.id");
?>

<h3>Add Schedule</h3>
<form method="POST">
    <input type="time" name="time" required>

    <select name="day">
        <option>Monday</option>
        <option>Tuesday</option>
        <option>Wednesday</option>
        <option>Thursday</option>
        <option>Friday</option>
        <option>Saturday</option>
        <option>Sunday</option>
    </select>

    <select name="sound_id">
        <?php while ($row = $sounds->fetch_assoc()): ?>
            <option value="<?= $row['id'] ?>"><?= $row['filename'] ?></option>
        <?php endwhile; ?>
    </select>

    <button name="add">Add</button>
</form>

<h3>Schedules</h3>
<table border="1">
<tr>
    <th>Time</th>
    <th>Day</th>
    <th>Sound</th>
    <th>Action</th>
</tr>

<?php while ($row = $schedules->fetch_assoc()): ?>
<tr>
    <td><?= $row['bell_time'] ?></td>
    <td><?= $row['day'] ?></td>
    <td><?= $row['filename'] ?></td>
    <td>
        <a href="edit_schedule.php?id=<?= $row['id'] ?>">Edit</a> |
        <a href="delete_schedule.php?id=<?= $row['id'] ?>">Delete</a>
    </td>
</tr>
<?php endwhile; ?>
</table>
