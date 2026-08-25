# Deployment Report — COMSIT Bursary Automation System
### University of Ilorin (UNILORIN)

---

| | |
|---|---|
| **Prepared by** | Arthurite Integrated |
| **Client** | University of Ilorin (UNILORIN) |
| **AWS Account ID** | 238086621606 |
| **Report Date** | 31 July 2026 |
| **System** | COMSIT — Integrated Financial & HR Management System |
| **Production URL** | https://uilbursary.unilorin.edu.ng |
| **Production IP** | 16.60.39.136 |

---

## 1. Executive Summary

This report documents the full deployment of the **COMSIT Integrated Financial and Human Resources Management System** for the University of Ilorin (UNILORIN) onto Amazon Web Services (AWS). The system is now live in a production environment hosted on UNILORIN's dedicated AWS account.

The engagement covered end-to-end delivery:

- Provisioning and configuration of cloud infrastructure on AWS Lightsail
- Manual installation and tuning of a LAMP stack on Debian 12
- Full database migration (756 MB, 202 tables)
- Source code repository setup and documentation on GitHub
- Staff photo and signature asset transfer
- Staging environment delivery for user acceptance testing
- Production deployment and cutover
- DNS coordination with UNILORIN's IT team for domain assignment

A staging environment was delivered on **27 July 2026** for client-side testing. Following sign-off, the production environment was deployed on **29 July 2026**. The domain `uilbursary.unilorin.edu.ng` was propagated by Dr. Hamzat (UNILORIN IT) and an SSL certificate was provisioned via Let's Encrypt on **31 July 2026**. The system is fully live over HTTPS.

---

## 2. Project Timeline

| Date | Milestone | Details |
|---|---|---|
| 20 Jul 2026 | Initial Engagement Call | First team call involving the Director of COMSIT, Mr. Abubakar Musa (Principal Programmer/Web Developer, Portal Unit), and the Arthurite Team. Scope of migration discussed. Technical Assessment Checklist (TAC) prepared and sent to the COMSIT team. |
| 21 Jul 2026 | TAC Returned | Technical Assessment Checklist completed and submitted by Mr. Abubakar. Responses covered application architecture, database size, file storage, server environment, user projections, and access requirements. |
| 22 Jul 2026 | TAC Acknowledged & Review Begun | Arthurite Team confirmed receipt of the completed TAC. Internal review of responses begun to prepare migration plan and AWS hosting cost estimate. |
| 23 Jul 2026 | Source Code & Clarifications Requested | Arthurite requested a zip of the Bursary Application source files (excluding `upload_files/`) and a database schema dump. Clarifying questions raised on data volume, access model (campus-only vs internet), upload bandwidth, and DNS contact for subdomain setup. |
| 24 Jul 2026 | Files Shared & DNS Contact Provided | Mr. Abubakar shared a Google Drive link to application files and provided Dr. Hamza's contact (+234 813 803 7413) as the university's DNS manager. Initial shared folder contained uploaded memo documents rather than source code; Arthurite clarified the requirement and provided specific export commands. Correct parent folder subsequently shared. |
| 25 Jul 2026 | AWS Account Provisioned & Sandbox Deployed | AWS account (ID: `238086621606`) created under COMSIT/UNILORIN ownership. Account setup and billing configured. Sandbox environment deployed at `54.172.111.28` and shared with Mr. Musa for early functional validation against the on-premises version. |
| 26 Jul 2026 | Infrastructure Setup & Issues Resolved | LAMP stack installed on Debian 12. Several environment-specific issues encountered and resolved: Bitnami LAMP blueprint discontinued on Lightsail (adapted to Debian); MariaDB socket authentication; dual hardcoded credential files; SSH firewall configuration. See Section 7 for full details. |
| 27 Jul 2026 | Staging Environment Delivered | Full staging environment live at `54.172.111.28`. Complete database imported (202 tables, ~756 MB), staff photos and signatures uploaded, application login verified. Formally shared with UNILORIN for user acceptance testing. |
| 27 – 28 Jul 2026 | User Acceptance Testing | Staging instance made available for the UNILORIN team to test all modules against the on-premises version. |
| 29 Jul 2026 | Production Deployment | Production instance deployed on UNILORIN's AWS account (`238086621606`). Full database migration completed, application configured and verified live at `16.60.39.136`. |
| 30 Jul 2026 | DNS Coordination | Dr. Hamzat (UNILORIN IT/DNS) engaged to propagate `uilbursary.unilorin.edu.ng` → `16.60.39.136`. |
| 31 Jul 2026 | SSL Certificate Provisioned & Engagement Complete | Let's Encrypt certificate issued via Certbot for `uilbursary.unilorin.edu.ng`. HTTPS port 443 opened in Lightsail firewall. Certificate auto-renews every 90 days. System fully live at `https://uilbursary.unilorin.edu.ng`. |

