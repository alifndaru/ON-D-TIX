<h1 align="center">Selamat datang di ON-DTIX! 👋</h1>

## Apa itu ON-DTIX?

Web ON-DTIX yang dibuat oleh kami\*\*ON-DTIX adalah Website untuk pemesanan Tiket transportasi dengan mudah melalui on.dtix.xyz

## Fitur apa saja yang tersedia di Ticket?

-   Autentikasi Admin
-   User & CRUD
-   Rute & CRUD
-   Transportasi & CRUD
-   Category & CRUD
-   Pemesanan Ticket
-   Payment Gateway
-   Cetak Tiket
-   Dan lain-lain

## Default Account for testing

**Admin Default Account**

-   username: admin
-   Password: admin123

---

## Install

1. **Clone Repository**

```bash
cd Ticket-Laravel
composer install
cp .env.example .env
```

2. **Buka `.env` lalu ubah baris berikut sesuai dengan databasemu yang ingin dipakai**

```bash
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```

3. **Instalasi website**

```bash
php artisan key:generate
php artisan migrate --seed
```

4. **Jalankan website**

```bash
php artisan serve
```
