<?php
// Closing tags. Mirrors header.php.
?>
<script>
  // Reset to top on every page load (browsers otherwise restore scroll on refresh).
  if ('scrollRestoration' in history) history.scrollRestoration = 'manual';
  window.scrollTo(0, 0);

  // Vote rail: local up/down toggle with live score (static demo only).
  document.querySelectorAll('.vote-rail').forEach(function (rail) {
    var scoreEl = rail.querySelector('.vote-score');
    var up = rail.querySelector('.is-up');
    var down = rail.querySelector('.is-down');
    var base = parseInt(rail.dataset.score, 10) || 0;
    var state = 0; // -1 down, 0 none, 1 up
    function render() {
      scoreEl.textContent = base + state;
      up.classList.toggle('is-active', state === 1);
      down.classList.toggle('is-active', state === -1);
    }
    up.addEventListener('click', function () { state = state === 1 ? 0 : 1; render(); });
    down.addEventListener('click', function () { state = state === -1 ? 0 : -1; render(); });
  });
</script>
</main>
</body>
</html>

