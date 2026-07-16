<?php

session_start();

require __DIR__ . '/includes/db.php';
require __DIR__ . '/includes/helpers.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
} else {
    $id = 0;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user'])) {
    if (isset($_POST['body'])) {
        $body = mysqli_real_escape_string($conn, $_POST['body']);
    } else {
        $body = '';
    }

    $userId = intval($_SESSION['user']['id']);

    if ($body !== '') {
        mysqli_query($conn, "INSERT INTO comments (body, user_id, post_id)
                             VALUES ('$body', $userId, $id)");
        header("Location: post.php?id=$id");
        exit();
    }
}

$postResult = mysqli_query($conn, "
    SELECT p.*, u.username AS author_username,
           c.name AS community_name, c.slug AS community_slug,
           (SELECT COUNT(*) FROM comments WHERE post_id = p.id) AS comments_count
    FROM posts p
    JOIN users u ON p.user_id = u.id
    JOIN communities c ON p.community_id = c.id
    WHERE p.id = $id
");

if (mysqli_num_rows($postResult) == 0) {
    http_response_code(404);
    $title = 'Not found';
    require __DIR__ . '/includes/header.php';
    echo '<div class="card p-8 text-center"><p class="text-ink-faint">Post not found.</p></div>';
    require __DIR__ . '/includes/footer.php';
    exit();
}

$post = mysqli_fetch_assoc($postResult);

$post['author'] = ['username' => $post['author_username']];
$post['community'] = $post['community_name'];
$post['time'] = timeAgo($post['created_at']);

$commentsResult = mysqli_query($conn, "
    SELECT c.*, u.username AS author_username
    FROM comments c
    JOIN users u ON c.user_id = u.id
    WHERE c.post_id = $id
    ORDER BY c.created_at ASC
");

$postComments = [];

while ($row = mysqli_fetch_assoc($commentsResult)) {
    $row['author'] = ['username' => $row['author_username']];
    $row['time'] = timeAgo($row['created_at']);
    $postComments[] = $row;
}

$title = $post['title'];
$ogType = 'article';

if (isset($post['body'])) {
    $ogDescription = mb_substr($post['body'], 0, 200);
} else {
    $ogDescription = '';
}

require __DIR__ . '/includes/header.php';
?>
<div class="space-y-6">
  <div class="progress-bar" aria-hidden="true"></div>
  <a href="index.php" class="text-sm text-ink-faint transition hover:text-pop">← Back to feed</a>
  <article class="card flex reveal overflow-hidden">
    <div class="vote-rail" data-score="<?= escapeHtml($post['score']) ?>">
      <button type="button" class="vote-btn is-up" aria-label="Upvote">▲</button>
      <span class="vote-score"><?= escapeHtml($post['score']) ?></span>
      <button type="button" class="vote-btn is-down" aria-label="Downvote">▼</button>
    </div>
    <div class="min-w-0 flex-1 p-5">
      <div class="mb-2 flex flex-wrap items-center gap-2 text-sm text-ink-faint">
        <span class="font-display font-semibold text-pop"><?= escapeHtml($post['community']) ?></span>
        <span>·</span><span>Posted by <?= escapeHtml($post['author']['username']) ?></span>
        <span>·</span><span><?= escapeHtml($post['time']) ?></span>
      </div>
      <h1 class="font-display text-2xl font-bold leading-tight text-ink"><?= escapeHtml($post['title']) ?></h1>
      <p class="mt-3 whitespace-pre-line text-ink-soft"><?= escapeHtml($post['body']) ?></p>
      <div class="mt-4 flex items-center gap-4 text-sm text-ink-faint">
        <span class="flex items-center gap-1 rounded-full bg-night-raised px-3 py-1">▲ <?= escapeHtml($post['score']) ?></span>
        <span>💬 <?= escapeHtml($post['comments_count']) ?> comments</span>
      </div>
    </div>
  </article>
  <section class="card reveal p-5">
    <h2 class="mb-3 font-display font-semibold text-ink">Add a comment</h2>
    <form action="post.php?id=<?= $id ?>" method="post" class="space-y-3">
      <textarea name="body" rows="3" placeholder="What are your thoughts?"
                class="field"></textarea>
      <button type="submit" class="btn-pop">Comment</button>
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
