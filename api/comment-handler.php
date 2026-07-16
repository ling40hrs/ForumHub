<?php

session_start();
require __DIR__ . '/includes/db.php';
require __DIR__ . '/includes/helpers.php';

if (!isset($_SESSION['user'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'You must be logged in.']);
    exit();
}

$body     = trim($_POST['body'] ?? '');
$postId   = intval($_POST['post_id'] ?? 0);
$parentId = intval($_POST['parent_id'] ?? 0);
$userId   = intval($_SESSION['user']['id']);

if ($body === '' || $postId === 0) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Missing body or post.']);
    exit();
}

if ($parentId > 0) {
    $depth = commentDepth($conn, $parentId);
    if ($depth >= 5) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Maximum nesting depth is 5 replies.']);
        exit();
    }
}

$safeBody = mysqli_real_escape_string($conn, $body);
$parentSql = $parentId > 0 ? $parentId : 'NULL';
mysqli_query($conn, "INSERT INTO comments (body, user_id, post_id, parent_id)
                     VALUES ('$safeBody', $userId, $postId, $parentSql)");
$newId = mysqli_insert_id($conn);

$result = mysqli_query($conn, "
    SELECT c.*, u.username AS author_username, u.avatar AS author_avatar_url,
           (SELECT v.value FROM votes v
            WHERE v.user_id = $userId AND v.target_id = c.id AND v.target_type = 'comment') AS my_vote
    FROM comments c
    JOIN users u ON c.user_id = u.id
    WHERE c.id = $newId
");
$row = mysqli_fetch_assoc($result);
$row['author'] = ['username' => $row['author_username'], 'avatar_url' => $row['author_avatar_url']];
$row['time'] = timeAgo($row['created_at']);

$newDepth = 0;
if ($parentId > 0) {
    $newDepth = commentDepth($conn, $parentId) + 1;
}

$commentHtml = commentItemHtml($row, $postId, $newDepth);
$html = '<div class="comment-thread" data-comment-id="' . $newId . '">' . $commentHtml . '</div>';

header('Content-Type: application/json');
echo json_encode([
    'success'   => true,
    'html'      => $html,
    'parent_id' => $parentId,
]);
exit();
