<?php

if (!isset($title)) {
    $title = 'Home';
}
$ogType = $ogType ?? 'website';
$ogDescription = $ogDescription ?? 'Yapr — a community where people yap about what they love.';
$pageTitle = $title . ' · Yapr';
?>
<!DOCTYPE html>
<html lang="en" class="h-full dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
  <meta name="theme-color" content="#000000">
  <title><?= escapeHtml($pageTitle) ?></title>
  <meta name="description" content="<?= escapeHtml($ogDescription) ?>">
  <meta property="og:site_name" content="Yapr">
  <meta property="og:type" content="<?= escapeHtml($ogType) ?>">
  <meta property="og:title" content="<?= escapeHtml($pageTitle) ?>">
  <meta property="og:description" content="<?= escapeHtml($ogDescription) ?>">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="<?= escapeHtml($pageTitle) ?>">
  <meta name="twitter:description" content="<?= escapeHtml($ogDescription) ?>">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,600;12..96,700;12..96,800&family=Hanken+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
  <script src="/ForumHub/public/js/tailwind.min.js"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            display: ['"Bricolage Grotesque"', 'system-ui', 'sans-serif'],
            sans: ['"Hanken Grotesk"', 'system-ui', 'sans-serif'],
          },
          animation: {
            'fade-in': 'fade-in 0.25s ease-out both',
            'fade-in-up': 'fade-in-up 0.25s ease-out both',
            'fade-in-down': 'fade-in-down 0.15s ease-out both',
            'scale-in': 'scale-in 0.25s cubic-bezier(0.22, 1, 0.36, 1) both',
            'gradient-pan': 'gradient-pan 10s ease infinite',
          },
          keyframes: {
            'fade-in': { '0%': { opacity: '0' }, '100%': { opacity: '1' } },
            'fade-in-up': { '0%': { opacity: '0', transform: 'translateY(14px)' }, '100%': { opacity: '1', transform: 'translateY(0)' } },
            'fade-in-down': { '0%': { opacity: '0', transform: 'translateY(-14px)' }, '100%': { opacity: '1', transform: 'translateY(0)' } },
            'scale-in': { '0%': { opacity: '0', transform: 'scale(0.94)' }, '100%': { opacity: '1', transform: 'scale(1)' } },
            'gradient-pan': { '0%': { backgroundPosition: '0% 50%' }, '50%': { backgroundPosition: '100% 50%' }, '100%': { backgroundPosition: '0% 50%' } },
          },
          colors: {
            night: { DEFAULT: '#000000', soft: '#0a0a0a', raised: '#161616', top: '#1f1f1f' },
            ink: { DEFAULT: '#ededed', soft: '#a1a1a1', faint: '#6b6b6b' },
            line: '#262626',
            pop: { DEFAULT: '#ff4500', dark: '#d93a00', tint: 'rgba(255,69,0,0.15)' },
            link: '#ff6a3d',
          },
          boxShadow: {
            'card-hover': '0 0 0 1px rgba(50,145,255,0.40)',
          },
        },
      },
    };
  </script>
  <style>
    :root { --ease-out: cubic-bezier(0.23, 1, 0.32, 1); }
    body {
      background: #000000;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
    }
    ::selection { background: #ff4500; color: #000; }
    .card {
      position: relative;
      border-radius: 1rem;
      border: 1px solid #262626;
      background: #0a0a0a;
      color: #ededed;
      transition: transform 200ms var(--ease-out), border-color 200ms var(--ease-out), box-shadow 200ms var(--ease-out);
    }
    .pill {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.375rem;
      border-radius: 9999px;
      padding: 0.375rem 0.75rem;
      font-size: 0.875rem;
      font-weight: 600;
      transition: transform 150ms var(--ease-out), background 150ms var(--ease-out), color 150ms var(--ease-out);
    }
    .pill:active { transform: scale(0.95); }
    .pill.active { background: #ff4500; color: #fff; }
    .btn-primary, .btn-pop {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border-radius: 9999px;
      padding: 0.5rem 1rem;
      font-size: 0.875rem;
      font-weight: 600;
      transition: transform 150ms var(--ease-out), background 150ms var(--ease-out), color 150ms var(--ease-out);
    }
    .btn-primary { background: #ededed; color: #000; }
    .btn-pop { background: #ff4500; color: #fff; }
    .btn-primary:active, .btn-pop:active { transform: scale(0.95); }
    .btn-ghost { color: #a1a1a1; font-weight: 500; transition: transform 150ms var(--ease-out), background 150ms var(--ease-out), color 150ms var(--ease-out); }
    .btn-ghost:active { transform: scale(0.97); }
    .btn-primary:focus-visible, .btn-pop:focus-visible, .btn-ghost:focus-visible {
      outline: none;
      box-shadow: 0 0 0 2px #ff4500;
    }
    .vote-rail {
      display: flex;
      width: 3.5rem;
      flex-shrink: 0;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 0.125rem;
      border-top-left-radius: 1rem;
      border-bottom-left-radius: 1rem;
      border-right: 1px solid #262626;
      background: #161616;
      padding: 0.75rem 0;
      color: #a1a1a1;
    }
    .vote-btn {
      display: flex;
      height: 1.75rem;
      width: 1.75rem;
      align-items: center;
      justify-content: center;
      border-radius: 9999px;
      font-size: 1rem;
      line-height: 1;
      transition: transform 150ms var(--ease-out), background 150ms var(--ease-out), color 150ms var(--ease-out);
    }
    .vote-btn:active { transform: scale(0.95); }
    .vote-btn.is-up.is-active { background: #ff4500; color: #000; }
    .vote-btn.is-down.is-active { background: #ededed; color: #000; }
    .vote-pill.is-up.is-active { background: #ff4500; color: #000; }
    .vote-pill.is-down.is-active { background: #ededed; color: #000; }
    .vote-score {
      font-family: "Bricolage Grotesque", system-ui, sans-serif;
      font-size: 0.875rem;
      font-weight: 700;
      font-variant-numeric: tabular-nums;
      color: #ededed;
    }
    .field {
      width: 100%;
      border-radius: 0.75rem;
      border: 1px solid #262626;
      background: #0a0a0a;
      padding: 0.5rem 0.75rem;
      color: #ededed;
      transition: border-color 150ms var(--ease-out), box-shadow 150ms var(--ease-out);
    }
    .field::placeholder { color: #6b6b6b; }
    .field:focus {
      border-color: #ff4500;
      outline: none;
      box-shadow: 0 0 0 2px rgba(255,69,0,0.3);
    }
    .chip {
      border-radius: 9999px;
      border: 1px solid #262626;
      background: #0a0a0a;
      padding: 0.125rem 0.625rem;
      font-size: 0.75rem;
      font-weight: 500;
      color: #a1a1a1;
    }
    @media (hover: hover) and (pointer: fine) {
      .card:hover { transform: translateY(-0.125rem); border-color: rgba(255,255,255,0.2); }
      .btn-primary:hover { background: #ff4500; color: #000; }
      .btn-pop:hover { background: #d93a00; }
      .btn-ghost:hover { background: #161616; color: #ff4500; }
      .vote-btn:hover { background: rgba(255,69,0,0.15); color: #ff4500; }
    }
    @media (prefers-reduced-motion: reduce) {
      *, *::before, *::after {
        animation-duration: 0.001ms !important;
        animation-delay: 0ms !important;
        animation-iteration-count: 1 !important;
        transition: opacity 150ms ease, color 150ms ease, background 150ms ease, border-color 150ms ease !important;
        scroll-behavior: auto !important;
      }
    }
  </style>
  <link rel="icon" type="image/svg+xml" href="/ForumHub/public/images/favicon.svg">
  <link rel="stylesheet" href="/ForumHub/public/css/polish.css">
  <link rel="stylesheet" href="/ForumHub/public/css/mobile.css">
</head>
<body class="h-full">
<?php require __DIR__ . '/navbar.php'; ?>
<main id="main-content" class="app-shell relative z-10 animate-fade-in px-6 pb-2 lg:pb-6" style="padding-top: calc(4rem + env(safe-area-inset-top, 0px));">
<?php if (!isset($noSidebar)): ?>
  <div class="flex flex-col gap-6 lg:flex-row">
    <aside class="hidden w-[220px] shrink-0 lg:block">
      <div class="sticky top-20 space-y-4">
        <?php require __DIR__ . '/sidebar-left.php'; ?>
      </div>
    </aside>
    <div class="min-w-0 flex-1">
<?php endif; ?>
