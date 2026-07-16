<?php

session_start();

require __DIR__ . '/includes/db.php';
require __DIR__ . '/includes/helpers.php';

if (isset($_GET['slug'])) {
    $slug = mysqli_real_escape_string($conn, $_GET['slug']);
} else {
    $slug = '';
}

$communityResult = mysqli_query($conn, "
    SELECT c.*, (SELECT COUNT(*) FROM community_members WHERE community_id = c.id) AS members_count
    FROM communities c WHERE c.slug = '$slug'
");

if (mysqli_num_rows($communityResult) == 0) {
    http_response_code(404);
    $title = 'Not found';
    require __DIR__ . '/includes/header.php';
    echo '<div class="card p-8 text-center"><p class="text-ink-faint">Community not found.</p></div>';
    require __DIR__ . '/includes/footer.php';
    exit();
}

$community = mysqli_fetch_assoc($communityResult);

$postsResult = mysqli_query($conn, "
    SELECT p.*, u.username AS author_username,
           (SELECT COUNT(*) FROM comments WHERE post_id = p.id) AS comments_count
    FROM posts p
    JOIN users u ON p.user_id = u.id
    WHERE p.community_id = {$community['id']}
    ORDER BY p.created_at DESC
");

$communityPosts = [];

while ($row = mysqli_fetch_assoc($postsResult)) {
    $row['author'] = ['username' => $row['author_username']];
    $row['community'] = $community['name'];
    $row['community_slug'] = $community['slug'];
    $row['time'] = timeAgo($row['created_at']);
    $row['author_id'] = $row['user_id'];
    $communityPosts[] = $row;
}

$title = $community['name'];
$ogType = 'website';
$ogDescription = $community['description'];

require __DIR__ . '/includes/header.php';
?>
<div class="space-y-6">
  <a href="index.php" class="text-sm text-ink-faint transition hover:text-pop">← Back to feed</a>
  <div class="card animate-fade-in-up overflow-hidden">
    <div aria-hidden="true" class="h-24 animate-gradient-pan bg-[length:200%_200%] bg-gradient-to-r from-pop via-pop-dark to-ink"></div>
    <div class="p-4">
      <h1 class="font-display text-2xl font-bold text-ink"><?= escapeHtml($community['name']) ?></h1>
      <p class="mt-1 text-sm text-ink-faint"><?= escapeHtml($community['description']) ?></p>
      <div class="mt-3 flex items-center gap-3">
        <span class="text-sm text-ink-faint"><?= escapeHtml($community['members_count']) ?> members</span>
        <button type="button" class="btn-pop">Join</button>
      </div>
    </div>
  </div>
  <section class="space-y-4">
    <?php foreach ($communityPosts as $i => $post): ?>
      <?= postCardHtml($post, $i) ?>
    <?php endforeach; ?>
    <?php if (empty($communityPosts)): ?>
      <p class="rounded-xl border border-dashed border-line p-8 text-center text-ink-faint">No posts yet.</p>
    <?php endif; ?>
  </section>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
