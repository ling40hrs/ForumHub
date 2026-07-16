<?php

?>
<?php if (!isset($noSidebar)): ?>
  </div>
  <aside class="hidden shrink-0 lg:block lg:w-[280px]">
    <div class="sticky top-20 space-y-4">
      <?php require __DIR__ . '/sidebar-right.php'; ?>
    </div>
  </aside>
</div>
<?php endif; ?>
<div class="h-20 lg:hidden" aria-hidden="true"></div>
</main>

<?php
if (!isset($noBottomNav)) {
    require __DIR__ . '/bottom-nav.php';

}
?>

<script>
history.scrollRestoration = 'manual';
window.scrollTo(0, 0);

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

document.querySelectorAll('form').forEach(function (form) {
  var voteBtn = form.querySelector('[name="vote"]');
  if (!voteBtn) return;

  form.addEventListener('submit', function (e) {
    e.preventDefault();

    var body = new FormData(form);
    if (e.submitter) body.set(e.submitter.name, e.submitter.value);

    fetch('vote-handler.php', {
      method: 'POST',
      headers: { 'X-Requested-With': 'XMLHttpRequest' },
      body: body
    })
    .then(function (r) {
      if (!r.ok) throw new Error('Request failed');
      return r.json();
    })
    .then(function (data) {
      var scoreEl = form.querySelector('.vote-score') || form.querySelector('.font-semibold');
      if (scoreEl) scoreEl.textContent = data.score;

      var up = form.querySelector('.is-up');
      var down = form.querySelector('.is-down');
      if (up) up.classList.toggle('is-active', data.my_vote === 1);
      if (down) down.classList.toggle('is-active', data.my_vote === -1);
    })
    .catch(function () {
      window.location.href = 'login.php';
    });
  });
});

document.addEventListener('click', function (e) {
  if (e.target.classList.contains('reply-btn')) {
    var id = e.target.getAttribute('data-comment-id');
    document.querySelectorAll('.inline-reply').forEach(function (f) { f.classList.add('hidden'); });
    var form = document.getElementById('reply-form-' + id);
    if (form) {
      form.classList.remove('hidden');
      form.querySelector('textarea').focus();
    }
    return;
  }

  if (e.target.classList.contains('cancel-reply')) {
    e.target.closest('.inline-reply').classList.add('hidden');
    return;
  }
});

document.addEventListener('submit', function (e) {
  var form = e.target;
  if (!form.classList.contains('js-reply-form')) return;

  e.preventDefault();
  var btn = form.querySelector('button[type="submit"]');
  btn.disabled = true;
  btn.textContent = 'Posting...';

  var body = new FormData(form);

  fetch('comment-handler.php', {
    method: 'POST',
    headers: { 'X-Requested-With': 'XMLHttpRequest' },
    body: body
  })
  .then(function (r) { return r.json(); })
  .then(function (data) {
    if (data.success) {
      var parentThread = document.querySelector('.comment-thread[data-comment-id="' + data.parent_id + '"]');
      if (parentThread) {
        parentThread.insertAdjacentHTML('beforeend', data.html);
        parentThread.querySelectorAll('.reveal:not(.visible)').forEach(function (el) {
          el.classList.add('visible');
        });
      }
      form.closest('.inline-reply').classList.add('hidden');
      form.reset();
    } else {
      alert(data.error || 'Failed to post reply.');
    }
  })
  .catch(function () {
    alert('Something went wrong. Please try again.');
  })
  .finally(function () {
    btn.disabled = false;
    btn.textContent = 'Post Reply';
  });
});

</script>
</body>
</html>
