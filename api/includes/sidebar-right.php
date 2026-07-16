<?php

$currentUserId = intval($_SESSION['user']['id'] ?? 0);

if ($currentUserId > 0) {
    $userStatsRes = mysqli_query($conn, "
        SELECT
            (SELECT COALESCE(SUM(score), 0) FROM posts    WHERE user_id = $currentUserId) +
            (SELECT COALESCE(SUM(score), 0) FROM comments WHERE user_id = $currentUserId) AS karma,
            (SELECT COUNT(*) FROM posts    WHERE user_id = $currentUserId) AS user_posts,
            (SELECT COUNT(*) FROM comments WHERE user_id = $currentUserId) AS user_comments,
            (SELECT COUNT(*) FROM community_members WHERE user_id = $currentUserId) AS user_communities
    ");
    $us = mysqli_fetch_assoc($userStatsRes);
    $karma = intval($us['karma'] ?? 0);
    $userPosts = intval($us['user_posts'] ?? 0);
    $userComments = intval($us['user_comments'] ?? 0);
    $userCommunities = intval($us['user_communities'] ?? 0);
}
?>

<?php if ($currentUserId > 0): ?>
  <div class="card p-4">
    <a href="profile.php" class="flex items-center gap-3">
      <?= avatarHtml($_SESSION['user'], 10) ?>
      <div class="min-w-0">
        <p class="truncate font-display font-semibold text-ink transition hover:text-pop"><?= escapeHtml($_SESSION['user']['username']) ?></p>
        <p class="text-sm text-ink-faint"><?= $karma ?> karma</p>
      </div>
    </a>
    <div class="mt-3 flex flex-wrap gap-2">
      <a href="profile.php" class="pill border border-line text-ink-soft text-xs transition hover:border-pop hover:text-pop">Profile</a>
      <a href="logout.php" class="pill border border-line text-ink-soft text-xs transition hover:border-pop hover:text-pop">Log out</a>
    </div>
  </div>

  <div class="card p-4">
    <h3 class="mb-3 font-display text-sm font-semibold text-ink">Your Stats</h3>
    <div class="space-y-2 text-sm">
      <a href="profile.php#posts" class="flex items-center justify-between rounded-lg px-2 py-1 -mx-2 transition hover:bg-night-raised">
        <span class="text-ink-faint">Posts</span>
        <span class="font-display font-semibold text-ink"><?= number_format($userPosts) ?></span>
      </a>
      <a href="profile.php#comments" class="flex items-center justify-between rounded-lg px-2 py-1 -mx-2 transition hover:bg-night-raised">
        <span class="text-ink-faint">Comments</span>
        <span class="font-display font-semibold text-ink"><?= number_format($userComments) ?></span>
      </a>
      <a href="profile.php#communities" class="flex items-center justify-between rounded-lg px-2 py-1 -mx-2 transition hover:bg-night-raised">
        <span class="text-ink-faint">Communities</span>
        <span class="font-display font-semibold text-ink"><?= number_format($userCommunities) ?></span>
      </a>
    </div>
  </div>
<?php else: ?>
  <div class="card p-4">
    <p class="font-display font-semibold text-ink">Welcome to Yapr</p>
    <p class="mt-1 text-sm text-ink-faint">Join the conversation.</p>
    <div class="mt-3 flex gap-2">
      <a href="login.php" class="btn-primary flex-1 text-center text-sm">Log in</a>
      <a href="register.php" class="rounded-full border border-line px-4 py-2 text-sm font-medium text-ink-soft transition hover:bg-night-raised hover:text-ink flex-1 text-center">Sign up</a>
    </div>
  </div>
<?php endif; ?>
