// ============================================================
//  SISTEM INFORMASI PERPUSTAKAAN — script.js
//  TM3: const/let, arrow function, array methods,
//       DOM manipulation, event delegation, localStorage
// ============================================================

// ─────────────────────────────────────────
//  1. DATA — array of objects + localStorage
// ─────────────────────────────────────────
const STORAGE_KEY = 'perpustakaan_data';

// Load dari localStorage, atau pakai data awal
let dataPeminjaman = JSON.parse(localStorage.getItem(STORAGE_KEY)) || [
  { id: 1, idAnggota: 'ANG-001', nama: 'Budi Santoso',  judul: 'Laskar Pelangi',          kode: 'BK-001', kategori: 'Fiksi',            jumlah: 1, tglPinjam: '2026-03-01', tglKembali: '2026-03-08', petugas: 'Siti',  keterangan: '',        status: 'Dikembalikan' },
  { id: 2, idAnggota: 'ANG-002', nama: 'Siti Rahayu',   judul: 'Bumi Manusia',            kode: 'BK-002', kategori: 'Fiksi',            jumlah: 1, tglPinjam: '2026-03-02', tglKembali: '2026-03-09', petugas: 'Budi',  keterangan: '',        status: 'Dipinjam' },
  { id: 3, idAnggota: 'ANG-003', nama: 'Ahmad Fauzi',   judul: 'Sejarah Indonesia Modern', kode: 'BK-003', kategori: 'Sejarah',          jumlah: 1, tglPinjam: '2026-03-03', tglKembali: '2026-03-10', petugas: 'Rina',  keterangan: '',        status: 'Dipinjam' },
  { id: 4, idAnggota: 'ANG-004', nama: 'Dewi Lestari',  judul: 'Pemrograman Web Dasar',   kode: 'BK-004', kategori: 'Teknologi',        jumlah: 1, tglPinjam: '2026-03-04', tglKembali: '2026-03-11', petugas: 'Andi',  keterangan: 'Terlambat', status: 'Terlambat' },
  { id: 5, idAnggota: 'ANG-005', nama: 'Rizky Pratama', judul: 'Fisika Untuk SMA',        kode: 'BK-005', kategori: 'Ilmu Pengetahuan', jumlah: 1, tglPinjam: '2026-03-05', tglKembali: '2026-03-12', petugas: 'Siti',  keterangan: '',        status: 'Dipinjam' },
];

const saveData = () => localStorage.setItem(STORAGE_KEY, JSON.stringify(dataPeminjaman));

let editId = null;        // null = mode tambah, angka = mode edit
let filterKategori = '';  // filter aktif dari sidebar checkbox
let filterStatus   = '';  // filter status aktif
let searchQuery    = '';  // pencarian real-time

// ─────────────────────────────────────────
//  2. REFERENSI DOM — aman di semua halaman
// ─────────────────────────────────────────
const form      = document.querySelector('#peminjaman form');
const tabelBody = document.querySelector('#daftar-buku tbody');
const submitBtn = form ? form.querySelector('button[type="submit"]') : null;
const resetBtn  = form ? form.querySelector('button[type="reset"]')  : null;
const searchInput   = document.getElementById('search-input');
const statTotal     = document.getElementById('stat-total');
const statDipinjam  = document.getElementById('stat-dipinjam');
const statTersedia  = document.getElementById('stat-tersedia');
const statTerlambat = document.getElementById('stat-terlambat');

// Field form — hanya ada di peminjaman.html
const fIdAnggota  = form ? form.querySelector('[name="id_anggota"]')     : null;
const fNama       = form ? form.querySelector('[name="nama_peminjam"]')  : null;
const fJudul      = form ? form.querySelector('[name="judul_buku"]')     : null;
const fKode       = form ? form.querySelector('[name="kode_buku"]')      : null;
const fKategori   = form ? form.querySelector('[name="kategori_buku"]')  : null;
const fJumlah     = form ? form.querySelector('[name="jumlah_buku"]')    : null;
const fTglPinjam  = form ? form.querySelector('[name="tanggal_pinjam"]') : null;
const fTglKembali = form ? form.querySelector('[name="tanggal_kembali"]'): null;
const fPetugas    = form ? form.querySelector('[name="petugas"]')        : null;
const fKeterangan = form ? form.querySelector('[name="keterangan"]') : null;

