# Laravel + MSSQL Web Project Setup (Windows, PHP 8.2)

This document explains how to set up the project after cloning it from GitHub.  
Please follow **all steps in order** to avoid configuration errors.

---

## 1. System Requirements

- **Windows 10 / 11 (64-bit)**
- **XAMPP (PHP 8.2 – ZTS x64)**
  https://www.apachefriends.org/download.html
- **Composer**
  https://getcomposer.org/
- **Node.js (LTS) – includes npm**
  https://nodejs.org/
# Nursing Management System — Setup (Windows, PHP 8.2)

This project uses Laravel with Microsoft SQL Server on Windows. Follow the steps below in order.

---

## Requirements

- Windows 10 or 11 (64-bit)
- XAMPP with PHP 8.2 (ZTS x64) — https://www.apachefriends.org/download.html
- Composer — https://getcomposer.org/
- Node.js (LTS) + npm — https://nodejs.org/
- Microsoft SQL Server (Express or Developer)
- ODBC Driver 18 for SQL Server — https://learn.microsoft.com/en-us/sql/connect/odbc/download-odbc-driver-for-sql-server
- Visual C++ Redistributable 2015–2022 (x64)

---

## 1 — Clone the repository

Replace `<REPOSITORY_URL>` with your repo URL and `<PROJECT_FOLDER>` with the folder name.

```bash
git clone <REPOSITORY_URL>
cd "<PROJECT_FOLDER>"
```

## 2 — Install PHP dependencies

```bash
composer install
```

## 3 — SQL Server configuration (summary)

These steps are performed in SQL Server Configuration Manager / SQL Server Management Studio (SSMS):

- Open **SQL Server Configuration Manager** → **SQL Server Network Configuration** → **Protocols for <INSTANCE>** and enable **TCP/IP**.
- Right-click **TCP/IP** → **Properties** → set the TCP Port to `1433` under IPAll (if default port is used).
- Restart the SQL Server service.
- In SQL Server Management Studio (SSMS): enable **SQL Server and Windows Authentication Mode** (Server Properties → Security) if needed.
- Create a database for the project and create a SQL login (username + password).
- If Windows Firewall blocks connections, add an inbound TCP rule for port `1433`.

## 4 — PHP SQLSRV extensions (PHP 8.2, XAMPP)

Download the matching drivers for PHP 8.2 (thread-safe x64) from Microsoft, then copy the DLLs into your PHP `ext` folder (example path shown).

Files to copy (example names — ensure they match the downloaded files):

- `php_sqlsrv_82_ts_x64.dll`
- `php_pdo_sqlsrv_82_ts_x64.dll`

Example (Windows Explorer): copy the two DLLs to:

```
C:\xampp\php\ext
```

Then edit your `php.ini` (for XAMPP this is typically `C:\xampp\php\php.ini`) and add the extensions:

```ini
extension=php_sqlsrv_82_ts_x64.dll
extension=php_pdo_sqlsrv_82_ts_x64.dll
```

Restart Apache (or XAMPP) after updating `php.ini`.

Verify the extensions are loaded:

```bash
php -m | findstr /R "sqlsrv pdo_sqlsrv"
```

Expected output should include `sqlsrv` and `pdo_sqlsrv`.

## 5 — Environment configuration

Copy `.env.example` to `.env` (if present) and update the database settings:

```
DB_CONNECTION=sqlsrv
DB_HOST=127.0.0.1
DB_PORT=1433
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

Then clear cached config and cache:

```bash
php artisan config:clear
php artisan cache:clear
```

## 6 — Migrations

Run database migrations (ensure `.env` is set correctly before this):

```bash
php artisan migrate
```

If you need to run seeders:

```bash
php artisan db:seed
```

## 7 — Frontend (Vite + npm)

Install frontend dependencies and run the dev server (Vite):

```bash
npm install
npm run dev
```

Run `npm run build` when preparing for production.

## 8 — Serve the application

Open two terminals:

- Terminal A: run Vite dev server

```bash
npm run dev
```

- Terminal B: run Laravel dev server

```bash
php artisan serve
```

The application will typically be available at `http://127.0.0.1:8000` unless `php artisan serve` reports a different URL.

## Troubleshooting notes

- If `php -m` does not list `sqlsrv` or `pdo_sqlsrv`, double-check the DLL filenames match the PHP version (thread-safe vs non-thread-safe) and that `php.ini` is the one loaded by your Apache/PHP setup.
- If migrations fail with SQL Server connection errors, verify `DB_HOST`, `DB_PORT`, and SQL Server authentication settings.

---

If you want, I can also:

- extract common commands into a `scripts/setup.sh` (or `setup.ps1`) for automation, or
- add a short one-paragraph summary at the top for quickstart.

Config changes saved to `README.md`.
