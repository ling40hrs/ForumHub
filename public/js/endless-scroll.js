(function () {
  var toggle = document.getElementById('endless-toggle');
  if (!toggle) return;

  var sort   = window.Yapr.sort;
  var base   = window.Yapr.base;
  var pagination = document.getElementById('pagination-container');
  var sentinel   = document.getElementById('scroll-sentinel');
  var feed       = document.getElementById('feed-posts');
  var loading    = false;
  var exhausted  = false;
  var active     = toggle.getAttribute('data-state') === '1';

  function updateUI() {
    toggle.setAttribute('data-state', active ? '1' : '0');
    if (active) {
      toggle.style.background = '#ff4500';
      toggle.style.color = '#ffffff';
    } else {
      toggle.style.background = '';
      toggle.style.color = '';
    }
    pagination.classList.toggle('hidden', active);
    sentinel.style.display = active ? '' : 'none';
    exhausted = false;
  }

  function nearBottom() {
    return (window.innerHeight + window.scrollY) >= (document.documentElement.scrollHeight - 600);
  }

  function loadMore() {
    if (!active || loading || exhausted) return;
    var nextPage = parseInt(sentinel.getAttribute('data-page') || 2);

    loading = true;
    sentinel.textContent = 'Loading...';

    var xhr = new XMLHttpRequest();
    xhr.open('GET', base + 'fetch-posts.php?sort=' + encodeURIComponent(sort) + '&page=' + nextPage + '&_=' + Date.now());
    xhr.onload = function () {
      if (xhr.status !== 200) {
        sentinel.textContent = 'Error loading posts (status ' + xhr.status + '). Tap to retry.';
        sentinel.onclick = function () { sentinel.onclick = null; loadMore(); };
        loading = false;
        return;
      }
      try {
        var data = JSON.parse(xhr.responseText);
      } catch (e) {
        sentinel.textContent = 'Error parsing response. Tap to retry.';
        sentinel.onclick = function () { sentinel.onclick = null; loadMore(); };
        loading = false;
        return;
      }

      sentinel.textContent = '';
      sentinel.onclick = null;

      if (!data.html) {
        exhausted = true;
        sentinel.textContent = 'No more posts';
        loading = false;
        return;
      }

      sentinel.insertAdjacentHTML('beforebegin', data.html);
      sentinel.setAttribute('data-page', nextPage + 1);
      feed.querySelectorAll('.reveal:not(.visible)').forEach(function (el) {
        el.classList.add('visible');
      });

      if (!data.hasMore) {
        exhausted = true;
        sentinel.textContent = 'No more posts';
      }

      loading = false;

      if (!exhausted && nearBottom()) setTimeout(loadMore, 100);
    };
    xhr.onerror = function () {
      loading = false;
      sentinel.textContent = 'Network error. Tap to retry.';
      sentinel.onclick = function () { sentinel.onclick = null; loadMore(); };
    };
    xhr.timeout = 15000;
    xhr.ontimeout = function () {
      loading = false;
      sentinel.textContent = 'Request timed out. Tap to retry.';
      sentinel.onclick = function () { sentinel.onclick = null; loadMore(); };
    };
    xhr.send();
  }

  var scrollTimer;
  window.addEventListener('scroll', function () {
    clearTimeout(scrollTimer);
    scrollTimer = setTimeout(function () {
      if (nearBottom()) loadMore();
    }, 150);
  }, { passive: true });

  updateUI();

  toggle.addEventListener('click', function () {
    active = !active;
    updateUI();
    var xhr = new XMLHttpRequest();
    xhr.open('POST', base + 'toggle-endless.php');
    if (!active) {
      xhr.onload = xhr.onerror = function () { window.location.reload(); };
    }
    xhr.send();
    if (active) {
      sentinel.setAttribute('data-page', 2);
      sentinel.textContent = '';
      exhausted = false;
      setTimeout(loadMore, 100);
    }
  });

  if (active) setTimeout(loadMore, 300);
})();
