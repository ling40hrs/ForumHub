(function () {
  var tabBar = document.getElementById('tab-bar');
  if (!tabBar) return;

  var pills  = tabBar.querySelectorAll('.tab-pill');
  var panels = document.querySelectorAll('[data-panel]');

  var EASE = 'cubic-bezier(0.23, 1, 0.32, 1)';
  var lock = false;

  function flashButtons(name) {
    pills.forEach(function (btn) {
      btn.classList.toggle('is-active', btn.getAttribute('data-tab') === name);
    });
  }

  function showPanel(name) {
    var target = document.querySelector('[data-panel="' + name + '"]');
    if (!target) return;

    target.classList.remove('hidden');

    target.style.transition = 'none';
    target.style.opacity = '0';
    target.style.transform = 'translateY(8px)';
    target.offsetHeight;
    target.style.transition = 'opacity 200ms ' + EASE + ', transform 200ms ' + EASE;
    target.style.opacity = '1';
    target.style.transform = 'translateY(0)';

    (function() {
      var reveals = target.querySelectorAll('.reveal');
      reveals.forEach(function (el, i) {
        el.classList.remove('visible');
        setTimeout(function () {
          el.classList.add('visible');
        }, 10 + i * 55);
      });
    })();

    flashButtons(name);
    lock = false;
  }

  function switchTab(name) {
    if (lock) return;

    var current = document.querySelector('[data-panel]:not(.hidden)');
    if (current && current.getAttribute('data-panel') === name) return;

    lock = true;
    flashButtons(name);

    if (!current) {
      panels.forEach(function (p) { p.classList.add('hidden'); });
      showPanel(name);
      return;
    }

    current.style.transition = 'opacity 150ms ' + EASE + ', transform 150ms ' + EASE;
    current.style.opacity = '0';
    current.style.transform = 'translateY(6px)';

    setTimeout(function () {
      current.classList.add('hidden');
      current.style.transition = '';
      current.style.opacity = '';
      current.style.transform = '';
      panels.forEach(function (p) { if (p !== current) p.classList.add('hidden'); });
      showPanel(name);
    }, 140);
  }

  pills.forEach(function (btn) {
    btn.addEventListener('click', function () {
      switchTab(this.getAttribute('data-tab'));
    });
  });

  var hash = location.hash.replace('#', '');
  if (hash && document.querySelector('[data-tab="' + hash + '"]')) {
    switchTab(hash);
  }
})();
