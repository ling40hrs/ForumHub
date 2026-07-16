<?php

$user = $_SESSION['user'] ?? null;
?>
<header class="sticky top-0 z-20 border-b border-line bg-night/90 backdrop-blur">
  <nav class="mx-auto flex max-w-5xl items-center gap-4 px-4 py-3">
    <a href="index.php" class="group flex items-center gap-2">
      <img src="images/favicon.svg" alt="Yapr" class="h-8 w-8 rounded-lg transition-transform duration-150 ease-out group-hover:-rotate-6 group-hover:scale-110">
      <span class="font-display text-2xl font-black tracking-tight text-ink">Yapr</span>
    </a>
    <form action="index.php" method="get" class="hidden flex-1 md:block">
      <input type="search" name="q" placeholder="Search the yap…"
             class="field rounded-full">
    </form>
    <a href="index.php" aria-label="Search" class="flex h-9 w-9 items-center justify-center rounded-full text-ink-faint transition hover:bg-night-raised hover:text-ink md:hidden">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-4.3-4.3"/></svg>
    </a>
    <div class="ml-auto flex items-center gap-2 text-sm">
      <?php if (!empty($user)): ?>
        <?= avatarHtml($user, 9) ?>
        <a href="profile.php?id=<?= escapeHtml($user['id']) ?>" class="hidden font-semibold text-ink transition hover:text-pop sm:block"><?= escapeHtml($user['username']) ?></a>
        <a href="logout.php" class="btn-ghost">Log out</a>
      <?php else: ?>
        <a href="login.php" class="btn-ghost">Log in</a>
        <a href="register.php" class="btn-primary">Sign up</a>
      <?php endif; ?>
    </div>
  </nav>
</header>
