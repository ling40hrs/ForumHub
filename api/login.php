<?php

require __DIR__ . '/includes/helpers.php';
$noSidebar = true;
$noBottomNav = true;
$title = 'Log in';
require __DIR__ . '/includes/header.php';
?>
<div class="mx-auto max-w-md">
  <div class="card animate-fade-in-up p-6">
    <h1 class="mb-1 font-display text-2xl font-bold text-ink">Welcome back</h1>
    <p class="mb-6 text-sm text-ink-faint">Log in to your Yapr account.</p>
    <?php if (isset($_GET['error'])): ?>
      <p class="mb-4 rounded-xl bg-pop-tint px-4 py-2 text-sm font-medium text-pop">Invalid username or password</p>
    <?php endif; ?>
    <form action="login-handler.php" method="post" class="space-y-4">
      <div>
        <label class="mb-1 block text-sm font-medium text-ink-soft">Username</label>
        <input name="username" type="text" required autocomplete="username" class="field">
      </div>
      <div>
        <label class="mb-1 block text-sm font-medium text-ink-soft">Password</label>
        <input name="password" type="password" required autocomplete="current-password" class="field">
      </div>
      <button type="submit" class="btn-pop w-full">Log in</button>
    </form>
    <p class="mt-4 text-center text-sm text-ink-faint">No account?
      <a href="register.php" class="font-semibold text-pop hover:underline">Sign up</a>
    </p>
  </div>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>

