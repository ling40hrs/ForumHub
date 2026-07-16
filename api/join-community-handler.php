<?php

session_start();
require __DIR__ . '/includes/db.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

$community_id = intval($_POST['community_id'] ?? 0);
$user_id      = intval($_SESSION['user']['id']);

if ($community_id === 0) {            // nothing valid to act on
    header('Location: communities.php');
    exit();
}

$res = mysqli_query($conn,
    "SELECT 1 FROM community_members
     WHERE user_id = $user_id AND community_id = $community_id LIMIT 1");
$isMember = mysqli_num_rows($res) > 0;

if ($isMember) {
    mysqli_query($conn,
        "DELETE FROM community_members
         WHERE user_id = $user_id AND community_id = $community_id");
} else {
    mysqli_query($conn,
        "INSERT INTO community_members (user_id, community_id)
         VALUES ($user_id, $community_id)");
}

header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
exit();
