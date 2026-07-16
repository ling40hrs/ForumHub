<?php

$currentUserId = intval($_SESSION['user']['id'] ?? 0);
$currentPage = basename($_SERVER['SCRIPT_NAME']);

function navLinkClass(string $currentPage, string $page): string {
    return $currentPage === $page
        ? 'flex items-center gap-3 rounded-lg bg-night-raised px-3 py-2 text-sm font-semibold text-pop'
        : 'flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-ink-soft transition hover:bg-night-raised hover:text-ink';
}
?>

<nav class="space-y-1">
  <a href="index.php" class="<?= navLinkClass($currentPage, 'index.php') ?>">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9,22 9,12 15,12 15,22"/></svg>
    Home
  </a>

  <?php if ($currentUserId > 0): ?>
    <a href="create-post.php" class="<?= navLinkClass($currentPage, 'create-post.php') ?>">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
      New Post
    </a>
  <?php endif; ?>

  <a href="communities.php" class="<?= navLinkClass($currentPage, 'communities.php') ?>">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
    Communities
  </a>
</nav>

<?php if ($currentUserId > 0): ?>
  <?php
  $myCommunities = [];
  $myResult = mysqli_query($conn, "
      SELECT c.name, c.slug,
             (SELECT COUNT(*) FROM community_members WHERE community_id = c.id) AS members_count
      FROM community_members cm
      JOIN communities c ON cm.community_id = c.id
      WHERE cm.user_id = $currentUserId
      ORDER BY c.name ASC
  ");
  while ($row = mysqli_fetch_assoc($myResult)) {
      $myCommunities[] = $row;
  }
  ?>
  <?php if (!empty($myCommunities)): ?>
    <hr class="my-3 border-line">
    <p class="px-3 text-xs font-semibold uppercase tracking-wider text-ink-faint">Your Communities</p>
    <div class="mt-2 space-y-0.5">
      <?php foreach ($myCommunities as $c): ?>
        <a href="community.php?slug=<?= escapeHtml($c['slug']) ?>"
           class="block truncate rounded-lg px-3 py-1.5 text-sm text-ink-soft transition hover:bg-night-raised hover:text-ink">
          <?= escapeHtml($c['name']) ?>
        </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
<?php endif; ?>

<hr class="my-3 border-line">
<p class="px-3 text-xs font-semibold uppercase tracking-wider text-ink-faint">Popular</p>
<div class="mt-2 space-y-0.5">
  <?php
  $popularResult = mysqli_query($conn, "
      SELECT c.slug, c.name,
             (SELECT COUNT(*) FROM community_members WHERE community_id = c.id) AS members_count
      FROM communities c
      ORDER BY members_count DESC
      LIMIT 5
  ");
  while ($pop = mysqli_fetch_assoc($popularResult)):
  ?>
    <a href="community.php?slug=<?= escapeHtml($pop['slug']) ?>"
       class="block truncate rounded-lg px-3 py-1.5 text-sm text-ink-soft transition hover:bg-night-raised hover:text-ink">
      <?= escapeHtml($pop['name']) ?>
    </a>
  <?php endwhile; ?>
</div>


