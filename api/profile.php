<?php

session_start();

require __DIR__ . '/includes/db.php';
require __DIR__ . '/includes/helpers.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
} elseif (isset($_SESSION['user']['id'])) {
    $id = intval($_SESSION['user']['id']);
} else {
    $id = 0;
}

$userResult = mysqli_query($conn, "
    SELECT id, username, avatar AS avatar_url, bio, karma, created_at
    FROM users WHERE id = $id
");

if (mysqli_num_rows($userResult) == 0) {
    http_response_code(404);
    $title = 'Not found';
    require __DIR__ . '/includes/header.php';
    echo '<div class="card p-8 text-center"><p class="text-ink-faint">User not found.</p></div>';
    require __DIR__ . '/includes/footer.php';
    exit();
}

$viewed = mysqli_fetch_assoc($userResult);

$postsResult = mysqli_query($conn, "
    SELECT p.*, u.username AS author_username,
           c.name AS community_name, c.slug AS community_slug,
           (SELECT COUNT(*) FROM comments WHERE post_id = p.id) AS comments_count
    FROM posts p
    JOIN users u ON p.user_id = u.id
    JOIN communities c ON p.community_id = c.id
    WHERE p.user_id = $id
    ORDER BY p.created_at DESC
");

$userPosts = [];

while ($row = mysqli_fetch_assoc($postsResult)) {
    $row['author'] = ['username' => $row['author_username']];
    $row['community'] = $row['community_name'];
    $row['community_slug'] = $row['community_slug'];
    $row['time'] = timeAgo($row['created_at']);
    $row['author_id'] = $row['user_id'];
    $userPosts[] = $row;
}

$title = $viewed['username'];
$ogType = 'profile';

if (isset($viewed['bio']) && $viewed['bio'] !== '') {
    $ogDescription = $viewed['bio'];
} else {
    $ogDescription = $viewed['username'] . ' on Yapr';
}

require __DIR__ . '/includes/header.php';
?>
<div class="space-y-6">
  <a href="index.php" class="text-sm text-ink-faint transition hover:text-pop">← Back to feed</a>
  <div class="card reveal flex items-center gap-4 p-5">
    <?= avatarHtml($viewed, 16) ?>
    <div>
      <h1 class="font-display text-2xl font-bold text-ink"><?= escapeHtml($viewed['username']) ?></h1>
      <p class="text-sm text-ink-faint"><?= escapeHtml($viewed['karma']) ?> karma</p>
    </div>
  </div>
  <?php if (isset($viewed['bio']) && $viewed['bio'] !== ''): ?>
    <p class="card -mt-3 p-5 text-ink-soft"><?= escapeHtml($viewed['bio']) ?></p>
  <?php endif; ?>
  <section class="space-y-4">
    <h2 class="font-display text-lg font-semibold text-ink">Posts</h2>
    <?php foreach ($userPosts as $i => $post): ?>
      <?= postCardHtml($post, $i) ?>
    <?php endforeach; ?>
    <?php if (empty($userPosts)): ?>
      <p class="rounded-xl border border-dashed border-line p-8 text-center text-ink-faint">No posts yet.</p>
    <?php endif; ?>
  </section>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
