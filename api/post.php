<?php

session_start();

require __DIR__ . '/includes/db.php';
require __DIR__ . '/includes/helpers.php';

$userId = intval($_SESSION['user']['id'] ?? 0);

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
} else {
    $id = 0;
}

$perPage = 10;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user'])) {
    if (isset($_POST['body'])) {
        $body = mysqli_real_escape_string($conn, $_POST['body']);
    } else {
        $body = '';
    }

    $userId = intval($_SESSION['user']['id']);

    if (isset($_POST['parent_id']) && $_POST['parent_id'] !== '') {
        $parentId = intval($_POST['parent_id']);
    } else {
        $parentId = 0;
    }

    if ($body !== '') {
        $tooDeep = false;
        if ($parentId > 0) {
            $parentDepth = commentDepth($conn, $parentId);
            $tooDeep = ($parentDepth >= 5);
        }

        if ($tooDeep) {
            $commentError = 'Maximum nesting depth is 5 replies.';
        } else {
            $parentSql = $parentId > 0 ? $parentId : 'NULL';
            mysqli_query($conn, "INSERT INTO comments (body, user_id, post_id, parent_id)
                                 VALUES ('$body', $userId, $id, $parentSql)");
            $countResult = mysqli_query($conn, "SELECT COUNT(*) AS total FROM comments WHERE post_id = $id");
            $total = intval(mysqli_fetch_assoc($countResult)['total']);
            $lastPage = (int) ceil($total / $perPage);
            header("Location: post.php?id=$id&page=$lastPage");
            exit();
        }
    }
}

$postResult = mysqli_query($conn, "
    SELECT p.*, u.username AS author_username,
           c.name AS community_name, c.slug AS community_slug,
           (SELECT COUNT(*) FROM comments WHERE post_id = p.id) AS comments_count,
           (SELECT v.value FROM votes v
            WHERE v.user_id = $userId AND v.target_id = p.id AND v.target_type = 'post') AS my_vote
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
$myVote = intval($post['my_vote'] ?? 0);

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) {
    $page = 1;
}
$totalPages = (int) ceil(intval($post['comments_count']) / $perPage);
if ($totalPages > 0 && $page > $totalPages) {
    $page = $totalPages;
}
$offset = ($page - 1) * $perPage;

$commentsResult = mysqli_query($conn, "
    SELECT c.*, u.username AS author_username, u.avatar AS author_avatar_url,
           (SELECT v.value FROM votes v
            WHERE v.user_id = $userId AND v.target_id = c.id AND v.target_type = 'comment') AS my_vote
    FROM comments c
    JOIN users u ON c.user_id = u.id
    WHERE c.post_id = $id
    ORDER BY c.created_at ASC
    LIMIT $offset, $perPage
");

$postComments = [];

while ($row = mysqli_fetch_assoc($commentsResult)) {
    $row['author'] = ['username' => $row['author_username'], 'avatar_url' => $row['author_avatar_url']];
    $row['time'] = timeAgo($row['created_at']);
    $postComments[] = $row;
}

$topLevel = [];
$repliesByParent = [];

$idsOnPage = [];
foreach ($postComments as $c) {
    $idsOnPage[$c['id']] = true;
}

foreach ($postComments as $c) {
    $parent = $c['parent_id'];  // NULL for a top-level comment

    if ($parent === null || !isset($idsOnPage[$parent])) {
        $topLevel[] = $c;
    } else {
        $repliesByParent[$parent][] = $c;  // a reply to a comment on this page
    }
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
    <form class="vote-rail" action="vote-handler.php" method="post">
      <input type="hidden" name="target_id" value="<?= $id ?>">
      <input type="hidden" name="target_type" value="post">
      <button type="submit" name="vote" value="1" class="vote-btn is-up<?= $myVote === 1 ? ' is-active' : '' ?>" aria-label="Upvote">▲</button>
      <span class="vote-score"><?= escapeHtml($post['score']) ?></span>
      <button type="submit" name="vote" value="-1" class="vote-btn is-down<?= $myVote === -1 ? ' is-active' : '' ?>" aria-label="Downvote">▼</button>
    </form>
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
    <h2 id="add-comment" class="mb-3 font-display font-semibold text-ink">Add a comment</h2>
    <?php if (isset($_SESSION['user'])): ?>
    <?php if (isset($commentError)): ?>
      <p class="mb-3 text-sm text-red-400"><?= escapeHtml($commentError) ?></p>
    <?php endif; ?>
    <form action="post.php?id=<?= $id ?>" method="post" class="space-y-3" onsubmit="this.querySelector('button').disabled=true">
      <input type="hidden" name="parent_id" value="<?= escapeHtml($_GET['reply_to'] ?? '') ?>">
      <?php if (isset($_GET['reply_to'])): ?>
        <p class="text-xs text-ink-faint">Replying to a comment · <a href="post.php?id=<?= $id ?>" class="text-pop hover:underline">cancel</a></p>
      <?php endif; ?>
      <textarea name="body" rows="3" placeholder="What are your thoughts?"
                class="field"></textarea>
      <button type="submit" class="btn-pop">Comment</button>
    </form>
    <?php else: ?>
      <p class="text-sm text-ink-faint"><a href="login.php" class="text-pop hover:underline">Log in</a> to comment.</p>
    <?php endif; ?>
    <div class="mt-5 divide-y divide-line">
      <?php foreach ($topLevel as $c): ?>
        <?= commentTreeHtml($c, $repliesByParent, $id) ?>
      <?php endforeach; ?>
      <?php if (empty($postComments)): ?>
        <p class="py-4 text-sm text-ink-faint">No comments yet. Be the first!</p>
      <?php endif; ?>
    </div>
    <?= paginationHtml($page, $totalPages, 'post.php?id=' . $id) ?>
  </section>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
