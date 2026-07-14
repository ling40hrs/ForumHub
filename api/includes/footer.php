<?php

declare(strict_types=1);

// Closing tags. Mirrors header.php.
?>
</main>

<script>
  // Reset to top on every page load (Lenis-aware fallback).
  if (typeof Lenis === 'undefined') {
    if ('scrollRestoration' in history) history.scrollRestoration = 'manual';
    window.scrollTo(0, 0);
  }

  // Vote rail: local up/down toggle with live score (static demo only).
  document.querySelectorAll('.vote-rail').forEach(function (rail) {
    var scoreEl = rail.querySelector('.vote-score');
    var up = rail.querySelector('.is-up');
    var down = rail.querySelector('.is-down');
    var base = parseInt(rail.dataset.score, 10) || 0;
    var state = 0;
    function render() {
      scoreEl.textContent = base + state;
      up.classList.toggle('is-active', state === 1);
      down.classList.toggle('is-active', state === -1);
    }
    up.addEventListener('click', function () { state = state === 1 ? 0 : 1; render(); });
    down.addEventListener('click', function () { state = state === -1 ? 0 : -1; render(); });
  });
</script>

<script src="https://unpkg.com/lenis@1.1.18/dist/lenis.min.js"></script>
<script>
(function () {
  'use strict';

  var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  // --- Lenis smooth scroll ---
  if (!reduceMotion && typeof Lenis !== 'undefined') {
    var lenis = new Lenis({
      duration: 0.45,
      easing: function (t) { return 1 - Math.pow(1 - t, 3); },
      smoothWheel: true,
    });
    function raf(time) { lenis.raf(time); requestAnimationFrame(raf); }
    requestAnimationFrame(raf);

    // --- Smart navbar ---
    var header = document.querySelector('header');
    if (header) {
      lenis.on('scroll', function (e) {
        var scrolled = e.progress > 0.05;
        if (e.velocity > 0.5 && scrolled) {
          header.classList.add('nav-hidden');
        } else if (e.velocity < -0.5 || !scrolled) {
          header.classList.remove('nav-hidden');
        }
      });
    }

    // --- Reading progress bar ---
    var progressBar = document.querySelector('.progress-bar');
    if (progressBar) {
      lenis.on('scroll', function (e) {
        progressBar.style.transform = 'scaleX(' + e.progress + ')';
      });
    }
  }

  // --- Scroll reveals ---
  (function () {
    var els = document.querySelectorAll('.reveal');
    if (reduceMotion) {
      els.forEach(function (el) { el.classList.add('visible'); });
      return;
    }
    if (!els.length) return;
    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });
    els.forEach(function (el) { observer.observe(el); });
  })();

  // --- Button ripple ---
  (function () {
    var buttons = document.querySelectorAll('.btn-primary, .btn-pop');
    buttons.forEach(function (btn) {
      btn.classList.add('ripple-container');
      btn.addEventListener('click', function (e) {
        var rect = btn.getBoundingClientRect();
        var size = Math.max(rect.width, rect.height);
        var x = e.clientX - rect.left - size / 2;
        var y = e.clientY - rect.top - size / 2;
        var ripple = document.createElement('span');
        ripple.className = 'ripple';
        ripple.style.width = ripple.style.height = size + 'px';
        ripple.style.left = x + 'px';
        ripple.style.top = y + 'px';
        btn.appendChild(ripple);
        ripple.addEventListener('animationend', function () { ripple.remove(); });
      });
    });
  })();

  // --- Page transitions ---
  (function () {
    document.addEventListener('click', function (e) {
      var link = e.target.closest('a[href]');
      if (!link || !link.href) return;
      if (link.hostname !== window.location.hostname) return;
      if (link.target === '_blank') return;
      if (e.metaKey || e.ctrlKey || e.shiftKey) return;
      if (link.getAttribute('href') === '#') return;
      e.preventDefault();
      document.body.classList.add('page-exit');
      setTimeout(function () { window.location.href = link.href; }, 150);
    });
  })();
})();
</script>
</body>
</html>
