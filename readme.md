1️⃣ Clone project dari GitHub
git clone https://github.com/dkatonn/Agenda-Pimpinan.git
cd Agenda-Pimpinan

2️⃣ Install dependency
composer install

3️⃣ Buat file environment
cp .env.example .env
php artisan key:generate

4️⃣ Setting database masing-masing (INI PENTING)

Di .env MASING-MASING ORANG:

DB_DATABASE=agenda_pimpinan
DB_USERNAME=root
DB_PASSWORD=


📌 Bisa beda-beda:

Kamu pakai MySQL

Dia pakai PostgreSQL

CI pakai SQLite

5️⃣ Buat database kosong

Contoh MySQL:

CREATE DATABASE agenda_pimpinan;

6️⃣ Jalankan MIGRATION (INI KUNCI UTAMA)
php artisan migrate


🎉 BOOM — database langsung jadi
Tanpa kirim .sql