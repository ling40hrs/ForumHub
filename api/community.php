<?php

session_start();

require __DIR__ . '/includes/db.php';
require __DIR__ . '/includes/helpers.php';

$userId = intval($_SESSION['user']['id'] ?? 0);

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

$currentUserId = intval($_SESSION['user']['id'] ?? 0);
$isMember = $currentUserId
    ? isCommunityMember($conn, $currentUserId, intval($community['id']))
    : false;

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) {
    $page = 1;
}
$perPage = 10;
$offset = ($page - 1) * $perPage;

$countResult = mysqli_query($conn, "SELECT COUNT(*) AS total FROM posts WHERE community_id = {$community['id']}");
$total = intval(mysqli_fetch_assoc($countResult)['total']);
$totalPages = (int) ceil($total / $perPage);
if ($totalPages > 0 && $page > $totalPages) {
    $page = $totalPages;
    $offset = ($page - 1) * $perPage;
}

$postsResult = mysqli_query($conn, "
    SELECT p.*, u.username AS author_username,
           (SELECT COUNT(*) FROM comments WHERE post_id = p.id) AS comments_count,
           (SELECT v.value FROM votes v
            WHERE v.user_id = $userId AND v.target_id = p.id AND v.target_type = 'post') AS my_vote
    FROM posts p
    JOIN users u ON p.user_id = u.id
    WHERE p.community_id = {$community['id']}
    ORDER BY p.created_at DESC
    LIMIT $offset, $perPage
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
    <div aria-hidden="true" class="relative h-24 overflow-hidden rounded-t-2xl bg-night">
      <div aria-hidden="true" class="absolute inset-0 animate-gradient-pan bg-[length:200%_200%] bg-gradient-to-r from-pop via-pop-dark to-ink"></div>
      <?php if (!empty($community['background_url'])): ?>
      <img src="<?= escapeHtml($community['background_url']) ?>" alt=""
           class="absolute inset-0 h-full w-full object-cover opacity-0 transition-opacity duration-700"
           onload="this.style.opacity='1'" onerror="this.style.display='none'"
           loading="lazy" fetchpriority="high">
      <?php endif; ?>
      <div aria-hidden="true" class="absolute inset-0 bg-gradient-to-t from-night/80 to-night/20"></div>
    </div>
    <div class="p-4">
      <h1 class="font-display text-2xl font-bold text-ink"><?= escapeHtml($community['name']) ?></h1>
      <p class="mt-1 text-sm text-ink-faint"><?= escapeHtml($community['description']) ?></p>
      <div class="mt-3 flex items-center gap-3">
        <span class="text-sm text-ink-faint"><?= escapeHtml($community['members_count']) ?> members</span>
        <?php if (isset($_SESSION['user'])): ?>
        <form action="join-community-handler.php" method="post" class="inline">
          <input type="hidden" name="community_id" value="<?= intval($community['id']) ?>">
          <button type="submit" class="btn-pop"><?= $isMember ? 'Leave' : 'Join' ?></button>
        </form>
        <?php else: ?>
          <a href="login.php" class="btn-pop">Join</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <section class="space-y-4">
    <?php foreach ($communityPosts as $post): ?>
      <?= postCardHtml($post) ?>
    <?php endforeach; ?>
    <?php if (empty($communityPosts)): ?>
      <p class="rounded-xl border border-dashed border-line p-8 text-center text-ink-faint">No posts yet.</p>
    <?php endif; ?>
    <?= paginationHtml($page, $totalPages, 'community.php?slug=' . escapeHtml($community['slug'])) ?>
  </section>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