// ─────────────────────────────────────────
//  3. STATISTIK — update otomatis
// ─────────────────────────────────────────
const updateStatistik = () => {
  const total     = dataPeminjaman.length;
  const dipinjam  = dataPeminjaman.filter(d => d.status === 'Dipinjam').length;
  const kembali   = dataPeminjaman.filter(d => d.status === 'Dikembalikan').length;
  const terlambat = dataPeminjaman.filter(d => d.status === 'Terlambat').length;

  // Sidebar statistik
  if (statTotal)     statTotal.textContent     = `Total Peminjaman: ${total}`;
  if (statDipinjam)  statDipinjam.textContent  = `Buku Dipinjam: ${dipinjam}`;
  if (statTersedia)  statTersedia.textContent  = `Dikembalikan: ${kembali}`;
  if (statTerlambat) statTerlambat.textContent = `Peminjaman Terlambat: ${terlambat}`;

  // Beranda stat cards
  const bTotal     = document.getElementById('bstat-total');
  const bDipinjam  = document.getElementById('bstat-dipinjam');
  const bKembali   = document.getElementById('bstat-kembali');
  const bTerlambat = document.getElementById('bstat-terlambat');
  if (bTotal)     bTotal.textContent     = total;
  if (bDipinjam)  bDipinjam.textContent  = dipinjam;
  if (bKembali)   bKembali.textContent   = kembali;
  if (bTerlambat) bTerlambat.textContent = terlambat;
};

// ─────────────────────────────────────────
//  4. RENDER TABEL — array methods
// ─────────────────────────────────────────
const statusBadge = (status) => {
  const map = {
    'Dikembalikan': 'badge-success',
    'Dipinjam':     'badge-warning',
    'Terlambat':    'badge-danger',
  };
  return `<span class="badge ${map[status] || ''}">${status}</span>`;
};

const renderTabel = () => {
  // Filter + search menggunakan arrow function & array methods
  const tampil = dataPeminjaman
    .filter(d => filterKategori === '' || d.kategori === filterKategori)
    .filter(d => filterStatus   === '' || d.status   === filterStatus)
    .filter(d => {
      if (!searchQuery) return true;
      const q = searchQuery.toLowerCase();
      return d.nama.toLowerCase().includes(q)  ||
             d.judul.toLowerCase().includes(q) ||
             d.idAnggota.toLowerCase().includes(q) ||
             d.kode.toLowerCase().includes(q);
    });

  if (tampil.length === 0) {
    tabelBody.innerHTML = `<tr><td colspan="9" style="text-align:center;padding:24px;color:var(--text-muted);">Tidak ada data yang cocok.</td></tr>`;
    return;
  }

  // map() untuk render setiap baris
  tabelBody.innerHTML = tampil.map((d, i) => `
    <tr data-id="${d.id}">
      <td>${i + 1}</td>
      <td>${d.idAnggota}</td>
      <td>${d.nama}</td>
      <td>${d.judul}</td>
      <td>${d.kategori}</td>
      <td>${d.tglPinjam}</td>
      <td>${d.tglKembali}</td>
      <td>${statusBadge(d.status)}</td>
      <td>
        <button class="btn-aksi btn-edit"   data-id="${d.id}">Edit</button>
        <button class="btn-aksi btn-hapus"  data-id="${d.id}">Hapus</button>
      </td>
    </tr>
  `).join('');

  updateStatistik();
};

// ─────────────────────────────────────────
//  5. VALIDASI FORM — custom validation
// ─────────────────────────────────────────
const clearError = (field) => {
  field.classList.remove('input-error');
  const msg = field.parentElement.querySelector('.error-msg');
  if (msg) msg.remove();
};

const showError = (field, pesan) => {
  field.classList.add('input-error');
  clearError(field);
  field.classList.add('input-error');
  const msg = document.createElement('span');
  msg.className = 'error-msg';
  msg.textContent = pesan;
  field.parentElement.appendChild(msg);
};

