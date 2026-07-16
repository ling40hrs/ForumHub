<?php

$user = $_SESSION['user'] ?? null;

if (!empty($user) && isset($user['id'])) {
    $id = intval($user['id']);
    $row = mysqli_query($conn, "SELECT avatar FROM users WHERE id = $id");
    if ($row && $data = mysqli_fetch_assoc($row)) {
        $_SESSION['user']['avatar_url'] = $data['avatar'];
        $user = $_SESSION['user'];
    }
}
?>
<header class="fixed top-0 z-20 w-full border-b border-line bg-night animate-fade-in-down" style="padding-top: env(safe-area-inset-top);">
  <nav class="flex items-center justify-center py-3 px-4">
    <a href="index.php" class="group flex items-center gap-2">
      <img src="/ForumHub/public/images/favicon.svg" alt="Yapr" class="h-8 w-8 rounded-lg transition-transform duration-150 ease-out group-hover:-rotate-6 group-hover:scale-110">
      <span class="font-display text-2xl font-black tracking-tight text-ink">Yapr</span>
    </a>
  </nav>
</header>