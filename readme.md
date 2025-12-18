# Agenda Pimpinan

Project ini dibangun menggunakan **Laravel 12** dan **Livewire 3**.
Repository ini hanya berisi **source code**, bukan database atau data asli.

---

## Cara Menjalankan Project

### 1. Clone project dari GitHub

```bash
git clone https://github.com/dkatonn/Agenda-Pimpinan.git
cd Agenda-Pimpinan
```

---

### 2. Install dependency

Pastikan sudah terinstall **PHP**, **Composer**, dan database yang ingin digunakan.

```bash
composer install
```

---

### 3. Buat file environment

```bash
cp .env.example .env
php artisan key:generate
```

---

### 4. Setting database (penting)

Atur koneksi database di file `.env` masing-masing.

Contoh menggunakan MySQL:

```env
DB_CONNECTION=mysql
DB_DATABASE=agenda_pimpinan
DB_USERNAME=root
DB_PASSWORD=
```

Catatan:

* Setiap orang bisa berbeda
* Kamu bisa pakai MySQL
* Orang lain bisa pakai PostgreSQL
* CI / GitHub Actions biasanya pakai SQLite

---

### 5. Buat database kosong

Contoh MySQL:

```sql
CREATE DATABASE agenda_pimpinan;
```

---

### 6. Jalankan migration

Ini langkah utama untuk membuat struktur database.

```bash
php artisan migrate
```

Database akan otomatis terbentuk dari migration, tanpa perlu file `.sql`.

---

### 7. (Opsional) Jalankan seeder

Jika ingin data awal (contoh user admin / superadmin):

```bash
php artisan db:seed
```

---

## Catatan Penting

* File `.env` tidak ikut di-push ke GitHub
* Database tidak pernah di-push ke GitHub
* Semua struktur database berasal dari migration
* Project ini aman untuk kerja tim dan open source

---

## Workflow Development Singkat

Setiap kali ada perubahan code:

```bash
git add .
git commit -m "pesan perubahan"
git push
```

---

Kalau ada kendala saat setup atau development, silakan cek dokumentasi Laravel atau sesuaikan dengan environment masing-masing.
