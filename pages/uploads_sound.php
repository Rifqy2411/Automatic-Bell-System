<?php
include "../auth/auth_check.php";
include "../config/db.php";

if (isset($_POST['upload'])) {
    $file = $_FILES['sound'];
    $fileName = $file['name'];
    $tmpName = $file['tmp_name'];

    move_uploaded_file($tmpName, "../uploads/" . $fileName);

    $conn->query("INSERT INTO sounds (filename) VALUES ('$fileName')");
}
?>

<form method="POST" enctype="multipart/form-data">
    <input type="file" name="sound" required>
    <button name="upload">Upload</button>
</form>