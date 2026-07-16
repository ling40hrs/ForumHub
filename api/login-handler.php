<?php

session_start();
require __DIR__ . '/includes/db.php';

$username = mysqli_real_escape_string($conn, $_POST["username"] ?? '');
$password = $_POST["password"] ?? '';

$sql = "SELECT id, username, email, password, avatar AS avatar_url, created_at FROM users WHERE username = '$username'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 1) {
    $user = mysqli_fetch_assoc($result);
    if (password_verify($password, $user["password"])) {
        unset($user["password"]);
        $_SESSION["user"] = $user;
        header("Location: index.php");
        exit();
    }
}
header("Location: login.php?error=1");
exit();
