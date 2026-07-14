<?php
require __DIR__ . '/includes/sample-data.php';
require __DIR__ . '/includes/helpers.php';

$id = (int) ($_GET['id'] ?? $posts[0]['id']);
$post = null;
foreach ($posts as $p) {
    if ((int) $p['id'] === $id) { $post = $p; break; }
}
if ($post === null) {
    http_response_code(404);
    $post = $posts[0];
}

$postComments = array_filter($comments, fn($c) => ($c['post_id'] ?? 0) === $id);
$title = $post['title'];
$ogType = 'article';
$ogDescription = mb_substr($post['body'] ?? '', 0, 200);
require __DIR__ . '/includes/header.php';
?>
<div class="space-y-6">
  <a href="index.php" class="text-sm text-ink-faint transition hover:text-pop">← Back to feed</a>
  <article class="card flex animate-fade-in-up overflow-hidden">
    <div class="vote-rail" data-score="<?= esc($post['score']) ?>">
      <button type="button" class="vote-btn is-up" aria-label="Upvote">▲</button>
      <span class="vote-score"><?= esc($post['score']) ?></span>
      <button type="button" class="vote-btn is-down" aria-label="Downvote">▼</button>
    </div>
    <div class="min-w-0 flex-1 p-5">
      <div class="mb-2 flex flex-wrap items-center gap-2 text-sm text-ink-faint">
        <span class="font-display font-semibold text-pop"><?= esc($post['community']) ?></span>
        <span>·</span><span>Posted by <?= esc($post['author']['username']) ?></span>
        <span>·</span><span><?= esc($post['time']) ?></span>
      </div>
      <h1 class="font-display text-2xl font-bold leading-tight text-ink"><?= esc($post['title']) ?></h1>
      <p class="mt-3 whitespace-pre-line text-ink-soft"><?= esc($post['body']) ?></p>
      <div class="mt-4 flex items-center gap-4 text-sm text-ink-faint">
        <span class="flex items-center gap-1 rounded-full bg-night-raised px-3 py-1">▲ <?= esc($post['score']) ?></span>
        <span>💬 <?= esc($post['comments_count']) ?> comments</span>
      </div>
    </div>
  </article>
  <section class="card animate-fade-in-up p-5">
    <h2 class="mb-3 font-display font-semibold text-ink">Add a comment</h2>
    <form action="post.php?id=<?= $id ?>" method="post" class="space-y-3">
      <textarea name="body" rows="3" placeholder="What are your thoughts?"
                class="field"></textarea>
      <button class="btn-pop">Comment</button>
    </form>
    <div class="mt-5 divide-y divide-line">
      <?php foreach ($postComments as $c): ?>
        <?= commentItemHtml($c) ?>
      <?php endforeach; ?>
      <?php if (empty($postComments)): ?>
        <p class="py-4 text-sm text-ink-faint">No comments yet. Be the first!</p>
      <?php endif; ?>
    </div>
  </section>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>

