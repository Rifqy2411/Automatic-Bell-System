<?php
session_start();
include "config/db.php";

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $result = $conn->query("SELECT * FROM users WHERE username='$username' AND password='$password'");

    if ($result->num_rows > 0) {
        $_SESSION['user'] = $username;
        header("Location: pages/dashboard.php");
    } else {
        echo "Invalid login";
    }
}
?>

<form method="POST">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button name="login">Login</button>
</form>