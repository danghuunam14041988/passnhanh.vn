// Địa chỉ của web app (chợ Pass). Đổi giá trị này khi app được triển khai ở domain khác.
const APP_URL = 'https://app.passnhanh.vn';

function appLink(path) {
  return APP_URL.replace(/\/$/, '') + (path || '/');
}

document.querySelectorAll('.js-app-link').forEach((a) => {
  a.href = appLink(a.dataset.path);
});

// Menu mobile
const header = document.querySelector('.site-header');
const toggle = document.getElementById('menuToggle');
if (toggle) {
  toggle.addEventListener('click', () => {
    const open = header.classList.toggle('open');
    toggle.setAttribute('aria-expanded', String(open));
    toggle.setAttribute('aria-label', open ? 'Đóng menu' : 'Mở menu');
  });
  document.querySelectorAll('#nav a').forEach((a) =>
    a.addEventListener('click', () => {
      header.classList.remove('open');
      toggle.setAttribute('aria-expanded', 'false');
    })
  );
}

// Ô tìm kiếm: tab "Tìm Pass" / "Đăng nhu cầu"
const searchTabs = document.querySelectorAll('.search-tab');
const searchQ = document.getElementById('searchQ');
const searchBtnText = document.getElementById('searchBtnText');
let searchMode = 'find';
searchTabs.forEach((tab) =>
  tab.addEventListener('click', () => {
    searchMode = tab.dataset.mode;
    searchTabs.forEach((t) => t.setAttribute('aria-selected', String(t === tab)));
    if (searchMode === 'demand') {
      searchQ.placeholder = 'Bạn cần tìm gì? (ví dụ: phòng 2 người view biển, vé concert…)';
      searchBtnText.textContent = 'Đăng nhu cầu';
    } else {
      searchQ.placeholder = 'Bạn đang tìm gì? (ví dụ: phòng Hạ Long, vé phim, tour Đà Nẵng…)';
      searchBtnText.textContent = 'Tìm ngay';
    }
  })
);
document.getElementById('searchForm').addEventListener('submit', (e) => {
  e.preventDefault();
  window.location.href = appLink('/');
});

// Tin minh họa
const LISTINGS = [
  { img: 'halong-hotel', title: 'Phòng 5★ Hạ Long View biển, Deluxe', city: 'Hạ Long, Quảng Ninh', old: 2800000, price: 1500000, left: 'Còn 9 giờ', hours: 9, urgent: true },
  { img: 'movie', title: 'Vé xem phim cuối tuần, 2 ghế đôi', city: 'Hà Nội', old: 400000, price: 270000, left: 'Còn 1 ngày', hours: 24 },
  { img: 'sapa-tour', title: 'Tour Sapa 2N1Đ Xe giường nằm + KS 3★', city: 'Sapa, Lào Cai', old: 3500000, price: 2200000, left: 'Còn 2 ngày', hours: 48 },
  { img: 'sofa', title: 'Sofa da cao cấp 99% mới, thanh lý', city: 'Hà Nội', old: 15000000, price: 7500000, left: 'Còn 3 ngày', hours: 72 },
  { img: 'room', title: 'Phòng trọ full đồ Gần ĐH Bách Khoa', city: 'Hà Nội', old: 4000000, price: 2800000, left: 'Còn 4 ngày', hours: 96 },
  { img: 'concert', title: 'Vé concert tháng 11, khu GA', city: 'Hà Nội', old: 2500000, price: 1600000, left: 'Còn 5 giờ', hours: 5, urgent: true },
  { img: 'danang-hotel', title: 'Phòng 4★ Đà Nẵng Gần biển Mỹ Khê', city: 'Đà Nẵng', old: 2200000, price: 1200000, left: 'Còn 1 ngày', hours: 24 },
  { img: 'scooter', title: 'Xe máy tay ga 2022 Chính chủ', city: 'Hà Nội', old: 28000000, price: 21000000, left: 'Còn 5 ngày', hours: 120 },
  { img: 'phuquoc-tour', title: 'Tour Phú Quốc 3N2Đ Resort 4★', city: 'Phú Quốc, Kiên Giang', old: 5200000, price: 3100000, left: 'Còn 5 ngày', hours: 120 },
  { img: 'apartment', title: 'Căn hộ 1PN đủ nội thất, pass hợp đồng', city: 'Hà Nội', old: 12000000, price: 8500000, left: 'Còn 10 ngày', hours: 240 },
];

