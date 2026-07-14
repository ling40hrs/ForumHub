<?php

declare(strict_types=1);

require __DIR__ . '/includes/sample-data.php';
require __DIR__ . '/includes/helpers.php';

$id = (int) ($_GET['id'] ?? $currentUser['id']);
$viewed = ($id === (int) $currentUser['id']) ? $currentUser : $profileUser;
$userPosts = array_filter($posts, fn($p) => ($p['author_id'] ?? 0) === $id);
$title = $viewed['username'];
$ogType = 'profile';
$ogDescription = ($viewed['bio'] ?? '') ?: $viewed['username'] . ' on Yapr';
require __DIR__ . '/includes/header.php';
?>
<div class="space-y-6">
  <a href="index.php" class="text-sm text-ink-faint transition hover:text-pop">← Back to feed</a>
  <div class="card animate-fade-in-up flex items-center gap-4 p-5">
    <?= avatarHtml($viewed, 16) ?>
    <div>
      <h1 class="font-display text-2xl font-bold text-ink"><?= esc($viewed['username']) ?></h1>
      <p class="text-sm text-ink-faint"><?= esc($viewed['karma']) ?> karma</p>
    </div>
  </div>
  <p class="card -mt-3 p-5 text-ink-soft"><?= esc($viewed['bio'] ?? '') ?></p>
  <section class="space-y-4">
    <h2 class="font-display text-lg font-semibold text-ink">Posts</h2>
    <?php foreach (array_values($userPosts) as $i => $post): ?>
      <?= postCardHtml($post, $i) ?>
    <?php endforeach; ?>
    <?php if (empty($userPosts)): ?>
      <p class="rounded-xl border border-dashed border-line p-8 text-center text-ink-faint">No posts yet.</p>
    <?php endif; ?>
  </section>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>

