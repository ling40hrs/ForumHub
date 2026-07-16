<?php

declare(strict_types=1);

session_start();
require __DIR__ . '/includes/db.php';
require __DIR__ . '/includes/helpers.php';

$postsResult = mysqli_query($conn, "
    SELECT p.*, u.username AS author_username,
           c.name AS community_name, c.slug AS community_slug,
           (SELECT COUNT(*) FROM comments WHERE post_id = p.id) AS comments_count
    FROM posts p
    JOIN users u ON p.user_id = u.id
    JOIN communities c ON p.community_id = c.id
    ORDER BY p.created_at DESC
");
$posts = [];
while ($row = mysqli_fetch_assoc($postsResult)) {
    $row['author'] = ['username' => $row['author_username']];
    $row['community'] = $row['community_name'];
    $row['comments_count'] = $row['comments_count'] ?? 0;
    $row['author_id'] = $row['user_id'];
    $row['time'] = timeAgo($row['created_at']);
    $posts[] = $row;
}

$communitiesResult = mysqli_query($conn, "
    SELECT c.*, (SELECT COUNT(*) FROM community_members WHERE community_id = c.id) AS members_count
    FROM communities c ORDER BY c.name ASC
");
$communities = [];
while ($row = mysqli_fetch_assoc($communitiesResult)) {
    $communities[] = $row;
}

$title = 'Home';
$ogType = 'website';
$ogDescription = 'Yapr — a community where people yap about what they love.';
require __DIR__ . '/includes/header.php';
?>
<div class="grid gap-6 md:grid-cols-3">
  <section class="space-y-4 md:col-span-2">
    <div class="flex items-center justify-between">
      <div class="flex flex-wrap items-center gap-x-3 gap-y-1">
        <h1 class="font-display text-2xl font-bold text-ink">Posts</h1>
        <div class="flex items-center gap-1 text-sm font-medium">
          <span class="text-pop">Popular</span>
          <span class="text-line">·</span>
          <a href="#" class="text-ink-faint transition hover:text-ink">New</a>
          <span class="text-line">·</span>
          <a href="#" class="text-ink-faint transition hover:text-ink">Top</a>
        </div>
      </div>
      <?php if (isset($_SESSION['user'])): ?>
        <a href="create-post.php" class="btn-primary">New post</a>
      <?php endif; ?>
    </div>
    <?php foreach ($posts as $i => $post): ?>
      <?= postCardHtml($post, $i) ?>
    <?php endforeach; ?>
  </section>
  <aside class="space-y-4">
    <div class="card p-4">
      <h2 class="mb-3 font-display font-semibold text-ink">Communities</h2>
      <div class="space-y-2">
        <?php foreach (array_slice($communities, 0, 5) as $i => $c): ?>
          <?= communityCardHtml($c, $i) ?>
        <?php endforeach; ?>
      </div>
      <a href="communities.php" class="mt-3 block text-sm font-semibold text-pop hover:underline">View all communities →</a>
    </div>
  </aside>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>