const validasiForm = () => {
  let valid = true;
  const fields = [fIdAnggota, fNama, fJudul, fKode, fJumlah, fTglPinjam, fTglKembali, fPetugas];

  // Hapus semua error dulu
  fields.forEach(clearError);
  if (fKategori) clearError(fKategori);

  // Cek tiap field wajib
  if (!fIdAnggota?.value.trim())  { showError(fIdAnggota,  'ID Anggota wajib diisi'); valid = false; }
  if (!fNama?.value.trim())       { showError(fNama,       'Nama Peminjam wajib diisi'); valid = false; }
  if (!fJudul?.value.trim())      { showError(fJudul,      'Judul Buku wajib diisi'); valid = false; }
  if (!fKode?.value.trim())       { showError(fKode,       'Kode Buku wajib diisi'); valid = false; }
  if (!fKategori?.value)          { showError(fKategori,   'Pilih Kategori Buku'); valid = false; }
  if (!fJumlah?.value || fJumlah.value < 1) { showError(fJumlah, 'Jumlah minimal 1'); valid = false; }
  if (!fTglPinjam?.value)         { showError(fTglPinjam,  'Tanggal Pinjam wajib diisi'); valid = false; }
  if (!fTglKembali?.value)        { showError(fTglKembali, 'Tanggal Kembali wajib diisi'); valid = false; }
  if (!fPetugas?.value.trim())    { showError(fPetugas,    'Nama Petugas wajib diisi'); valid = false; }

  // Validasi tanggal kembali harus setelah tanggal pinjam
  if (fTglPinjam?.value && fTglKembali?.value) {
    if (fTglKembali.value <= fTglPinjam.value) {
      showError(fTglKembali, 'Tanggal kembali harus setelah tanggal pinjam');
      valid = false;
    }
  }

  return valid;
};

// ─────────────────────────────────────────
//  6. SUBMIT FORM — tambah / update
// ─────────────────────────────────────────
if (form) form.addEventListener('submit', (e) => {
  e.preventDefault();
  if (!validasiForm()) return;

  const data = {
    idAnggota:  fIdAnggota?.value.trim(),
    nama:       fNama?.value.trim(),
    judul:      fJudul?.value.trim(),
    kode:       fKode?.value.trim(),
    kategori:   fKategori?.value,
    jumlah:     parseInt(fJumlah?.value),
    tglPinjam:  fTglPinjam?.value,
    tglKembali: fTglKembali?.value,
    petugas:    fPetugas?.value.trim(),
    keterangan: fKeterangan?.value.trim(),
    status:     'Dipinjam',
  };

  if (editId !== null) {
    // Mode edit — update array pakai map()
    dataPeminjaman = dataPeminjaman.map(d =>
      d.id === editId ? { ...d, ...data } : d
    );
    editId = null;
    submitBtn.textContent = 'Simpan';
    submitBtn.style.background = '';
    showToast('Data berhasil diperbarui!', 'success');
  } else {
    // Mode tambah — generate id baru
    const newId = dataPeminjaman.length > 0
      ? Math.max(...dataPeminjaman.map(d => d.id)) + 1
      : 1;
    dataPeminjaman.push({ id: newId, ...data });
    showToast('Data berhasil disimpan!', 'success');
  }

  saveData();
  renderTabel();
  form.reset();
  // Scroll ke tabel
  document.getElementById('daftar-buku')?.scrollIntoView({ behavior: 'smooth' });
});

// Reset button
if (resetBtn) resetBtn.addEventListener('click', () => {
  editId = null;
  submitBtn.textContent = 'Simpan';
  submitBtn.style.background = '';
  // Hapus semua error
  form.querySelectorAll('.input-error').forEach(el => el.classList.remove('input-error'));
  form.querySelectorAll('.error-msg').forEach(el => el.remove());
});

// ─────────────────────────────────────────
//  7. EVENT DELEGATION — edit & hapus
// ─────────────────────────────────────────
if (tabelBody) tabelBody.addEventListener('click', (e) => {
  const target = e.target;
  const id = parseInt(target.dataset.id);
  if (!id) return;

  // Tombol EDIT
  if (target.classList.contains('btn-edit')) {
    const item = dataPeminjaman.find(d => d.id === id);
    if (!item) return;

    if (form) {
      // Sudah di halaman peminjaman — isi form langsung
      editId = id;
      if (fIdAnggota)  fIdAnggota.value  = item.idAnggota;
      if (fNama)       fNama.value       = item.nama;
      if (fJudul)      fJudul.value      = item.judul;
      if (fKode)       fKode.value       = item.kode;
      if (fKategori)   fKategori.value   = item.kategori;
      if (fJumlah)     fJumlah.value     = item.jumlah;
      if (fTglPinjam)  fTglPinjam.value  = item.tglPinjam;
      if (fTglKembali) fTglKembali.value = item.tglKembali;
      if (fPetugas)    fPetugas.value    = item.petugas;
      if (fKeterangan) fKeterangan.value = item.keterangan;
      submitBtn.textContent = 'Update Data';
      submitBtn.style.background = 'linear-gradient(135deg, #e67e22, #d35400)';
      document.getElementById('peminjaman')?.scrollIntoView({ behavior: 'smooth' });
      showToast('Mode edit aktif — ubah data lalu klik Update Data', 'info');
    } else {
      // Di halaman lain (daftar.html) — simpan id ke localStorage, pindah ke form
      localStorage.setItem('edit_id', id);
      window.location.href = 'peminjaman.html';
    }
  }

  // Tombol HAPUS
  if (target.classList.contains('btn-hapus')) {
    const item = dataPeminjaman.find(d => d.id === id);
    if (!item) return;

    // Konfirmasi dialog
    const konfirmasi = confirm(`Hapus data peminjaman "${item.nama} — ${item.judul}"?\n\nData yang dihapus tidak dapat dikembalikan.`);
    if (!konfirmasi) return;

    // filter() untuk hapus
    dataPeminjaman = dataPeminjaman.filter(d => d.id !== id);
    saveData();
    renderTabel();
    showToast('Data berhasil dihapus.', 'danger');
  }
});

