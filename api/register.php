<?php

require __DIR__ . '/includes/helpers.php';
$noSidebar = true;
$noBottomNav = true;
$title = 'Sign up';
require __DIR__ . '/includes/header.php';
?>
<div class="mx-auto max-w-md">
  <div class="card animate-fade-in-up p-6">
    <h1 class="mb-1 font-display text-2xl font-bold text-ink">Create your account</h1>
    <p class="mb-6 text-sm text-ink-faint">Join Yapr and start posting.</p>
    <?php if (isset($_GET['error'])): ?>
      <p class="mb-4 rounded-xl bg-pop-tint px-4 py-2 text-sm font-medium text-pop">
        <?php if ($_GET['error'] === '1'): ?>
          All fields are required.
        <?php else: ?>
          Username or email already taken.
        <?php endif; ?>
      </p>
    <?php endif; ?>
    <form action="register-handler.php" method="post" class="space-y-4">
      <div>
        <label class="mb-1 block text-sm font-medium text-ink-soft">Username</label>
        <input name="username" type="text" required autocomplete="username" class="field">
      </div>
      <div>
        <label class="mb-1 block text-sm font-medium text-ink-soft">Email</label>
        <input name="email" type="email" required autocomplete="email" class="field">
      </div>
      <div>
        <label class="mb-1 block text-sm font-medium text-ink-soft">Password</label>
        <input name="password" type="password" required autocomplete="new-password" class="field">
      </div>
      <div>
        <label class="mb-1 block text-sm font-medium text-ink-soft">Confirm password</label>
        <input name="password_confirm" type="password" required autocomplete="new-password" class="field">
      </div>
      <button type="submit" class="btn-pop w-full">Sign up</button>
    </form>
    <p class="mt-4 text-center text-sm text-ink-faint">Already have an account?
      <a href="login.php" class="font-semibold text-pop hover:underline">Log in</a>
    </p>
  </div>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
