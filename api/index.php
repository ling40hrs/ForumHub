<?php

session_start();

require __DIR__ . '/includes/db.php';
require __DIR__ . '/includes/helpers.php';

$userId = intval($_SESSION['user']['id'] ?? 0);

$endlessScroll = 0;
if ($userId > 0) {
    $result = mysqli_query($conn, "SELECT endless_scroll FROM users WHERE id = $userId");
    if ($result && $row = mysqli_fetch_assoc($result)) {
        $endlessScroll = intval($row['endless_scroll']);
    }
}

if (isset($_GET['sort'])) {
    $sort = $_GET['sort'];
} else {
    $sort = 'new';
}

if ($sort === 'top') {
    $orderBy = 'p.score DESC, p.created_at DESC';
} else {
    $orderBy = 'p.created_at DESC';
}

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) {
    $page = 1;
}
$perPage = 10;
$offset = ($page - 1) * $perPage;

$countResult = mysqli_query($conn, "SELECT COUNT(*) AS total FROM posts");
$total = intval(mysqli_fetch_assoc($countResult)['total']);
$totalPages = (int) ceil($total / $perPage);
if ($totalPages > 0 && $page > $totalPages) {
    $page = $totalPages;
    $offset = ($page - 1) * $perPage;
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
    $row['time'] = timeAgo($row['created_at']);
    $posts[] = $row;
}

$title = 'Home';
$ogType = 'website';
$ogDescription = 'Yapr — a community where people yap about what they love.';

require __DIR__ . '/includes/header.php';
?>
<section class="space-y-4" id="feed-posts">
    <div class="flex items-center justify-between">
      <div class="flex flex-wrap items-center gap-x-3 gap-y-1">
        <h1 class="font-display text-2xl font-bold text-ink">Posts</h1>
        <div class="flex items-center gap-1 text-sm font-medium">
          <a href="?sort=new"
             class="<?= $sort === 'new' ? 'text-pop' : 'text-ink-faint' ?> transition hover:text-ink">New</a>
          <span class="text-line">·</span>
          <a href="?sort=top"
             class="<?= $sort === 'top' ? 'text-pop' : 'text-ink-faint' ?> transition hover:text-ink">Top</a>
        </div>
      </div>
      <?php if (isset($_SESSION['user'])): ?>
        <button id="endless-toggle" data-state="<?= $endlessScroll ?>" type="button"
                class="pill border border-line text-ink-soft transition hover:border-pop hover:text-pop">
          ∞ Scroll
        </button>
      <?php endif; ?>
    </div>
    <?php foreach ($posts as $post): ?>
      <?= postCardHtml($post) ?>
    <?php endforeach; ?>
    <div id="pagination-container"<?= $endlessScroll ? ' class="hidden"' : '' ?>>
      <?= paginationHtml($page, $totalPages, 'index.php?sort=' . escapeHtml($sort)) ?>
    </div>
    <div id="scroll-sentinel" class="py-4 text-center text-sm text-ink-faint" data-page="<?= $page + 1 ?>"></div>
</section>
<script>
window.Yapr = { sort: <?= json_encode($sort) ?>, base: <?= json_encode(dirname($_SERVER['SCRIPT_NAME']) . '/') ?> };
</script>
<script src="/ForumHub/public/js/endless-scroll.js"></script>
<?php require __DIR__ . '/includes/footer.php'; ?>
