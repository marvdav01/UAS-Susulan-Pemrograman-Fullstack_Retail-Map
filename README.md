# 🗺️ RetailMap - Sistem Pemetaan Cabang Toko

**RetailMap** adalah sebuah prototipe aplikasi web interaktif yang dirancang untuk mengelola dan memvisualisasikan lokasi cabang toko di atas peta dunia secara *real-time*. Proyek ini dibangun untuk memenuhi kriteria penilaian UAS Susulan mata kuliah **Pemrograman Fullstack**.

---

## 🚀 Fitur Utama (Features)

Aplikasi ini mengimplementasikan konsep SPA (*Single Page Application*) dengan kemampuan penuh:

1. **CRUD Lokasi Toko Lengkap**
   - **Create**: Menambah lokasi baru melalui form panel kiri.
   - **Read**: Menampilkan seluruh lokasi terdaftar di tabel dan di atas peta.
   - **Update**: Mengedit nama dan koordinat toko yang sudah ada.
   - **Delete**: Menghapus data toko dari *database* secara permanen.
2. **Peta Interaktif (OpenStreetMap + Leaflet.js)**
   - Semua toko yang ditambahkan akan otomatis muncul sebagai *Pin Marker* berwarna merah di atas peta.
   - Klik pin untuk melihat *Popup Window* yang berisi detail toko.
3. **Smart Click-to-Fill Coordinates**
   - Pengguna tidak perlu repot mengetik titik bujur dan lintang secara manual. Cukup **klik di area mana saja pada peta**, dan kolom Latitude & Longitude pada form akan terisi secara otomatis!
4. **Reactive UI & Flash Notifications**
   - Setiap perubahan (Simpan, Update, Hapus) terjadi secara instan tanpa proses *refresh* halaman secara penuh berkat Inertia.js.
   - Terdapat **Toast Notification** bergaya *glassmorphism* di sudut kanan atas untuk memberi umpan balik (sukses/gagal) pada pengguna.
5. **Modern Premium Design**
   - Antarmuka pengguna (UI) dirancang dengan tema gelap-terang bergaya profesional, tipografi Google Inter, animasi transisi yang mulus, serta panel kontrol dan tabel data yang sangat responsif.

---

## 🛠️ Tumpukan Teknologi (Tech Stack)

Proyek ini dibangun mematuhi kaidah arsitektur MVC (Model-View-Controller) dengan *stack* modern:

- **Backend Framework**: [Laravel 13](https://laravel.com/) (PHP)
- **Frontend Framework**: [Vue.js 3](https://vuejs.org/) (Composition API)
- **Bridge / Routing**: [Inertia.js](https://inertiajs.com/)
- **Database**: [Supabase](https://supabase.com/) (PostgreSQL Cloud)
- **Map Engine**: [Leaflet.js](https://leafletjs.com/) (OpenStreetMap / CartoDB)
- **Styling**: Vanilla CSS (Custom Premium Layout)
- **Bundler**: Vite

---

## ⚙️ Panduan Instalasi (Local Setup)

Untuk menjalankan proyek ini secara lokal, ikuti langkah-langkah berikut:

### 1. Kebutuhan Sistem (Prerequisites)
- PHP >= 8.2
- Composer
- Node.js & NPM
- Akun Supabase (untuk koneksi PostgreSQL)

### 2. Kloning Repositori
```bash
git clone https://github.com/marvdav01/UAS-Susulan-Pemrograman-Fullstack_Retail-Map.git
cd UAS-Susulan-Pemrograman-Fullstack_Retail-Map
```

### 3. Instalasi Dependensi
Jalankan kedua perintah ini untuk menginstal dependensi PHP dan JavaScript:
```bash
composer install
npm install
```

### 4. Konfigurasi Lingkungan (Environment)
1. Salin file contoh `.env`:
   ```bash
   cp .env.example .env
   ```
2. Buka file `.env` dan atur koneksi database Supabase Anda:
   ```env
   DB_CONNECTION=pgsql
   DB_HOST=aws-0-[REGION].pooler.supabase.com
   DB_PORT=6543
   DB_DATABASE=postgres
   DB_USERNAME=postgres.[YOUR_PROJECT_ID]
   DB_PASSWORD=[YOUR_SUPABASE_PASSWORD]
   ```
3. *(Penting)* Pastikan ekstensi `pdo_pgsql` dan `pgsql` telah diaktifkan di dalam file `php.ini` lokal Anda.

### 5. Setup Database & Aplikasi
Jalankan perintah berikut secara berurutan untuk men-generate kunci aplikasi dan memigrasi struktur tabel database:
```bash
php artisan key:generate
php artisan migrate
```

### 6. Jalankan Server
Buka 2 tab terminal terpisah, dan jalankan perintah berikut secara bersamaan:

**Terminal 1 (Backend):**
```bash
php artisan serve
```

**Terminal 2 (Frontend bundler):**
```bash
npm run dev
```

Buka **`http://localhost:8000`** di browser Anda, dan aplikasi RetailMap siap digunakan!

---
*Dibuat oleh Marviel David untuk keperluan UAS Susulan.*
