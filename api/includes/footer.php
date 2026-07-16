<?php

?>
</main>

<script>
// Scroll to top on every page load
window.scrollTo(0, 0);

// Vote rail: local up/down toggle with live score
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

  up.addEventListener('click', function () {
    state = state === 1 ? 0 : 1;
    render();
  });

  down.addEventListener('click', function () {
    state = state === -1 ? 0 : -1;
    render();
  });
});

// Scroll reveal: fade in elements as they appear
(function () {
  var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var els = document.querySelectorAll('.reveal');

  if (reduceMotion) {
    els.forEach(function (el) {
      el.classList.add('visible');
    });
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
  }, { threshold: 0.1 });

  els.forEach(function (el) {
    observer.observe(el);
  });
})();
</script>
</body>
</html>