// ─────────────────────────────────────────
//  8. PENCARIAN REAL-TIME
// ─────────────────────────────────────────
if (searchInput) {
  searchInput.addEventListener('input', (e) => {
    searchQuery = e.target.value.trim();
    renderTabel();
  });
}

// ─────────────────────────────────────────
//  9. FILTER CHECKBOX SIDEBAR — kategori & status
// ─────────────────────────────────────────
document.addEventListener('change', (e) => {
  const target = e.target;

  // Filter kategori
  if (target.closest('.filter-kategori')) {
    filterKategori = target.checked ? target.value : '';
    // Uncheck yang lain (single select behavior)
    document.querySelectorAll('.filter-kategori input[type="checkbox"]').forEach(cb => {
      if (cb !== target) cb.checked = false;
    });
    if (!target.checked) filterKategori = '';
    if (tabelBody) renderTabel();
  }

  // Filter status
  if (target.closest('.filter-status')) {
    filterStatus = target.checked ? target.value : '';
    document.querySelectorAll('.filter-status input[type="checkbox"]').forEach(cb => {
      if (cb !== target) cb.checked = false;
    });
    if (!target.checked) filterStatus = '';
    if (tabelBody) renderTabel();
  }
});

// ─────────────────────────────────────────
//  10. TOAST NOTIFICATION
// ─────────────────────────────────────────
const showToast = (pesan, tipe = 'success') => {
  // Hapus toast lama jika ada
  document.querySelectorAll('.toast').forEach(t => t.remove());

  const toast = document.createElement('div');
  toast.className = `toast toast-${tipe}`;
  toast.textContent = pesan;
  document.body.appendChild(toast);

  // Trigger animasi masuk
  requestAnimationFrame(() => toast.classList.add('toast-show'));

  // Hilang otomatis setelah 3 detik
  setTimeout(() => {
    toast.classList.remove('toast-show');
    setTimeout(() => toast.remove(), 400);
  }, 3000);
};

// ─────────────────────────────────────────
//  11. INISIALISASI — deteksi dari elemen DOM
// ─────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {

  // Update statistik di semua halaman
  updateStatistik();

  // Kalau ada tabel → render
  if (tabelBody) {
    renderTabel();
  }

  // Kalau ada form dan ada edit_id dari halaman daftar → isi form otomatis
  if (form) {
    const pendingEditId = localStorage.getItem('edit_id');
    if (pendingEditId) {
      const id = parseInt(pendingEditId);
      const item = dataPeminjaman.find(d => d.id === id);
      if (item) {
        editId = id;
        if (fIdAnggota)  fIdAnggota.value  = item.idAnggota;
        if (fNama)       fNama.value       = item.nama;
        if (fJudul)      fJudul.value      = item.judul;
        if (fKode)       fKode.value       = item.kode;
        if (fKategori)   fKategori.value   = item.kategori;
        if (fJumlah)     fJumlah.value     = item.jumlah;
        if (fTglPinjam)  fTglPinjam.value  = item.tglPinjam;
        if (fTglKembali) fTglKembali.value = item.tglKembali;
        if (fPetugas)    fPetugas.value    = item.petugas;
        if (fKeterangan) fKeterangan.value = item.keterangan;
        if (submitBtn) {
          submitBtn.textContent = 'Update Data';
          submitBtn.style.background = 'linear-gradient(135deg, #e67e22, #d35400)';
        }
        showToast('Mode edit aktif — ubah data lalu klik Update Data', 'info');
      }
      localStorage.removeItem('edit_id');
    }
  }

});
