<?php

declare(strict_types=1);

require __DIR__ . '/includes/sample-data.php';
require __DIR__ . '/includes/helpers.php';

$slug = $_GET['slug'] ?? $communities[0]['slug'];
$community = null;
foreach ($communities as $c) {
    if ($c['slug'] === $slug) { $community = $c; break; }
}
if ($community === null) {
    http_response_code(404);
    $community = $communities[0];
}

$communityPosts = array_filter($posts, fn($p) => ($p['community_slug'] ?? '') === $slug);
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
      <h1 class="font-display text-2xl font-bold text-ink"><?= esc($community['name']) ?></h1>
      <p class="mt-1 text-sm text-ink-faint"><?= esc($community['description']) ?></p>
      <div class="mt-3 flex items-center gap-3">
        <span class="text-sm text-ink-faint"><?= esc($community['members_count']) ?> members</span>
        <button type="button" class="btn-pop">Join</button>
      </div>
    </div>
  </div>
  <section class="space-y-4">
    <?php foreach (array_values($communityPosts) as $i => $post): ?>
      <?= postCardHtml($post, $i) ?>
    <?php endforeach; ?>
    <?php if (empty($communityPosts)): ?>
      <p class="rounded-xl border border-dashed border-line p-8 text-center text-ink-faint">No posts yet.</p>
    <?php endif; ?>
  </section>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>