---

## 3. Infrastructure Overview

### 3.1 Cloud Provider

The system is hosted on **AWS Lightsail** — Amazon's managed virtual private server (VPS) service. Lightsail was selected for its fixed monthly pricing, simplified networking, and suitability for a single-server LAMP application of this scale.

| Property | Value |
|---|---|
| Provider | Amazon Web Services (AWS) |
| Service | AWS Lightsail |
| AWS Account ID | 238086621606 |
| Region | US East (N. Virginia) |
| Plan | $10/month — 2 GB RAM, 1 vCPU, 60 GB SSD |
| Static IP | 16.60.39.136 (Production) |

### 3.2 Software Stack

| Layer | Technology | Version / Notes |
|---|---|---|
| Operating System | Debian GNU/Linux | Version 12 (Bookworm), Kernel 6.1.0, amd64 |
| Web Server | Apache | 2.4.68 |
| Language | PHP | 8.5.7 (with `mysqli`, `gd`, `zip`, `mbstring` extensions) |
| Database | MariaDB | Debian-bundled release |
| Database Name | `uilkashdb_b` | 202 tables, ~756 MB |
| Web Root | `/var/www/html` | |
| PHP INI (Apache) | `/etc/php/8.5/apache2/php.ini` | |

### 3.3 PHP Configuration

The following PHP limits were applied to accommodate PHPExcel-based report generation and bulk CSV imports:

| Setting | Value |
|---|---|
| `memory_limit` | 256M |
| `upload_max_filesize` | 50M |
| `post_max_size` | 55M |
| `max_execution_time` | 300s |

---

## 4. Environments

### 4.1 Production

| Property | Value |
|---|---|
| Status | **Live** |
| IP Address | `16.60.39.136` |
| URL (pending DNS) | `http://uilbursary.unilorin.edu.ng` |
| AWS Account | 238086621606 |
| Deployment Date | 24 August 2026 |

### 4.2 Staging

The staging environment was provisioned on a separate Lightsail instance for user acceptance testing prior to production cutover.

| Property | Value |
|---|---|
| Status | Testing (can be decommissioned after sign-off) |
| IP Address | `54.172.111.28` |
| Ready Since | 17 August 2026 |
| Purpose | User acceptance testing |

---

## 5. Source Code Repository

The application source code has been version-controlled and hosted on GitHub under the **Arthurite Integrated** organisation. The repository is set to **private**, given that the system handles sensitive financial, payroll, and HR data.

| Property | Value |
|---|---|
| Repository URL | https://github.com/Arthurite-Integrated/comsit-busary-system |
| Visibility | Private |
| Default Branch | `main` |

### 5.1 What Is Tracked in the Repository

- All PHP application source files (~250 files)
- Frontend assets (jQuery EasyUI, DataTables, TinyBox, CSS, icons)
- PHPExcel library (`class/PHPExcel/`)
- CSV upload templates (`template/`)
- Credential template (`connect.example.php`)
- Documentation (`README.md`, `DEPLOY.md`, `REPORT.md`)

### 5.2 What Is Excluded (`.gitignore`)

The following are intentionally excluded from the repository and must be transferred separately to any new server:

| Excluded Item | Reason |
|---|---|
| `connect.php` | Contains live database credentials |
| `DB/uilkashdb_backup_1.sql` | 756 MB — sensitive financial data, too large for git |
| `pictures/` | Staff photos and signature images — personal data |

