<?php

declare(strict_types=1);

session_start();
require __DIR__ . '/includes/db.php';
require __DIR__ . '/includes/auth.php';
require __DIR__ . '/includes/helpers.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title'] ?? '');
    $body = mysqli_real_escape_string($conn, $_POST['body'] ?? '');
    $communityId = (int) ($_POST['community_id'] ?? 0);
    $userId = (int) $_SESSION['user']['id'];

    if ($title === '' || $body === '' || $communityId === 0) {
        $error = 'All fields are required.';
    } else {
        mysqli_query($conn, "
            INSERT INTO posts (title, body, user_id, community_id)
            VALUES ('$title', '$body', $userId, $communityId)
        ");
        $postId = mysqli_insert_id($conn);
        header("Location: post.php?id=$postId");
        exit();
    }
}

$communitiesResult = mysqli_query($conn, "SELECT id, name FROM communities ORDER BY name ASC");

$title = 'Create post';
require __DIR__ . '/includes/header.php';
?>
<div class="mx-auto max-w-2xl">
  <a href="index.php" class="text-sm text-ink-faint transition hover:text-pop">&larr; Back to feed</a>
  <div class="card animate-fade-in-up mt-4 p-6">
    <h1 class="mb-1 font-display text-2xl font-bold text-ink">Create a post</h1>
    <p class="mb-6 text-sm text-ink-faint">Share something with the community.</p>

    <?php if (isset($error)): ?>
      <p class="mb-4 rounded-xl bg-pop-tint px-4 py-2 text-sm font-medium text-pop"><?= esc($error) ?></p>
    <?php endif; ?>

    <form method="post" class="space-y-4">
      <div>
        <label class="mb-1 block text-sm font-medium text-ink-soft">Community</label>
        <select name="community_id" required class="field">
          <option value="">Choose a community</option>
          <?php while ($c = mysqli_fetch_assoc($communitiesResult)): ?>
            <option value="<?= esc($c['id']) ?>"><?= esc($c['name']) ?></option>
          <?php endwhile; ?>
        </select>
      </div>
      <div>
        <label class="mb-1 block text-sm font-medium text-ink-soft">Title</label>
        <input name="title" type="text" required maxlength="255" class="field" placeholder="What's on your mind?">
      </div>
      <div>
        <label class="mb-1 block text-sm font-medium text-ink-soft">Body</label>
        <textarea name="body" rows="6" required class="field" placeholder="Write something..."></textarea>
      </div>
      <div class="flex items-center gap-3">
        <button type="submit" class="btn-pop">Post</button>
        <a href="index.php" class="btn-ghost">Cancel</a>
      </div>
    </form>
  </div>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
