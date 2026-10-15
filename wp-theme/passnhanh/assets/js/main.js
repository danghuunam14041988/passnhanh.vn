(function () {
  var appUrl = (window.PN && window.PN.appUrl) || 'https://app.passnhanh.vn/';

  // Menu mobile
  var header = document.querySelector('.site-header');
  var toggle = document.getElementById('menuToggle');
  if (toggle && header) {
    toggle.addEventListener('click', function () {
      var open = header.classList.toggle('open');
      toggle.setAttribute('aria-expanded', String(open));
      toggle.setAttribute('aria-label', open ? 'Đóng menu' : 'Mở menu');
    });
    document.querySelectorAll('#nav a').forEach(function (a) {
      a.addEventListener('click', function () {
        header.classList.remove('open');
        toggle.setAttribute('aria-expanded', 'false');
      });
    });
  }

  // Ô tìm kiếm: tab "Tìm Pass" tìm trong Tin Pass, tab "Đăng nhu cầu" mở web app
  var form = document.getElementById('searchForm');
  var searchQ = document.getElementById('searchQ');
  var btnText = document.getElementById('searchBtnText');
  var mode = 'find';
  document.querySelectorAll('.search-tab').forEach(function (tab, _, tabs) {
    tab.addEventListener('click', function () {
      mode = tab.dataset.mode;
      tabs.forEach(function (t) { t.setAttribute('aria-selected', String(t === tab)); });
      if (mode === 'demand') {
        searchQ.placeholder = 'Bạn cần tìm gì? (ví dụ: phòng 2 người view biển, vé concert…)';
        btnText.textContent = 'Đăng nhu cầu';
      } else {
        searchQ.placeholder = 'Bạn đang tìm gì? (ví dụ: phòng Hạ Long, vé phim, tour Đà Nẵng…)';
        btnText.textContent = 'Tìm ngay';
      }
    });
  });
  if (form) {
    form.addEventListener('submit', function (e) {
      if (mode === 'demand') {
        e.preventDefault();
        window.location.href = appUrl;
      }
    });
  }

  // Lưu tin (trái tim) trong trình duyệt
  var saved = [];
  try { saved = JSON.parse(localStorage.getItem('pn_saved') || '[]'); } catch (err) { saved = []; }
  function persist() {
    try { localStorage.setItem('pn_saved', JSON.stringify(saved)); } catch (err) { /* bỏ qua */ }
  }
  document.querySelectorAll('.l-heart').forEach(function (btn) {
    var on = saved.indexOf(btn.dataset.id) !== -1;
    btn.classList.toggle('on', on);
    btn.setAttribute('aria-pressed', String(on));
    btn.addEventListener('click', function (e) {
      e.preventDefault();
      var id = btn.dataset.id;
      var idx = saved.indexOf(id);
      if (idx === -1) saved.push(id); else saved.splice(idx, 1);
      persist();
      var now = idx === -1;
      btn.classList.toggle('on', now);
      btn.setAttribute('aria-pressed', String(now));
    });
  });

  // Chọn khu vực ở đầu trang
  var citySelect = document.getElementById('citySelect');
  var grid = document.getElementById('listingGrid');

  // Bộ lọc "Tin Pass nổi bật" trên trang chủ
  if (grid) {
    var cards = Array.prototype.slice.call(grid.querySelectorAll('.listing'));
    var empty = grid.querySelector('.empty');
    var filter = 'all';
    var norm = function (s) { return (s || '').toLowerCase().replace('tp. ', ''); };

    var render = function () {
      var list = cards.slice();
      if (filter === 'near') {
        var city = norm(citySelect ? citySelect.value : '');
        list = list.filter(function (c) { return norm(c.dataset.city).indexOf(city) !== -1; });
      } else if (filter === 'urgent') {
        list = list.filter(function (c) { return c.dataset.urgent === '1'; });
      } else if (filter === 'deal') {
        list.sort(function (a, b) { return b.dataset.off - a.dataset.off; });
      } else if (filter === 'soon') {
        list.sort(function (a, b) { return a.dataset.hours - b.dataset.hours; });
      }
      cards.forEach(function (c) { c.hidden = list.indexOf(c) === -1; });
      list.forEach(function (c) { grid.insertBefore(c, empty); });
      if (empty) empty.hidden = list.length > 0;
    };

    document.querySelectorAll('.filters .chip').forEach(function (chip, _, chips) {
      chip.addEventListener('click', function () {
        filter = chip.dataset.filter;
        chips.forEach(function (c) { c.classList.toggle('active', c === chip); });
        render();
      });
    });
    if (citySelect) {
      citySelect.addEventListener('change', function () {
        if (filter === 'near') render();
        else document.querySelector('.filters .chip[data-filter="near"]').click();
      });
    }
  } else if (citySelect) {
    // Trang khác: chọn khu vực thì mở danh sách Tin Pass theo khu vực
    citySelect.addEventListener('change', function () { citySelect.form.submit(); });
  }
})();
