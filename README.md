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
- **Microsoft SQL Server** (Express or Developer)
- **ODBC Driver 18 for SQL Server**
  https://learn.microsoft.com/en-us/sql/connect/odbc/download-odbc-driver-for-sql-server
- **Visual C++ Redistributable 2015–2022 (x64)**
  https://learn.microsoft.com/en-us/cpp/windows/latest-supported-vc-redist

---

## 2. Clone the Repository

```bash
git clone <REPOSITORY_URL>
cd <PROJECT_FOLDER>


3. PHP Dependencies (Laravel)

Install backend dependencies:

composer install


4. SQL Server Configuration (IMPORTANT)

Open SQL Server Configuration Manager

Go to:

SQL Server Network Configuration

Protocols for your SQL instance

Enable TCP/IP

Set TCP Port to 1433

Restart SQL Server service

Authentication

Ensure SQL Server and Windows Authentication Mode is enabled

Create a database for the project

Create a SQL login (username + password)

Firewall

Open Windows Defender Firewall → Advanced Settings

Add Inbound Rule:

TCP

Port 1433

Allow connection



5. PHP SQLSRV Extensions (PHP 8.2)

Go to:

C:\xampp\php\ext

COPY ONLY THESE FILES

php_sqlsrv_82_ts_x64.dll
php_pdo_sqlsrv_82_ts_x64.dll

Edit:

C:\xampp\php\php.ini

Add:

extension=php_sqlsrv_82_ts_x64.dll
extension=php_pdo_sqlsrv_82_ts_x64.dll

Restart Apache

VERIFICATION PROCESS

php -m

EXPECTED OUTPUT

sqlsrv
pdo_sqlsrv

6. Environment Configuration
Edit .env
DB_CONNECTION=sqlsrv
DB_HOST=127.0.0.1
DB_PORT=1433
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

Clear Caches 
php artisan config:clear
php artisan cache:clear

7. Run Database Migration
php artisan migrate


8. Frontend Setup (Bootstrap 5)

Install frontend dependencies:
npm install

Run Vite: In one terminal
npm run dev

9 Run the Server

Run Vite: In one terminal
npm run dev

Run server: In another terminal don't exit the vite terminal
php artisan serve