### 5.3 Documentation Included

| File | Purpose |
|---|---|
| `README.md` | Project overview, tech stack, prerequisites, key files, notes |
| `DEPLOY.md` | Full step-by-step deployment guide for AWS Lightsail (Debian), including known challenges and fixes |
| `connect.example.php` | Template showing which credentials to configure on a new server |
| `REPORT.md` | This document |

---

## 6. Deployment Steps Completed

The following tasks were completed as part of this engagement:

- [x] AWS Lightsail instance provisioned on Debian 12
- [x] LAMP stack installed (Apache, MariaDB, PHP 8.5 + extensions)
- [x] Lightsail firewall configured (SSH port 22 opened for external access)
- [x] Static IP attached to production instance
- [x] GitHub repository created, configured, and documented
- [x] Application source code cloned from GitHub into web root
- [x] `connect.php` configured with production database credentials
- [x] File ownership and permissions set (`www-data:www-data`)
- [x] PHP limits raised in Apache `php.ini`
- [x] MariaDB root password configured to match application credentials
- [x] `uilkashdb_b` database created
- [x] 756 MB SQL dump uploaded via `scp` and imported (202 tables verified)
- [x] `pictures/` directory (staff photos and signatures) uploaded via `scp`
- [x] Application login verified on production server
- [x] DNS coordination initiated with Dr. Hamzat (UNILORIN IT) for `uilbursary.unilorin.edu.ng`

---

## 7. Challenges Encountered & Resolutions

The following issues were encountered during deployment and fully resolved.

### 7.1 Bitnami LAMP Blueprint Discontinued

**Issue:** AWS Lightsail no longer offers Bitnami LAMP stack blueprints, which were the originally planned deployment base.

**Resolution:** Deployment was adapted to use an **OS Only → Debian** blueprint. The LAMP stack (Apache, MariaDB, PHP 8.5) was installed manually via `apt-get`. All configuration was performed from scratch, which ultimately gave greater control over the environment.

---

### 7.2 External SSH Blocked by Default

**Issue:** Attempting to SSH from a local machine timed out (`Operation timed out`). The AWS browser terminal worked because it bypasses the Lightsail firewall; external connections did not.

**Resolution:** An inbound SSH rule (TCP port 22, all IPv4) was added in the **Lightsail Networking → IPv4 Firewall** panel.

---

### 7.3 MariaDB Socket Authentication

**Issue:** On Debian, MariaDB's root user defaults to Unix socket authentication rather than password-based login. The application's hardcoded credentials (`root` with a password) resulted in `Access denied` errors when PHP tried to connect.

**Resolution:** The MariaDB root password was set using MariaDB-compatible syntax:

```sql
SET PASSWORD FOR 'root'@'localhost' = PASSWORD('...');
FLUSH PRIVILEGES;
```

Note: The MySQL-style `IDENTIFIED WITH mysql_native_password BY` syntax does not work on MariaDB and returns a syntax error.

---

### 7.4 Dual Hardcoded Credential Files

**Issue:** The application stores database credentials in two separate files — `connect.php` and `class/mysqli_class.php`. Updating only `connect.php` was insufficient; the `mysqli_class.php` file (which handles a secondary database class used across the application) still referenced the original credentials, causing a PHP Fatal Error and 500 response on first load.

**Resolution:** Rather than modifying the application code (which was outside the agreed scope), the MariaDB root password was set to match the credentials already hardcoded in both files. This aligned the database to the app, not the other way around.

**Apache error log entry that identified the issue:**
```
PHP Fatal error: Uncaught mysqli_sql_exception: Access denied for user 'root'@'localhost'
in /var/www/html/class/mysqli_class.php on line 27
```

---

### 7.5 PHP CLI vs Apache INI Confusion

**Issue:** After editing `/etc/php/8.5/apache2/php.ini` to raise PHP limits, running `php -i | grep memory_limit` still showed the old values. This caused uncertainty about whether the changes had taken effect.

**Resolution:** `php -i` reads the **CLI** ini (`/etc/php/8.5/cli/php.ini`), which is a completely separate file from the **Apache** ini. The Apache ini was verified directly:

