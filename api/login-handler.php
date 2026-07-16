<?php

session_start();
require __DIR__ . '/includes/db.php';

$username = $_POST["username"] ?? '';
$password = $_POST["password"] ?? '';

$sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 1) {
    $_SESSION["user"] = mysqli_fetch_assoc($result);
    header("Location: index.php");
    exit();
} else {
    header("Location: login.php?error=1");
    exit();
}
