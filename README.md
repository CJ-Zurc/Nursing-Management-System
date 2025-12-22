<<<<<<< HEAD
# Nursing-Management-System
A Nursing Management System for Web Development and Database Administration courses project
=======
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<!-- Nursing Management System README - cleaned and formatted -->
# Nursing Management System

A Nursing Management System scaffold for teaching and development (Laravel + MSSQL).

<p align="center">
  <a href="https://laravel.com"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300" alt="Laravel Logo"></a>
</p>

> Quick guide to get this project running on Windows (XAMPP + PHP 8.2) with Microsoft SQL Server.

---

## Badges

- Latest stable: [![Packagist Version](https://img.shields.io/packagist/v/laravel/framework)](https://packagist.org/packages/laravel/framework)
- Last commit: [![Last Commit](https://img.shields.io/github/last-commit/CJ-Zurc/Nursing-Management-System)](https://github.com/CJ-Zurc/Nursing-Management-System)

---

## Quick Start

> Copy these commands to your terminal (PowerShell recommended on Windows).

1. Install PHP dependencies

```powershell
composer install
```

2. Create environment and app key

```powershell
copy .env.example .env
php artisan key:generate
```

3. Configure `.env` (database settings) — see "Database (MSSQL)" below.

4. Run migrations

```powershell
php artisan migrate
```

5. Start local server

```powershell
php artisan serve
# then open http://127.0.0.1:8000
```

---

## Requirements

- Windows 10/11 (64-bit)
- XAMPP with PHP 8.2
- Microsoft SQL Server (Express/Developer)
- ODBC Driver 18 for SQL Server
- Visual C++ 2015–2022 Redistributable (x64)
- Composer

---

## Database (MSSQL) — concise setup

1. Enable TCP/IP for your SQL Server instance (SQL Server Configuration Manager).
2. Open TCP port 1433 (or your chosen port) in Windows Firewall.
3. Create database and SQL login for the app.
4. Use this example `.env` block:

```ini
DB_CONNECTION=sqlsrv
DB_HOST=127.0.0.1
DB_PORT=1433
DB_DATABASE=your_database
DB_USERNAME=sa
DB_PASSWORD=YourStrong!Passw0rd
```

Notes:
- Use `127.0.0.1` instead of `localhost` to avoid named-pipe problems.
- Ensure SQL Server allows SQL authentication (mixed mode).

---

## PHP & SQLSRV extensions (XAMPP)

1. Download the SQLSRV drivers matching PHP 8.2 and your build (TS/NTS) from Microsoft.
2. Place the matching DLLs in `C:\xampp\php\ext` (examples):

- `php_sqlsrv_82_ts_x64.dll`
- `php_pdo_sqlsrv_82_ts_x64.dll`

3. In `C:\xampp\php\php.ini`, add lines:

```ini
extension=php_sqlsrv_82_ts_x64.dll
extension=php_pdo_sqlsrv_82_ts_x64.dll
```

4. Restart Apache and verify with `php -m` — expect `sqlsrv` and `pdo_sqlsrv` listed.

---

## Common commands

```powershell
# install dependencies
composer install
# generate key
php artisan key:generate
# run migrations
php artisan migrate
# clear config cache
php artisan config:clear
php artisan cache:clear
```

---

## Troubleshooting

- If migrations fail, confirm `pdo_sqlsrv` & `sqlsrv` are enabled and the ODBC driver matches PHP 8.2.
- Check XAMPP Apache error logs: `C:\xampp\apache\logs\error.log`.
- Test SQL connectivity from PowerShell:

```powershell
Test-NetConnection -ComputerName 127.0.0.1 -Port 1433
```

---

## Contributing

- Add features, run tests (if any), and open a PR.
- Keep secrets out of the repo — `.env` should remain local.

---

## License

This project is licensed under the MIT License.

3. Install PHP Dependencies
bash
Copy code
composer install
4. Configure SQL Server
Open SQL Server Configuration Manager

Go to SQL Server Network Configuration → Protocols for [YourInstance]

Enable TCP/IP

Set TCP Port to 1433 (or your custom port)

Enable SQL Server and Windows Authentication Mode

Create a database and SQL login for the project

Open Windows Firewall → Advanced Settings → Inbound Rules

Allow TCP port 1433

Optionally allow sqlservr.exe executable

Restart SQL Server service

5. Configure PHP SQLSRV Extensions
Go to your XAMPP PHP extensions folder:

makefile
Copy code
C:\xampp\php\ext
Copy only these DLLs for PHP 8.2 ZTS x64:

Copy code
php_sqlsrv_82_ts_x64.dll
php_pdo_sqlsrv_82_ts_x64.dll
⚠️ Important: Remove all other old or mismatched DLLs (x86, NTS, 81/83 DLLs)

Edit C:\xampp\php\php.ini:

ini
Copy code
extension=php_sqlsrv_82_ts_x64.dll
extension=php_pdo_sqlsrv_82_ts_x64.dll
Restart Apache from XAMPP

Verify PHP recognizes the extensions:

bash
Copy code
php -m
Expected output includes:

nginx
Copy code
sqlsrv
pdo_sqlsrv
6. Configure Laravel .env
Copy .env.example to .env:

bash
Copy code
cp .env.example .env
Update database configuration:

env
Copy code
DB_CONNECTION=sqlsrv
DB_HOST=127.0.0.1
DB_PORT=1433
DB_DATABASE=your_db
DB_USERNAME=your_user
DB_PASSWORD=your_password
Use 127.0.0.1 instead of localhost to avoid named pipe issues

Clear Laravel caches:

bash
Copy code
php artisan config:clear
php artisan cache:clear
7. Run Migrations
bash
Copy code
php artisan migrate
8. Start Laravel Server
bash
Copy code
php artisan serve
Open your browser:

cpp
Copy code
http://127.0.0.1:8000

9. Git & Team Setup
Add .gitignore to exclude:

bash
Copy code
/vendor
/node_modules
.env
Push to GitHub:

bash
Copy code
git add .
git commit -m "Initial commit"
git push origin main
Team members should clone the repository, install dependencies, configure .env, and run migrations.

10. Notes / Tips
Ensure everyone use PHP 8.2 ZTS x64 and the correct SQLSRV DLLs

Test TCP connectivity if migrations fail:

powershell
Copy code
Test-NetConnection -ComputerName 127.0.0.1 -Port 1433
Always remove old DLLs in ext before adding a new version

Use 127.0.0.1 instead of localhost in .env

For remote SQL Server, ensure firewall and ports are configured correctly




<p align=center> FOR TEAM MEMBERS FOLLOW </p>


📌 Project Requirements

Windows 10/11

XAMPP (PHP 8.2+)

Composer

Git

Microsoft SQL Server

ODBC Driver 18 for SQL Server

📥 Required Downloads

XAMPP: https://www.apachefriends.org/download.html

Composer: https://getcomposer.org/download/

Git: https://git-scm.com/downloads

SQL Server Express: https://www.microsoft.com/en-us/sql-server/sql-server-downloads

SSMS: https://learn.microsoft.com/en-us/sql/ssms/download-sql-server-management-studio-ssms

ODBC Driver: https://learn.microsoft.com/en-us/sql/connect/odbc/download-odbc-driver-for-sql-server

PHP SQLSRV Driver: https://learn.microsoft.com/en-us/sql/connect/php/download-drivers-php-sql-server

⚙️ Local Setup Instructions
1️⃣ Start XAMPP

Open XAMPP Control Panel

Start Apache

2️⃣ Enable SQL Server PHP Extensions

Copy these files into:

C:\xampp\php\ext


php_sqlsrv_82_ts_x64.dll
php_pdo_sqlsrv_82_ts_x64.dll

Edit php.ini:

extension=php_sqlsrv_82_ts_x64.dll
extension=php_pdo_sqlsrv_82_ts_x64.dll

Restart Apache.

3️⃣ Clone the Repository
git clone https://github.com/your-username/your-repo.git
cd your-repo

4️⃣ Install Dependencies
composer install

5️⃣ Environment Configuration

Copy .env.example:

copy .env.example .env


Edit .env:

DB_CONNECTION=sqlsrv
DB_HOST=127.0.0.1
DB_PORT=1433
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

6️⃣ Generate App Key
php artisan key:generate

7️⃣ Run Migrations
php artisan migrate

8️⃣ Run the Application
php artisan serve


Access:

http://127.0.0.1:8000

🧑‍💻 Team Notes

Do NOT commit .env

Use feature branches

Pull before pushing changes

✅ Tech Stack

Laravel

PHP

Apache (XAMPP)

Microsoft SQL Server

GitHub