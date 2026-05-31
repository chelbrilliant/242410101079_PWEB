/* ══════════════════════════════════════════════
   Sistem Informasi Perpustakaan UPA UNEJ
   script.js — Global JavaScript
   ══════════════════════════════════════════════ */

document.addEventListener('DOMContentLoaded', function () {

  // ── 1. Auto-dismiss flash message setelah 4 detik ──────────────────
  const flash = document.getElementById('flash-msg');
  if (flash) {
    setTimeout(() => {
      flash.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
      flash.style.opacity    = '0';
      flash.style.transform  = 'translateX(120%)';
      setTimeout(() => flash.remove(), 500);
    }, 4000);
  }

  // ── 2. Scroll to top button ─────────────────────────────────────────
  const scrollBtn = document.createElement('button');
  scrollBtn.id        = 'scrollTopBtn';
  scrollBtn.innerHTML = '↑';
  scrollBtn.title     = 'Kembali ke atas';
  scrollBtn.style.cssText = `
    position: fixed; bottom: 24px; right: 24px; z-index: 999;
    width: 42px; height: 42px; border-radius: 50%;
    background: linear-gradient(135deg, #1b4f72, #2e86c1);
    color: white; border: none; font-size: 1.1rem; font-weight: 700;
    cursor: pointer; box-shadow: 0 4px 14px rgba(0,0,0,0.2);
    display: none; align-items: center; justify-content: center;
    transition: opacity 0.3s, transform 0.3s;
    font-family: sans-serif;
  `;
  document.body.appendChild(scrollBtn);

  // Tampilkan tombol saat scroll > 300px
  window.addEventListener('scroll', function () {
    if (window.scrollY > 300) {
      scrollBtn.style.display = 'flex';
    } else {
      scrollBtn.style.display = 'none';
    }
  });

  scrollBtn.addEventListener('click', function () {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });

  // ── 3. Highlight baris tabel saat hover ────────────────────────────
  // DOM manipulation: tambah efek hover ke semua baris tabel
  const rows = document.querySelectorAll('table tbody tr');
  rows.forEach(row => {
    row.style.transition = 'background 0.15s';
    row.addEventListener('mouseover', () => {
      if (!row.style.backgroundColor || row.style.backgroundColor === 'transparent') {
        row.dataset.origBg    = row.style.background;
        row.style.background  = '#f5f9ff';
      }
    });
    row.addEventListener('mouseout', () => {
      row.style.background = row.dataset.origBg || '';
    });
  });

  // ── 4. Konfirmasi sebelum submit form DELETE (global fallback) ──────
  // Tangkap semua form method DELETE yang belum punya onclick sendiri
  document.querySelectorAll('form[data-confirm]').forEach(form => {
    form.addEventListener('submit', function (e) {
      const pesan = form.dataset.confirm || 'Yakin ingin menghapus data ini?';
      if (!confirm(pesan)) e.preventDefault();
    });
  });

  // ── 5. Input focus efek border ──────────────────────────────────────
  document.querySelectorAll('input, select, textarea').forEach(el => {
    el.addEventListener('focus', () => {
      el.style.outline    = 'none';
      el.style.borderColor = '#2e86c1';
      el.style.boxShadow  = '0 0 0 3px rgba(46,134,193,0.15)';
    });
    el.addEventListener('blur', () => {
      el.style.boxShadow = 'none';
    });
  });

  // ── 6. Aktifkan nav link sesuai halaman aktif ───────────────────────
  const currentPath = window.location.pathname;
  document.querySelectorAll('nav ul li a').forEach(link => {
    const href = link.getAttribute('href');
    if (href && currentPath.startsWith(href) && href !== '/') {
      link.classList.add('nav-active');
    }
  });

});
