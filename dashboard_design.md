# Desain Dashboard Admin FinCo (Bahasa Indonesia)

**Gambaran Umum**
Desain ini mengusung tampilan SaaS/fintech modern, profesional, dan elegan dengan nuansa bersih dan minimalis. Warna utama biru **#2563EB**, dipadukan dengan putih dan abu‑abu muda. Menggunakan font **Inter** (fallback **Poppins**).

---

## Tata Letak
```
+---------------------------+-------------------------------+
|          Sidebar          |           Header              |
|  (ikon + label ringkas)   |  (breadcrumb | search |       |
|                           |   notifikasi | profil)        |
+---------------------------+-------------------------------+
|                     Area Konten Utama (responsif)    |
|  ┌───────────────────┐  ┌───────────────────────┐       |
|  │ Kartu Statistik 1│  │ Kartu Statistik 2    │ …     |
|  └───────────────────┘  └───────────────────────┘       |
|  ┌───────────────────────────────────────────────┐    |
|  │ Grafik Interaktif (perkembangan data)          │    |
|  └───────────────────────────────────────────────┘    |
|  ┌───────────────────────────────────────────────┐    |
|  │ Tabel Aktivitas Terbaru (status berwarna)      │    |
|  └───────────────────────────────────────────────┘    |
+---------------------------------------------------+----+
```

### Komponen Utama
| Komponen | Penjelasan |
|----------|------------|
| **Sidebar** | Lebar tetap ~240 px (dapat diperkecil ke ikon‑only pada layar kecil). Ikon bergaya line, label singkat, item aktif ditandai warna biru utama. |
| **Header** | Breadcrumb di kiri, kotak pencarian, ikon notifikasi dengan badge, avatar admin di kanan. |
| **Kartu Statistik** | 4‑6 kartu per baris, angka besar (contoh: **3 842**) dengan ikon, serta indikator naik/turun berwarna hijau/merah. Latar putih, bayangan lembut, radius 18 px. |
| **Grafik Interaktif** | Line chart dengan gradasi biru, tooltip hover menampilkan nilai detail. | 
| **Tabel Aktivitas** | Baris kompak, warna background berganti‑ganti abu‑abu muda, status badge berwarna (Hijau = Sukses, Oranye = Pending, Merah = Gagal). |
| **Kartu Akses Cepat** | Kartu kecil berikon & label untuk fungsi umum (misalnya “Tambah User”). |

### Styling (CSS Tanpa Framework)
> **Catatan:** Kode CSS di bawah dapat disimpan di `assets/dashboard.css` dan disertakan pada halaman HTML dengan `<link rel="stylesheet" href="assets/dashboard.css">`.

```css
/* Variabel Global */
:root {
  --blue: #2563EB;
  --gray-light: #F5F5F5;
  --gray-mid: #E0E0E0;
  --radius: 18px;
  --font-main: 'Inter', 'Poppins', sans-serif;
}

body {
  font-family: var(--font-main);
  background: var(--gray-light);
  margin: 0;
  color: #212121;
}

/* Layout */
.wrapper { display: flex; min-height: 100vh; }
.sidebar { width: 240px; background: #fff; box-shadow: 2px 0 6px rgba(0,0,0,.05); padding: 20px; border-radius: 0 var(--radius) var(--radius) 0; }
.main { flex: 1; display: flex; flex-direction: column; }

/* Header */
.header { display: flex; align-items: center; justify-content: space-between; padding: 12px 24px; background: #fff; box-shadow: 0 2px 4px rgba(0,0,0,.04); border-radius: 0 0 var(--radius) var(--radius); }

/* Kartu Statistik */
.stat-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 24px; padding: 24px; }
.stat-card { background: #fff; border-radius: var(--radius); box-shadow: 0 2px 6px rgba(0,0,0,.06); padding: 20px; display: flex; align-items: center; gap: 16px; }
.stat-card .icon { font-size: 2rem; color: var(--blue); }
.stat-card .value { font-size: 1.75rem; font-weight: 600; }
.stat-card .change { font-size: .9rem; }
.stat-card .up { color: #2E7D32; }
.stat-card .down { color: #C62828; }

/* Grafik */
.chart-box { background: #fff; border-radius: var(--radius); box-shadow: 0 2px 6px rgba(0,0,0,.06); margin: 0 24px 24px; padding: 24px; }

/* Tabel */
.activity-table { width: 100%; border-collapse: collapse; margin: 0 24px 24px; }
.activity-table th, .activity-table td { padding: 12px 16px; text-align: left; }
.activity-table thead { background: var(--gray-mid); }
.activity-table tbody tr:nth-child(even) { background: var(--gray-light); }
.status-tag { display: inline-block; padding: 2px 8px; border-radius: 12px; font-size: .85rem; color: #fff; }
.status-success { background: #2E7D32; }
.status-pending { background: #FF9800; }
.status-failed  { background: #C62828; }

/* Efek Hover */
button:hover,
.stat-card:hover,
.quick-tile:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,.12); }
```

### Responsif
| Breakpoint | Perubahan Layout |
|-----------|-------------------|
| **≥ 1200 px** | Sidebar penuh, grid statistik 4 kolom |
| **≥ 768 px & < 1200 px** | Sidebar menyusut ke ikon‑only (≈ 80 px); grid 2‑kolom |
| **< 768 px** | Sidebar menjadi drawer (slide‑in); header menumpuk; kartu & grafik full‑width |

---

## Cara Mengintegrasikan
1. **Buat folder** `assets/` di dalam proyek FinCo (`c:\finco\assets`).
2. Simpan kode CSS di atas ke file `dashboard.css` di dalam folder tersebut.
3. Tambahkan file HTML (atau Blade/Laravel view) yang mengikuti struktur layout di atas dan **link** ke `dashboard.css`.
4. Isi konten dinamis (statistik, grafik, tabel) menggunakan data API Laravel Anda.
5. Untuk grafik interaktif, Anda dapat memakai **Chart.js** atau **ApexCharts** – cukup masukkan skrip dan inisialisasi pada elemen `.chart-box`.

Jika ada pertanyaan lebih lanjut atau butuh contoh kode HTML/Blade lengkap, beri tahu saya! 🚀
