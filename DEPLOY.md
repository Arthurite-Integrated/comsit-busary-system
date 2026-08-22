# Deploying to AWS Lightsail

Step-by-step guide for deploying the COMSIT Bursary System on a fresh AWS Lightsail instance.

**Tested environment:** AWS Lightsail · Debian 12 · MariaDB · PHP 8.5 · Apache 2.4

**Before you start:** AWS account with billing set up, the 756 MB SQL dump file on hand, Terminal open on your Mac.

---

## Phase 1 — Clone the Repository

**1. Clone onto your Mac** *(optional — only needed if you want a local copy)*
```bash
git clone https://github.com/Arthurite-Integrated/comsit-busary-system.git
cd comsit-busary-system
```

The server will clone directly from GitHub in Phase 3. You only need a local copy if you intend to make code changes and push them.

---

## Phase 2 — Create the Lightsail Instance

**1. Open Lightsail**

Log in to `console.aws.amazon.com` → search **Lightsail** → click **Create instance**.

**2. Select platform and blueprint**

Platform: **Linux/Unix**. Blueprint: **OS Only → Debian**.

> Bitnami LAMP blueprints are no longer supported on AWS Lightsail. Use Debian and install the LAMP stack manually (Phase 3).

**3. Choose a plan**

The **$10/month** plan (2 GB RAM, 1 vCPU, 60 GB SSD) is a safe minimum. Consider $20/month for heavier usage.

**4. Name and launch**

Name it (e.g. `comsit-prod`), click **Create instance**. Wait ~60 seconds for status to show *Running*.

**5. Attach a static IP**

Go to **Networking → Create static IP** → attach it to the instance. Note the IP — you'll use it in every command below.

**6. Download the SSH key and fix permissions**
```bash
mv ~/Downloads/comsit-key.pem ~/.ssh/comsit-key.pem
chmod 400 ~/.ssh/comsit-key.pem
```

**7. Open port 22 in the Lightsail firewall**

External SSH is blocked by default. The AWS browser terminal bypasses this, but your Mac does not.

> Instance → **Networking** tab → **IPv4 Firewall** → **Add rule**
> Application: `SSH` · Port: `22` · Source: `All IPv4 (0.0.0.0/0)` → **Create**

---

## Phase 3 — Configure the Server

**1. SSH into the server from your Mac**
```bash
ssh -i ~/.ssh/comsit-key.pem admin@YOUR_STATIC_IP
```

The default user on Debian Lightsail is `admin`.

**2. Install the LAMP stack**
```bash
sudo apt-get update -y
sudo apt-get install -y apache2 mariadb-server php php-mysqli php-gd php-zip php-mbstring unzip git
sudo systemctl enable apache2 mariadb
sudo systemctl start apache2 mariadb
```

**3. Set the MariaDB root password to match the app**

The app has `root` and a password hardcoded in two files (`connect.php` and `class/mysqli_class.php`). Rather than modifying the app, configure the database to match.

On Debian, MariaDB root uses socket authentication by default — connect with `sudo mysql`, then set the password:

```bash
sudo mysql -e "SET PASSWORD FOR 'root'@'localhost' = PASSWORD('YOUR_DB_PASSWORD'); FLUSH PRIVILEGES;"
```

Verify it works:
```bash
mysql -uroot -pYOUR_DB_PASSWORD -e "SELECT 1;"
```

Should return `1` with no error.

**4. Clear the default Apache web root**
```bash
sudo rm -rf /var/www/html/*
```

**5. Clone the repository into the web root**
```bash
sudo git clone https://github.com/Arthurite-Integrated/comsit-busary-system.git /var/www/html
```

If prompted for credentials, use your GitHub username and a **Personal Access Token** as the password (GitHub → Settings → Developer settings → Personal access tokens → classic, with `repo` scope).

**6. Create connect.php from the template**
```bash
sudo cp /var/www/html/connect.example.php /var/www/html/connect.php
sudo nano /var/www/html/connect.php
```

Set the credentials to match what you used in step 3:
```php
$user = "root";
$password = "YOUR_DB_PASSWORD";
```

Save: **Ctrl+O → Enter → Ctrl+X**.

**7. Set file ownership and permissions**
```bash
sudo chown -R www-data:www-data /var/www/html
sudo find /var/www/html -type d -exec chmod 755 {} \;
sudo find /var/www/html -type f -exec chmod 644 {} \;
```

**8. Raise PHP limits** (required for PHPExcel and CSV imports)

First check your PHP version:
```bash
php -v
```

Then apply the limits — replace `8.5` with your actual version if different:
```bash
sudo sed -i 's/^memory_limit = .*/memory_limit = 256M/' /etc/php/8.5/apache2/php.ini
sudo sed -i 's/^upload_max_filesize = .*/upload_max_filesize = 50M/' /etc/php/8.5/apache2/php.ini
sudo sed -i 's/^post_max_size = .*/post_max_size = 55M/' /etc/php/8.5/apache2/php.ini
sudo sed -i 's/^max_execution_time = .*/max_execution_time = 300/' /etc/php/8.5/apache2/php.ini
```

Verify against the Apache ini (not the CLI ini — they are separate files):
```bash
grep -E "^memory_limit|^upload_max_filesize|^post_max_size|^max_execution_time" /etc/php/8.5/apache2/php.ini
```

Restart Apache:
```bash
sudo systemctl restart apache2
```

---

## Phase 4 — Database Import & Go Live

