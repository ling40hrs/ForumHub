<?php
// Top navigation. $currentUser may be empty (logged-out state).
$user = $currentUser ?? null;
?>
<header class="sticky top-0 z-20 animate-fade-in-down border-b border-line bg-night/90 backdrop-blur">
  <nav class="mx-auto flex max-w-5xl items-center gap-4 px-4 py-3">
    <a href="index.php" class="group flex items-center gap-2">
      <img src="images/favicon.svg" alt="Yapr" class="h-8 w-8 rounded-lg transition duration-300 group-hover:-rotate-6 group-hover:scale-110">
      <span class="font-display text-2xl font-black tracking-tight text-ink">Yapr</span>
    </a>
    <form action="index.php" method="get" class="hidden flex-1 md:block">
      <input type="search" name="q" placeholder="Search the yap…"
             class="field rounded-full">
    </form>
    <div class="ml-auto flex items-center gap-2 text-sm">
      <?php if (!empty($user)): ?>
        <?= avatarHtml($user, 9) ?>
        <a href="profile.php?id=<?= esc($user['id']) ?>" class="hidden font-semibold text-ink transition hover:text-pop sm:block"><?= esc($user['username']) ?></a>
        <a href="login.php" class="btn-ghost">Log out</a>
      <?php else: ?>
        <a href="login.php" class="btn-ghost">Log in</a>
        <a href="register.php" class="btn-primary">Sign up</a>
      <?php endif; ?>
    </div>
  </nav>
</header>

