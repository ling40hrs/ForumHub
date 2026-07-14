<?php

declare(strict_types=1);

require __DIR__ . '/includes/sample-data.php';
require __DIR__ . '/includes/helpers.php';
$title = 'Home';
$ogType = 'website';
$ogDescription = 'Yapr — a community where people yap about what they love.';
require __DIR__ . '/includes/header.php';
?>
<div class="grid gap-6 md:grid-cols-3">
  <section class="space-y-4 md:col-span-2">
    <div class="flex items-center justify-between">
      <h1 class="font-display text-2xl font-bold text-ink">Popular posts</h1>
      <a href="register.php" class="btn-primary">New post</a>
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
      <a href="community.php?slug=<?= esc($communities[0]['slug']) ?>" class="mt-3 block text-sm font-semibold text-pop hover:underline">View all communities →</a>
    </div>
  </aside>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>