**1. Upload the SQL dump** *(open a new Terminal tab on your Mac)*
```bash
scp -i ~/.ssh/comsit-key.pem \
  "/path/to/uilkashdb_backup_1.sql" \
  admin@YOUR_STATIC_IP:~/uilkashdb_backup_1.sql
```

> **756 MB — allow 5–15 minutes depending on your upload speed.**

**2. Create the pictures directory on the server** *(in the SSH session)*
```bash
sudo mkdir -p /var/www/html/pictures
sudo chown admin:admin /var/www/html/pictures
```

**3. Upload the pictures directory** *(from your Mac)*
```bash
scp -r -i ~/.ssh/comsit-key.pem \
  "/path/to/pictures/" \
  admin@YOUR_STATIC_IP:/var/www/html/pictures/
```

**4. Create the database** *(in the SSH session)*
```bash
sudo mysql -e "CREATE DATABASE IF NOT EXISTS uilkashdb_b CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

**5. Import the dump**
```bash
sudo mysql uilkashdb_b < ~/uilkashdb_backup_1.sql
```

No output means it's working. Wait for the prompt to return (~5 minutes).

**6. Verify the import**
```bash
sudo mysql -e "USE uilkashdb_b; SHOW TABLES;" | wc -l
```

Expect **175+**.

**7. Fix pictures directory ownership**
```bash
sudo chown -R www-data:www-data /var/www/html/pictures
```

**8. Open the app**

Navigate to `http://YOUR_STATIC_IP/` in your browser. You should see the COMSIT login page.

**9. Find login credentials**
```bash
mysql -uroot -pYOUR_DB_PASSWORD uilkashdb_b \
  -e "SELECT fileno, surname, first_name, password FROM stafftb LIMIT 10;"
```

Passwords are base64-encoded. Decode in the browser console:
```js
atob("PASTE_BASE64_VALUE_HERE")
```

**10. Clean up the dump from the server**
```bash
rm ~/uilkashdb_backup_1.sql
```

---

## After Go-Live

**Custom domain:** Create a DNS A record pointing to the static IP. Lightsail also has a built-in DNS zone manager.

**HTTPS / SSL:**
```bash
sudo apt-get install -y certbot python3-certbot-apache
sudo certbot --apache -d yourdomain.com
```

**Future code updates:**
```bash
cd /var/www/html && sudo git pull
sudo chown -R www-data:www-data /var/www/html
```

---

## Known Challenges & Fixes

### SSH times out from your Mac
**Symptom:** `ssh: connect to host ... port 22: Operation timed out`

**Cause:** Lightsail's firewall blocks port 22 by default. The AWS browser terminal bypasses this, external SSH does not.

**Fix:** Instance → **Networking** → **IPv4 Firewall** → Add rule: SSH / port 22 / All IPv4.

---

### `scp` fails with "not a regular file"
**Symptom:** `scp: local "...pictures/" is not a regular file`

**Cause:** Missing the `-r` flag for directories.

**Fix:** Always use `scp -r` when uploading a directory.

---

### `scp` fails with "path canonicalization failed"
**Symptom:** `scp: realpath /var/www/html/pictures/: No such file`

**Cause:** The destination directory doesn't exist on the server yet.

**Fix:**
```bash
sudo mkdir -p /var/www/html/pictures
sudo chown admin:admin /var/www/html/pictures
```

---

### 500 Internal Server Error on first load
**Symptom:** Browser shows 500, Apache log shows `Access denied for user 'root'@'localhost' in mysqli_class.php`

**Cause:** The app has credentials hardcoded in two separate files — `connect.php` and `class/mysqli_class.php`. Both expect the same `root` password. On Debian, MariaDB root uses socket auth by default so password login fails until the password is explicitly set.

**Fix:** Set the MariaDB root password to match what the app expects (Phase 3, step 3), then make sure `connect.php` uses the same password.

Check the Apache error log to confirm:
```bash
sudo tail -20 /var/log/apache2/error.log
```

---

### MariaDB syntax error on ALTER USER
**Symptom:** `ERROR 1064: You have an error in your SQL syntax ... near 'BY ...'`

**Cause:** The `IDENTIFIED WITH mysql_native_password BY` syntax is MySQL-specific. This server runs **MariaDB**.

**Fix:** Use MariaDB's syntax instead:
```bash
sudo mysql -e "SET PASSWORD FOR 'root'@'localhost' = PASSWORD('YOUR_DB_PASSWORD'); FLUSH PRIVILEGES;"
```

---

### PHP ini changes don't appear in `php -i`
**Symptom:** `php -i | grep memory_limit` still shows the old value after editing php.ini.

**Cause:** `php -i` reads the **CLI** ini (`/etc/php/8.5/cli/php.ini`), not the Apache one (`/etc/php/8.5/apache2/php.ini`). They are separate files.

**Fix:** Verify the Apache ini directly:
```bash
grep -E "^memory_limit|^upload_max_filesize|^post_max_size|^max_execution_time" /etc/php/8.5/apache2/php.ini
```

---

### PHPExcel errors on Excel export
**Symptom:** Errors when generating payroll reports or bank lists as Excel files.

**Cause:** PHPExcel was abandoned in 2015 and has known compatibility issues with PHP 8.x.

**Fix:** The core app functions (vouchers, payroll, HR, journal entries) work fine. Excel export features may need the library upgraded to [PhpSpreadsheet](https://github.com/PHPOffice/PhpSpreadsheet) at a later stage.
