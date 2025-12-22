<<<<<<< HEAD
# Nursing-Management-System
A Nursing Management System for Web Development and Database Administration courses project
=======
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
>>>>>>> c433e6c (Laravel Setup)

# Laravel + MSSQL Project Setup (PHP 8.2, Windows)

This guide helps you set up the Laravel project using **XAMPP with PHP 8.2** and **Microsoft SQL Server**. Follow it carefully to avoid common SQLSRV and connection issues.

---

## 1. Requirements

- **Windows 10/11** (64-bit)
- **XAMPP** with PHP 8.2  
  [Download here](https://www.apachefriends.org/download.html)
- **Microsoft SQL Server** (Express or Developer edition)
- **ODBC Driver 18 for SQL Server**  
  [Download here](https://learn.microsoft.com/en-us/sql/connect/odbc/download-odbc-driver-for-sql-server)
- **Visual C++ 2015–2022 Redistributable (x64)**  
  [Download here](https://learn.microsoft.com/en-us/cpp/windows/latest-supported-vc-redist)
- **Composer**  
  [Download here](https://getcomposer.org/)

---

## 2. Clone the Repository

```bash
git clone https://github.com/your-username/your-repo.git
cd your-repo
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
Ensure all team members use PHP 8.2 ZTS x64 and the correct SQLSRV DLLs

Test TCP connectivity if migrations fail:

powershell
Copy code
Test-NetConnection -ComputerName 127.0.0.1 -Port 1433
Always remove old DLLs in ext before adding a new version

Use 127.0.0.1 instead of localhost in .env

For remote SQL Server, ensure firewall and ports are configured correctly