<?php

session_start();

require __DIR__ . '/includes/db.php';
require __DIR__ . '/includes/auth.php';
require __DIR__ . '/includes/helpers.php';

requireLogin();

$me = $_SESSION['user'];
$id = intval($me['id']);

$userRow = mysqli_query($conn, "
    SELECT username, email, avatar, created_at
    FROM users WHERE id = $id
");
if ($userRow && $u = mysqli_fetch_assoc($userRow)) {
    $me['username']    = $u['username'];
    $me['email']       = $u['email'];
    $me['avatar_url']  = $u['avatar'];
    $me['created_at']  = $u['created_at'];
}

$karmaRes = mysqli_query($conn, "
    SELECT
        (SELECT COALESCE(SUM(score), 0) FROM posts    WHERE user_id = $id) +
        (SELECT COALESCE(SUM(score), 0) FROM comments WHERE user_id = $id) AS karma
");
$karma = 0;
if ($karmaRes && $kRow = mysqli_fetch_assoc($karmaRes)) {
    $karma = intval($kRow['karma']);
}

$communitiesResult = mysqli_query($conn, "
    SELECT c.id, c.name, c.slug, c.description,
           (SELECT COUNT(*) FROM community_members WHERE community_id = c.id) AS members_count
    FROM community_members cm
    JOIN communities c ON cm.community_id = c.id
    WHERE cm.user_id = $id
    ORDER BY cm.joined_at DESC
");

$postsResult = mysqli_query($conn, "
    SELECT p.*, u.username AS author_username,
           c2.name AS community_name, c2.slug AS community_slug,
           (SELECT COUNT(*) FROM comments WHERE post_id = p.id) AS comments_count,
           (SELECT v.value FROM votes v
            WHERE v.user_id = $id AND v.target_id = p.id
              AND v.target_type = 'post') AS my_vote
    FROM posts p
    JOIN users u ON p.user_id = u.id
    JOIN communities c2 ON p.community_id = c2.id
    WHERE p.user_id = $id
    ORDER BY p.created_at DESC
");

$commentsResult = mysqli_query($conn, "
    SELECT c.id, c.body, c.score, c.created_at,
           p.id AS post_id, p.title AS post_title,
           c2.name AS community_name, c2.slug AS community_slug,
           (SELECT v.value FROM votes v
            WHERE v.user_id = $id AND v.target_id = c.id
              AND v.target_type = 'comment') AS my_vote
    FROM comments c
    JOIN posts p ON c.post_id = p.id
    JOIN communities c2 ON p.community_id = c2.id
    WHERE c.user_id = $id
    ORDER BY c.created_at DESC
");

$title = 'My account';
$noSidebar = true;
$ogType = 'website';
$ogDescription = 'Your account settings and activity on Yapr.';

require __DIR__ . '/includes/header.php';
?>

<div class="space-y-6">

  <a href="index.php"
     class="inline-flex items-center gap-1.5 text-sm text-ink-faint transition hover:text-pop">
    <span aria-hidden="true">&larr;</span> Back to feed
  </a>

  <header class="space-y-1">
    <h1 class="font-display text-2xl font-bold tracking-tight text-ink">My account</h1>
    <p class="max-w-[60ch] text-sm text-ink-faint">
      Manage your profile and review your activity. Only you can see this page.
    </p>
  </header>

  <div class="flex flex-col gap-6 lg:grid lg:grid-cols-[200px_1fr]">

    <aside class="z-10 -mx-6 bg-night lg:sticky lg:top-20 lg:self-start lg:mx-0 lg:bg-transparent">
      <nav id="tab-bar" aria-label="Account sections"
           class="grid grid-cols-4 border-b border-line px-4 lg:flex lg:flex-col lg:gap-2 lg:border-b-0 lg:px-0">
        <button type="button" data-tab="overview"
                class="tab-pill is-active flex items-center justify-center gap-2.5 px-2 py-2.5 text-xs font-medium transition lg:rounded-lg lg:px-3 lg:text-sm">
          <span class="dot hidden h-1.5 w-1.5 shrink-0 rounded-full lg:inline-block" aria-hidden="true"></span>
          Overview
        </button>
        <button type="button" data-tab="comments"
                class="tab-pill flex items-center justify-center gap-2.5 px-2 py-2.5 text-xs font-medium transition lg:rounded-lg lg:px-3 lg:text-sm">
          <span class="dot hidden h-1.5 w-1.5 shrink-0 rounded-full lg:inline-block" aria-hidden="true"></span>
          Comments
        </button>
        <button type="button" data-tab="posts"
                class="tab-pill flex items-center justify-center gap-2.5 px-2 py-2.5 text-xs font-medium transition lg:rounded-lg lg:px-3 lg:text-sm">
          <span class="dot hidden h-1.5 w-1.5 shrink-0 rounded-full lg:inline-block" aria-hidden="true"></span>
          Posts
        </button>
        <button type="button" data-tab="communities"
                class="tab-pill flex items-center justify-center gap-2.5 px-2 py-2.5 text-xs font-medium transition lg:rounded-lg lg:px-3 lg:text-sm">
          <span class="dot hidden h-1.5 w-1.5 shrink-0 rounded-full lg:inline-block" aria-hidden="true"></span>
          Communities
        </button>
      </nav>
    </aside>

    <div class="min-w-0 space-y-4">

      <section data-panel="overview" class="space-y-4">
        <div class="card flex items-center gap-4 p-5">
          <?= avatarHtml($me, 14) ?>
          <div class="min-w-0">
            <h2 class="truncate font-display text-xl font-bold text-ink">
              <?= escapeHtml($me['username']) ?>
            </h2>
            <p class="truncate text-sm text-ink-faint">
              <?= escapeHtml($me['email'] ?? '') ?>
            </p>
          </div>
        </div>

        <div class="card divide-y divide-line">
          <div class="grid grid-cols-3 gap-4 px-5 py-4">
            <div class="text-sm font-medium text-ink-faint">Display name</div>
            <div class="col-span-2 text-sm text-ink"><?= escapeHtml($me['username']) ?></div>
          </div>
          <div class="grid grid-cols-3 gap-4 px-5 py-4">
            <div class="text-sm font-medium text-ink-faint">Email</div>
            <div class="col-span-2 truncate text-sm text-ink">
              <?= escapeHtml($me['email'] ?? 'Not set') ?>
            </div>
          </div>
          <div class="grid grid-cols-3 gap-4 px-5 py-4">
            <div class="text-sm font-medium text-ink-faint">Karma</div>
            <div class="col-span-2 font-display text-sm font-semibold text-ink"><?= escapeHtml($karma) ?></div>
          </div>
          <div class="grid grid-cols-3 gap-4 px-5 py-4">
            <div class="text-sm font-medium text-ink-faint">Member since</div>
            <div class="col-span-2 text-sm text-ink">
              <?= escapeHtml(date('M j, Y', strtotime($me['created_at'] ?? 'now'))) ?>
            </div>
          </div>
        </div>

        <div class="card p-5">
          <h3 class="font-display text-sm font-semibold text-ink">Account actions</h3>
          <div class="mt-3 flex flex-wrap gap-2">
            <a href="index.php"
               class="pill bg-night-top text-ink-soft transition hover:bg-pop hover:text-white">
              View the feed
            </a>
            <form action="logout.php" method="post" class="inline">
              <button type="submit"
                      class="pill border border-line bg-night-soft text-ink-soft transition hover:border-pop hover:text-pop">
                Sign out
              </button>
            </form>
          </div>
        </div>
      </section>

      <section data-panel="comments" class="hidden space-y-3">
        <?php
        $commentIndex = 0;
        while ($c = mysqli_fetch_assoc($commentsResult)):
            $commentIndex++;
        ?>
          <div class="card flex flex-col gap-1 p-4">
            <a href="post.php?id=<?= intval($c['post_id']) ?>"
               class="font-display text-sm font-semibold text-ink hover:text-pop">
              <?= escapeHtml($c['post_title']) ?>
            </a>
            <p class="line-clamp-3 text-sm leading-relaxed text-ink-soft">
              <?= escapeHtml($c['body']) ?>
            </p>
            <div class="flex flex-wrap items-center gap-2 text-xs text-ink-faint">
              <a href="community.php?slug=<?= escapeHtml($c['community_slug']) ?>"
                 class="hover:text-pop"><?= escapeHtml($c['community_name']) ?></a>
              <span aria-hidden="true">&middot;</span>
              <span><?= escapeHtml(timeAgo($c['created_at'])) ?></span>
              <span aria-hidden="true">&middot;</span>
              <span><?= escapeHtml($c['score']) ?> points</span>
            </div>
          </div>
        <?php endwhile; ?>
        <?php if ($commentIndex === 0): ?>
          <div class="rounded-xl border border-dashed border-line p-8 text-center text-ink-faint">
            You haven&rsquo;t commented yet.
          </div>
        <?php endif; ?>
      </section>

      <section data-panel="posts" class="hidden space-y-3">
        <?php
        $postIndex = 0;
        while ($row = mysqli_fetch_assoc($postsResult)):
            $postIndex++;
            $row['author'] = ['username' => $row['author_username']];
            $row['community'] = $row['community_name'];
            $row['community_slug'] = $row['community_slug'];
            $row['time'] = timeAgo($row['created_at']);
            $row['author_id'] = $row['user_id'];
            echo postCardHtml($row);
        endwhile;
        ?>
        <?php if ($postIndex === 0): ?>
          <div class="rounded-xl border border-dashed border-line p-8 text-center text-ink-faint">
            You haven&rsquo;t posted yet.
          </div>
        <?php endif; ?>
      </section>

      <section data-panel="communities" class="hidden space-y-3">
        <?php
        $communityIndex = 0;
        while ($c = mysqli_fetch_assoc($communitiesResult)):
            $communityIndex++;
            echo communityCardHtml($c, $communityIndex);
        endwhile;
        ?>
        <?php if ($communityIndex === 0): ?>
          <div class="rounded-xl border border-dashed border-line p-8 text-center text-ink-faint">
            You haven&rsquo;t joined any communities.
          </div>
        <?php endif; ?>
      </section>

    </div>
  </div>
</div>

<script src="/ForumHub/public/js/profile-tabs.js"></script>

<?php require __DIR__ . '/includes/footer.php'; ?>