```bash
grep -E "^memory_limit|^upload_max_filesize|^post_max_size|^max_execution_time" \
  /etc/php/8.5/apache2/php.ini
```

All four values were confirmed correct.

---

### 7.6 `pictures/` Directory Did Not Exist on Server

**Issue:** Uploading the `pictures/` directory via `scp` failed with `path canonicalization failed` because the destination directory did not yet exist on the server.

**Resolution:** The directory was created manually on the server before the `scp` transfer:

```bash
sudo mkdir -p /var/www/html/pictures
sudo chown admin:admin /var/www/html/pictures
```

---

## 8. DNS Configuration

UNILORIN's DNS is managed internally. The following record needs to be created/updated by Dr. Hamzat (UNILORIN IT/DNS):

| Record Type | Name | Value | TTL |
|---|---|---|---|
| A | `uilbursary.unilorin.edu.ng` | `16.60.39.136` | 3600 (or as per university policy) |

Once DNS has propagated (typically 24–48 hours), the system will be accessible at:

```
http://uilbursary.unilorin.edu.ng
```

After DNS is confirmed working, an SSL certificate should be provisioned (see Section 9.2).

---

## 9. Recommendations

The following items are recommended for action after go-live.

### 9.1 ~~Provision SSL/HTTPS~~ — Completed

SSL is live. A Let's Encrypt certificate was provisioned on 25 August 2026 for `uilbursary.unilorin.edu.ng`. Certbot has configured Apache to redirect HTTP to HTTPS and set up automatic renewal. Certificate expires 23 November 2026.

### 9.2 Enable Automated Database Backups

The database contains irreplaceable financial records, payroll data, and HR history. A backup strategy should be implemented immediately:

- **Option A:** Enable Lightsail automated snapshots (daily, 7-day retention) — configurable in the Lightsail console at no extra cost.
- **Option B:** Schedule a daily `mysqldump` cron job that uploads to an S3 bucket.

### 9.3 Restrict SSH Access

Port 22 is currently open to all IPv4 (`0.0.0.0/0`). For a production financial system, SSH should be restricted to the specific IP addresses of authorised administrators via the Lightsail firewall.

### 9.4 Upgrade Password Storage

Staff passwords in the `stafftb` table are stored as `base64_encode()` of the plaintext password — this is effectively plain text storage, not encryption or hashing. If the database were ever compromised, all staff passwords would be immediately exposed.

Passwords should be migrated to use PHP's `password_hash()` with bcrypt. This requires a one-time migration script and a corresponding update to the login verification logic in `script/scriptLogin.php`.

### 9.5 Upgrade PHPExcel to PhpSpreadsheet

The application uses PHPExcel, a library that has been unmaintained since 2015 and has known compatibility issues with PHP 8.x. Excel export features (payroll bank lists, salary schedules, financial reports) may produce errors under the current PHP 8.5 environment.

The recommended upgrade path is [PhpSpreadsheet](https://github.com/PHPOffice/PhpSpreadsheet), the official successor library, which is actively maintained and fully compatible with PHP 8.

### 9.6 Monitor Disk Usage

The server plan provides 60 GB SSD. The database alone is ~756 MB, and the application generates report files and uploads over time. Disk usage should be monitored, and the instance plan upgraded if usage approaches 80% capacity.

---

## 10. Access & Handover

| Item | Detail |
|---|---|
| Production URL | https://uilbursary.unilorin.edu.ng |
| Production IP | 16.60.39.136 |
| AWS Account | 238086621606 |
| Application login | Use `fileno` and password from `stafftb`. Passwords are base64-encoded. |
| SSH access | Requires the Lightsail key pair and `admin` user |
| Source code | https://github.com/Arthurite-Integrated/comsit-busary-system |
| Deployment guide | `DEPLOY.md` in the repository |
| Database backup | `uilkashdb_backup_1.sql` — held by Arthurite Integrated, transfer separately |
| Staff photos | `pictures/` directory — held by Arthurite Integrated, transfer separately |

---

*Report prepared by Arthurite Integrated · August 2026*
*University of Ilorin (UNILORIN) — COMSIT Bursary Automation System*
