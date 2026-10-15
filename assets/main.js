// Địa chỉ của web app (chợ Pass). Đổi giá trị này khi app được triển khai ở domain khác.
const APP_URL = 'https://app.passnhanh.vn';

document.querySelectorAll('.js-app-link').forEach((a) => {
  a.href = APP_URL.replace(/\/$/, '') + (a.dataset.path || '/');
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

// Tabs "Cách hoạt động"
const tabs = Array.from(document.querySelectorAll('[role="tab"]'));
function selectTab(tab) {
  tabs.forEach((t) => {
    const on = t === tab;
    t.setAttribute('aria-selected', String(on));
    t.tabIndex = on ? 0 : -1;
    document.getElementById(t.getAttribute('aria-controls')).hidden = !on;
  });
}
tabs.forEach((tab, i) => {
  tab.addEventListener('click', () => selectTab(tab));
  tab.addEventListener('keydown', (e) => {
    if (e.key !== 'ArrowRight' && e.key !== 'ArrowLeft') return;
    const next = tabs[(i + (e.key === 'ArrowRight' ? 1 : tabs.length - 1)) % tabs.length];
    selectTab(next);
    next.focus();
  });
});

const year = document.getElementById('year');
if (year) year.textContent = new Date().getFullYear();
