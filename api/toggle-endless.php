<?php

session_start();

require __DIR__ . '/includes/db.php';
require __DIR__ . '/includes/auth.php';

requireLogin();

$userId = intval($_SESSION['user']['id']);

session_write_close();

header('Content-Type: application/json');

mysqli_query($conn, "
    UPDATE users
    SET endless_scroll = 1 - endless_scroll
    WHERE id = $userId
");

$result = mysqli_query($conn, "SELECT endless_scroll FROM users WHERE id = $userId");
$row = mysqli_fetch_assoc($result);

echo json_encode([
    'success' => true,
    'state' => intval($row['endless_scroll']),
]);
