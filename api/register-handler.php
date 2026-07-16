<?php

session_start();
require __DIR__ . '/includes/db.php';

$username = mysqli_real_escape_string($conn, $_POST["username"] ?? '');
$email = mysqli_real_escape_string($conn, $_POST["email"] ?? '');
$password = $_POST["password"] ?? '';

if ($username === '' || $email === '' || $password === '') {
    header("Location: register.php?error=1");
    exit();
}

$hashed = password_hash($password, PASSWORD_DEFAULT);
$sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed')";

if (mysqli_query($conn, $sql)) {
    $result = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
    $_SESSION["user"] = mysqli_fetch_assoc($result);
    header("Location: index.php");
    exit();
} else {
    header("Location: register.php?error=2");
    exit();
}
