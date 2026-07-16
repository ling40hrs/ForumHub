<?php

session_start();
require __DIR__ . '/includes/db.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

$target_id   = intval($_POST['target_id'] ?? 0);
$target_type = ($_POST['target_type'] ?? 'post') === 'comment' ? 'comment' : 'post';
$value       = (($_POST['vote'] ?? '') === '1') ? 1 : -1;
$user_id     = intval($_SESSION['user']['id']);

if ($target_id === 0) {            // nothing valid to vote on
    header('Location: index.php');
    exit();
}

$res = mysqli_query($conn,
    "SELECT value FROM votes
     WHERE user_id = $user_id AND target_id = $target_id AND target_type = '$target_type'");
$row = mysqli_fetch_assoc($res);
$old = $row ? intval($row['value']) : 0;

mysqli_query($conn,
    "DELETE FROM votes
     WHERE user_id = $user_id AND target_id = $target_id AND target_type = '$target_type'");

if ($old !== $value) {
    mysqli_query($conn,
        "INSERT INTO votes (user_id, target_id, target_type, value)
         VALUES ($user_id, $target_id, '$target_type', $value)");
}

$delta = ($old !== $value) ? $value - $old : -$old;
$table = ($target_type === 'post') ? 'posts' : 'comments';
mysqli_query($conn, "UPDATE $table SET score = score + $delta WHERE id = $target_id");

$scoreRes = mysqli_query($conn, "SELECT score FROM $table WHERE id = $target_id");
$total = intval(mysqli_fetch_assoc($scoreRes)['score'] ?? 0);

$newVote = ($old !== $value) ? $value : 0;

if (($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest') {
    header('Content-Type: application/json');
    echo json_encode(['score' => $total, 'my_vote' => $newVote]);
    exit();
}

header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
exit();
