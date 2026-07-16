<?php

session_start();
require __DIR__ . '/includes/db.php';
require __DIR__ . '/includes/helpers.php';

$title = 'Communities';
$ogType = 'website';
$ogDescription = 'Browse all communities on Yapr.';
require __DIR__ . '/includes/header.php';

$query = mysqli_real_escape_string($conn, trim($_GET['q'] ?? ''));

if ($query !== '') {
    $result = mysqli_query($conn, "
        SELECT c.*, (SELECT COUNT(*) FROM community_members WHERE community_id = c.id) AS members_count
        FROM communities c
        WHERE c.name LIKE '%$query%' OR c.description LIKE '%$query%'
        ORDER BY c.name ASC
    ");
} else {
    $result = mysqli_query($conn, "
        SELECT c.*, (SELECT COUNT(*) FROM community_members WHERE community_id = c.id) AS members_count
        FROM communities c ORDER BY c.name ASC
    ");
}
$filtered = mysqli_fetch_all($result, MYSQLI_ASSOC);
$totalMembers = array_sum(array_column($filtered, 'members_count'));
?>
<div class="space-y-6">
  <a href="index.php" class="text-sm text-ink-faint transition hover:text-pop">&larr; Back to feed</a>

  <div class="card reveal overflow-hidden">
    <div aria-hidden="true" class="h-28 animate-gradient-pan bg-[length:200%_200%] bg-gradient-to-r from-pop via-pop-dark to-ink"></div>
    <div class="p-5">
      <h1 class="font-display text-3xl font-bold text-ink">Communities</h1>
      <p class="mt-1 text-sm text-ink-faint">Find conversations that match your interests.</p>
      <div class="mt-3 flex flex-wrap items-center gap-4 text-sm text-ink-soft">
        <span><?= count($filtered) ?> communities</span>
        <span class="text-line">&middot;</span>
        <span><?= number_format($totalMembers) ?> members</span>
      </div>
    </div>
  </div>

  <form action="communities.php" method="get" class="reveal" style="--reveal-delay:60ms">
    <label for="community-search" class="sr-only">Search communities</label>
    <div class="relative max-w-md">
      <svg aria-hidden="true" class="absolute left-3 top-1/2 -translate-y-1/2 text-ink-faint" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-4.3-4.3"/></svg>
      <input type="search" id="community-search" name="q"
             placeholder="Search communities…"
             value="<?= escapeHtml($query) ?>"
             class="field rounded-full pl-9">
    </div>
  </form>

  <?php if (empty($filtered)): ?>
    <div class="card reveal p-12 text-center">
      <p class="font-display text-lg font-semibold text-ink">No results found</p>
      <p class="mt-1 text-sm text-ink-faint">No communities match "<?= escapeHtml($query) ?>". Try a different search.</p>
      <a href="communities.php" class="btn-pop mt-4 inline-flex">Clear search</a>
    </div>
  <?php else: ?>
    <div class="grid gap-4 sm:grid-cols-2">
      <?php foreach ($filtered as $i => $c):
        $name = escapeHtml($c['name']);
        $slug = escapeHtml($c['slug']);
        $desc = escapeHtml($c['description']);
        $members = escapeHtml(number_format(intval($c['members_count'])));
        $initial = escapeHtml(mb_strtoupper(mb_substr($c['name'], 0, 1)));
        $delay = ($i + 1) * 70;
      ?>
        <div class="reveal" style="--reveal-delay:<?= $delay ?>ms">
          <a href="community.php?slug=<?= $slug ?>" class="card group flex gap-4 p-4">
            <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-pop font-display text-xl font-black text-white transition duration-300 group-hover:scale-110"><?= $initial ?></span>
            <div class="min-w-0 flex-1">
              <h2 class="font-display text-lg font-semibold text-ink transition group-hover:text-pop"><?= $name ?></h2>
              <p class="mt-1 line-clamp-2 text-sm leading-relaxed text-ink-faint"><?= $desc ?></p>
              <div class="mt-2 flex items-center gap-2">
                <span class="text-xs font-medium text-ink-soft"><?= $members ?> members</span>
                <button type="button" class="btn-pop !py-1 !px-3 !text-xs">Join</button>
              </div>
            </div>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
