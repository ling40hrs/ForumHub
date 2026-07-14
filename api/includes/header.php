<?php
// Shared document head + opening body. Expects $title to be set by the page.
if (!isset($title)) {
    $title = 'Home';
}
$ogType = $ogType ?? 'website';
$ogDescription = $ogDescription ?? 'Yapr — a community where people yap about what they love.';
$pageTitle = $title . ' · Yapr';
?>
<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script>history.scrollRestoration='manual';window.scrollTo(0,0);</script>
  <title><?= esc($pageTitle) ?></title>
  <meta name="description" content="<?= esc($ogDescription) ?>">
  <meta property="og:site_name" content="Yapr">
  <meta property="og:type" content="<?= esc($ogType) ?>">
  <meta property="og:title" content="<?= esc($pageTitle) ?>">
  <meta property="og:description" content="<?= esc($ogDescription) ?>">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="<?= esc($pageTitle) ?>">
  <meta name="twitter:description" content="<?= esc($ogDescription) ?>">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,600;12..96,700;12..96,800&family=Hanken+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            display: ['"Bricolage Grotesque"', 'system-ui', 'sans-serif'],
            sans: ['"Hanken Grotesk"', 'system-ui', 'sans-serif'],
          },
          animation: {
            'fade-in': 'fade-in 0.6s ease-out both',
            'fade-in-up': 'fade-in-up 0.5s ease-out both',
            'fade-in-down': 'fade-in-down 0.5s ease-out both',
            'scale-in': 'scale-in 0.45s cubic-bezier(0.34, 1.56, 0.64, 1) both',
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
            card: '0 1px 2px rgba(0,0,0,0.5)',
            'card-hover': '0 0 0 1px rgba(50,145,255,0.40)',
          },
        },
      },
    };
  </script>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
    }
    ::selection { background: #ff4500; color: #000; }
    .card {
      position: relative;
      border-radius: 1rem;
      border: 1px solid #262626;
      background: #0a0a0a;
      box-shadow: 0 1px 2px rgba(0,0,0,0.5);
      transition: all 0.3s;
      animation: fade-in-up 0.5s ease-out both;
    }
    .card:hover {
      transform: translateY(-0.125rem);
      border-color: rgba(255,255,255,0.2);
      box-shadow: 0 0 0 1px rgba(50,145,255,0.40);
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
      transition: all 0.15s;
    }
    .pill:active { transform: scale(0.95); }
    .btn-primary { background: #ededed; color: #000; }
    .btn-primary:hover { background: #ff4500; color: #000; }
    .btn-pop { background: #ff4500; color: #fff; }
    .btn-pop:hover { background: #d93a00; }
    .btn-ghost { color: #a1a1a1; }
    .btn-ghost:hover { background: #161616; color: #ff4500; }
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
      transition: all 0.15s;
    }
    .vote-btn:hover { background: rgba(255,69,0,0.15); color: #ff4500; }
    .vote-btn:active { transform: scale(0.9); }
    .vote-btn.is-up.is-active { background: #ff4500; color: #000; }
    .vote-btn.is-down.is-active { background: #ededed; color: #000; }
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
      transition: all 0.15s;
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
    @media (prefers-reduced-motion: reduce) {
      *, *::before, *::after {
        animation-duration: 0.001ms !important;
        animation-delay: 0ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.001ms !important;
        scroll-behavior: auto !important;
      }
    }
  </style>
  <link rel="icon" type="image/svg+xml" href="images/favicon.svg">
</head>
<body class="h-full">
<?php require __DIR__ . '/navbar.php'; ?>
<main class="relative z-10 mx-auto max-w-5xl animate-fade-in px-4 py-6">