const money = (n) => n.toLocaleString('vi-VN') + 'đ';
const off = (x) => Math.round((1 - x.price / x.old) * 100);
const esc = (s) => String(s).replace(/[&<>"']/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));

let saved = [];
try { saved = JSON.parse(localStorage.getItem('pn_saved') || '[]'); } catch (e) { saved = []; }
function persistSaved() {
  try { localStorage.setItem('pn_saved', JSON.stringify(saved)); } catch (e) { /* bỏ qua */ }
}

const grid = document.getElementById('listingGrid');
const citySelect = document.getElementById('citySelect');
let filter = 'all';

function renderListings() {
  let items = LISTINGS.slice();
  if (filter === 'near') {
    const city = citySelect.value.replace('TP. ', '');
    items = items.filter((x) => x.city.includes(city));
  } else if (filter === 'deal') {
    items.sort((a, b) => off(b) - off(a));
  } else if (filter === 'soon') {
    items.sort((a, b) => a.hours - b.hours);
  } else if (filter === 'urgent') {
    items = items.filter((x) => x.urgent);
  }
  if (!items.length) {
    grid.innerHTML = '<p class="empty">Chưa có tin phù hợp ở khu vực này. <a class="js-app-link" href="' + appLink('/') + '">Đăng nhu cầu</a> để được báo khi có tin.</p>';
    return;
  }
  grid.innerHTML = items.map((x) => {
    const on = saved.includes(x.img);
    return `<article class="listing">
      <div class="l-media">
        <img src="assets/img/${x.img}.webp" alt="" loading="lazy">
        ${x.urgent ? '<span class="l-urgent">Cần Pass Gấp</span>' : ''}
        <button class="l-heart${on ? ' on' : ''}" data-id="${x.img}" aria-pressed="${on}" aria-label="Lưu tin">
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 20s-7-4.4-7-10a4 4 0 0 1 7-2.6A4 4 0 0 1 19 10c0 5.6-7 10-7 10z"/></svg>
        </button>
        <span class="l-left">⏱ ${esc(x.left)}</span>
      </div>
      <div class="l-body">
        <h3><a href="${appLink('/')}">${esc(x.title)}</a></h3>
        <p class="l-city">📍 ${esc(x.city)}</p>
        <div class="l-price"><s>${money(x.old)}</s><b>${money(x.price)}</b><span class="l-off">-${off(x)}%</span></div>
      </div>
    </article>`;
  }).join('');
}

grid.addEventListener('click', (e) => {
  const btn = e.target.closest('.l-heart');
  if (!btn) return;
  const id = btn.dataset.id;
  saved = saved.includes(id) ? saved.filter((s) => s !== id) : saved.concat(id);
  persistSaved();
  btn.classList.toggle('on');
  btn.setAttribute('aria-pressed', String(btn.classList.contains('on')));
});

document.querySelectorAll('.filters .chip').forEach((chip) =>
  chip.addEventListener('click', () => {
    filter = chip.dataset.filter;
    document.querySelectorAll('.filters .chip').forEach((c) => c.classList.toggle('active', c === chip));
    renderListings();
  })
);
citySelect.addEventListener('change', () => { if (filter === 'near') renderListings(); });

// Chip khu vực: chọn thành phố rồi lọc "Gần bạn"
document.querySelectorAll('.area-chips a:not(.more)').forEach((a) =>
  a.addEventListener('click', () => {
    const name = a.textContent.trim();
    const opt = Array.from(citySelect.options).find((o) => o.text === name);
    if (opt) citySelect.value = opt.value; else {
      citySelect.add(new Option(name, name));
      citySelect.value = name;
    }
    document.querySelector('.filters .chip[data-filter="near"]').click();
  })
);

renderListings();

const year = document.getElementById('year');
if (year) year.textContent = new Date().getFullYear();
