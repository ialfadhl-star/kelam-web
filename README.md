# KELAM — Streetwear Premium (Demo)

Toko online streetwear premium. Dibangun dengan **Laravel 13 + PostgreSQL**.
Demo portofolio (demo fashion #2). Mood: dark / charcoal / off-white dengan satu aksen **rust**.

> **Tagline:** *Kelam. Bukan untuk semua.*

---

## Yang sudah ada

**Storefront**
- Beranda: hero, navigasi kategori, produk unggulan, tentang brand, manifesto + Instagram, testimoni.
- Katalog dengan filter **kategori + warna + ukuran** + urutkan harga. *(filter warna & ukuran = upgrade di luar baseline Paket Starter — lihat catatan di bawah.)*
- Halaman produk: pilih varian warna/ukuran, indikator stok live, deskripsi bahan.
- Keranjang berbasis **session server-side** (bukan localStorage), badge cart update tanpa reload (AJAX).
- Checkout modern (simulasi): review → alamat → metode bayar (Transfer Bank / QRIS / COD) → konfirmasi pesanan dengan instruksi bayar sesuai metode.
- Halaman tentang & kontak/FAQ.
- Responsive + hamburger menu mobile berfungsi.

**Admin panel** (`/admin`)
- Login aman: password **di-hash bcrypt**, rate limit `throttle:5,1`, CSRF aktif.
- Kelola produk (CRUD) + editor varian (warna/ukuran/stok) + gradient placeholder.
- Kelola pesanan: filter status, detail, ubah status, input kurir & no. resi.
- Pengaturan toko: nama, kontak, ongkir, info rekening, teks banner.

---

## ⚠️ Masih placeholder (ganti sebelum dipakai serius)

- **Foto produk** — semua masih gradient placeholder, ditandai jelas di halaman produk. Ganti dengan foto asli.
- **Testimoni** di beranda — 3 testimoni contoh, ditandai eksplisit di halaman. Ganti dengan testimoni pelanggan asli.
- **Info rekening & kontak** di Pengaturan admin — isi dengan data toko sebenarnya.
- **Checkout = simulasi** — mencatat pesanan ke database, tapi **belum** terhubung payment gateway asli (Midtrans/Xendit). UI tidak mengklaim pembayaran otomatis. Integrasi gateway = add-on di luar paket dasar.

---

## Menjalankan secara lokal (Windows)

Prasyarat: PHP 8.3+ (dengan ekstensi `pdo_pgsql`), Composer, PostgreSQL berjalan.

```bash
# 1. Dependencies
composer install

# 2. Environment
cp .env.example .env
php artisan key:generate
# Edit .env → isi DB_PASSWORD (dan DB_HOST/PORT/DATABASE/USERNAME sesuai instansmu)

# 3. Buat database + user di PostgreSQL (via psql / pgAdmin sebagai superuser):
#   CREATE USER kelam WITH PASSWORD '...';
#   CREATE DATABASE kelam_db OWNER kelam ENCODING 'UTF8';

# 4. Migrasi + seed (produk contoh, pengaturan, akun admin)
php artisan migrate --seed

# 5. Jalankan
php artisan serve
```

Buka **http://127.0.0.1:8000** — storefront.
Admin: **http://127.0.0.1:8000/admin/login**

### Akun admin
Dibuat oleh `AdminSeeder`. Email default: `admin@kelam.id`.
Password: kalau `ADMIN_PASSWORD` di `.env` kosong, seeder **generate password acak dan menampilkannya sekali** di terminal saat `db:seed` — catat saat itu. Tidak ada password plaintext yang disimpan permanen di mana pun.

---

## Deploy ke Railway

1. Push repo ke GitHub, buat project baru di Railway dari repo itu.
2. Tambahkan **plugin PostgreSQL** (Railway → Add → Database → PostgreSQL). Railway otomatis menyediakan `DATABASE_URL`.
   Config `config/database.php` sudah membaca `DATABASE_URL` secara otomatis — tidak perlu set `DB_*` manual.
3. Set environment variable di Railway:
   - `APP_KEY` → **generate baru** untuk production (`php artisan key:generate --show`), **jangan** reuse APP_KEY development.
   - `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://<domain-railway-mu>`.
   - `ADMIN_EMAIL` + `ADMIN_PASSWORD` (set password kuat untuk production).
4. `nixpacks.toml` sudah mengatur build + menjalankan `php artisan migrate --force` saat start.
5. Seed data awal (produk contoh) di production — jalankan **sekali** via Railway shell:
   `php artisan db:seed --force`

---

## Catatan konsistensi paket

Demo ini menjaga scope **Paket Starter** yang sama dengan demo #1 (Khaleeva), dengan **satu upgrade yang ditandai sengaja**: filter warna & ukuran di katalog. Sisanya (admin panel, checkout, manajemen pesanan) adalah paritas fitur. Integrasi payment gateway asli, foto/CDN, dan sejenisnya tetap di luar baseline.

## Stack teknis
Laravel 13 · PostgreSQL · Blade · CSS custom (tanpa build step Node) · vanilla JS untuk interaksi · session-based cart.
