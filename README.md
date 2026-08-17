# COMSIT — Bursary Automation System

Integrated Financial and Human Resources Management System for the University of Ilorin (UNILORIN). Covers payroll, payment vouchers, budget management, HR records, fixed assets, revenue tracking, and internal correspondence.

---

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | PHP (procedural, no framework) |
| Database | MySQL (`uilkashdb_b`) |
| Frontend | jQuery 1.12.3, jQuery EasyUI, DataTables |
| Excel Export | PHPExcel (bundled in `class/PHPExcel/`) |
| SMS Alerts | SmartSMS Solutions API |

---

## Deployment

See **[DEPLOY.md](DEPLOY.md)** for the full step-by-step guide to deploying on AWS Lightsail — from GitHub push to live database import.

---

## Prerequisites

- PHP 7.4+ with `mysqli` extension enabled
- MySQL 5.7+
- Apache or Nginx web server

---

## Setup

### 1. Clone the repository

```bash
git clone https://github.com/YOUR_ORG/comsit.git
cd comsit
```

### 2. Configure the database connection

```bash
cp connect.example.php connect.php
```

Edit `connect.php` and fill in your MySQL credentials.

### 3. Import the database

The SQL dump is **not included in the repository** (756 MB, sensitive data). Obtain it separately and import it:

```bash
mysql -u root -p -e "CREATE DATABASE uilkashdb_b;"
mysql -u root -p uilkashdb_b < /path/to/uilkashdb_backup_1.sql
```

### 4. Upload staff photos

The `pictures/` directory (staff photos and signatures) is excluded from the repository. Transfer it separately:

```bash
scp -r pictures/ user@your-server:/path/to/comsit/pictures/
```

### 5. Point your web server to the project root

The project root is the document root — `index.php` is the entry point.

---

## Key Files

| File | Purpose |
|---|---|
| `index.php` | Login page (entry point) |
| `main.php` | Dashboard (post-login) |
| `connect.php` | Database credentials (not in git) |
| `connect.example.php` | Credentials template |
| `function.php` | Global utility functions |
| `scriptfile_a.php` | Central AJAX dispatcher |
| `sidebar_main.php` | Role-based navigation |
| `class/myclass_m.php` | Core application class (payroll, salary) |

---

## Roles

The system supports approximately 20 user roles including Super Admin, Bursar, Auditor, Budget Officer, Final Account, HR Officer, and Registry Admin. Role-based menus are generated at runtime from the `users_roletb` table.

---

## PHP Configuration Recommendations

Add these to your `php.ini` or server config — PHPExcel and bulk CSV imports require higher limits:

```ini
memory_limit = 256M
upload_max_filesize = 50M
post_max_size = 55M
max_execution_time = 300
```

---

## Notes

- Passwords are stored as `base64_encode()` in `stafftb` — not cryptographically hashed. Plan to upgrade this before any public-facing deployment.
- Most SQL queries use raw request input — review before exposing to untrusted networks.
- PHPExcel is unmaintained (2015). Consider migrating to [PhpSpreadsheet](https://github.com/PHPOffice/PhpSpreadsheet) for PHP 8 compatibility.
