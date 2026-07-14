<?php
require __DIR__ . '/includes/sample-data.php';
require __DIR__ . '/includes/helpers.php';
$title = 'Log in';
require __DIR__ . '/includes/header.php';
?>
<div class="mx-auto max-w-md">
  <div class="card animate-fade-in-up p-6">
    <h1 class="mb-1 font-display text-2xl font-bold text-ink">Welcome back</h1>
    <p class="mb-6 text-sm text-ink-faint">Log in to your Yapr account.</p>
    <form action="index.php" method="post" class="space-y-4">
      <div>
        <label class="mb-1 block text-sm font-medium text-ink-soft">Username or email</label>
        <input name="identifier" type="text" required autocomplete="username" class="field">
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

