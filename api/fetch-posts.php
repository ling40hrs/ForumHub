<?php

session_start();

require __DIR__ . '/includes/db.php';
require __DIR__ . '/includes/helpers.php';

$userId = intval($_SESSION['user']['id'] ?? 0);

session_write_close();

$sort = $_GET['sort'] ?? 'new';
$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 10;
$offset = ($page - 1) * $perPage;

if ($sort === 'top') {
    $orderBy = 'p.score DESC, p.created_at DESC';
} else {
    $orderBy = 'p.created_at DESC';
}

$postsResult = mysqli_query($conn, "
    SELECT p.*, u.username AS author_username,
           c.name AS community_name, c.slug AS community_slug,
           (SELECT COUNT(*) FROM comments WHERE post_id = p.id) AS comments_count,
           (SELECT v.value FROM votes v
            WHERE v.user_id = $userId AND v.target_id = p.id AND v.target_type = 'post') AS my_vote
    FROM posts p
    JOIN users u ON p.user_id = u.id
    JOIN communities c ON p.community_id = c.id
    ORDER BY $orderBy
    LIMIT $offset, $perPage
");

$posts = [];

while ($row = mysqli_fetch_assoc($postsResult)) {
    $row['author'] = ['username' => $row['author_username']];
    $row['community'] = $row['community_name'];
    $row['community_slug'] = $row['community_slug'];
    $row['time'] = timeAgo($row['created_at']);
    $row['author_id'] = $row['user_id'];
    $posts[] = $row;
}

$html = '';
foreach ($posts as $p) {
    $html .= postCardHtml($p);
}

$countResult = mysqli_query($conn, "SELECT COUNT(*) AS total FROM posts");
$total = intval(mysqli_fetch_assoc($countResult)['total']);
$totalPages = (int) ceil($total / $perPage);

header('Content-Type: application/json');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('X-Content-Type-Options: nosniff');
echo json_encode([
    'html' => $html,
    'hasMore' => $page < $totalPages,
]);
