<?php

$currentUserId = intval($_SESSION['user']['id'] ?? 0);
$currentPage   = basename($_SERVER['SCRIPT_NAME']);

function tabActive(string $current, string $page): string {
    return $current === $page
        ? 'text-pop'
        : 'text-ink-faint';
}
?>
<nav aria-label="Primary"
     class="fixed bottom-0 inset-x-0 z-30 lg:hidden border-t border-line bg-night/85 backdrop-blur safe-bottom animate-fade-in-up">
  <?php if ($currentUserId > 0): ?>
    <a href="create-post.php" aria-label="New Post"
       class="fixed left-1/2 z-40 -translate-x-1/2 -translate-y-1/2 flex h-14 w-14 items-center justify-center rounded-full bg-pop text-white shadow-lg transition active:scale-95"
       style="box-shadow: 0 8px 18px -6px rgba(217,58,0,0.55);">
      <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
    </a>
  <?php endif; ?>

  <div class="relative grid grid-cols-3 justify-items-center items-center">

    <a href="index.php" data-tab
       class="flex flex-col items-center gap-0.5 py-2 transition active:scale-95">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="transition-colors duration-150 <?= tabActive($currentPage, 'index.php') ?>"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9,22 9,12 15,12 15,22"/></svg>
      <span class="text-[11px] font-medium transition-colors duration-150 <?= tabActive($currentPage, 'index.php') ?>">Home</span>
    </a>

    <a href="communities.php" data-tab
       class="flex flex-col items-center gap-0.5 py-2 transition active:scale-95">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="transition-colors duration-150 <?= tabActive($currentPage, 'communities.php') ?>"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
      <span class="text-[11px] font-medium transition-colors duration-150 <?= tabActive($currentPage, 'communities.php') ?>">Communities</span>
    </a>

    <?php if ($currentUserId > 0): ?>
      <a href="profile.php" data-tab
         class="flex flex-col items-center gap-0.5 py-2 transition active:scale-95">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="transition-colors duration-150 <?= tabActive($currentPage, 'profile.php') ?>"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        <span class="text-[11px] font-medium transition-colors duration-150 <?= tabActive($currentPage, 'profile.php') ?>">Profile</span>
      </a>
    <?php else: ?>
      <a href="login.php" data-tab
         class="flex flex-col items-center gap-0.5 py-2 transition active:scale-95">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-ink-faint"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10,17 15,12 10,7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
        <span class="text-[11px] font-medium text-ink-faint">Log in</span>
      </a>
    <?php endif; ?>

  </div>
</nav>