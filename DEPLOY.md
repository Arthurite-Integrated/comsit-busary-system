# Deploying to AWS Lightsail

Step-by-step guide — from pushing to GitHub to a live LAMP instance on Debian.

**Before you start:** Git installed, GitHub account, AWS account with billing, the 756 MB SQL dump on hand, Terminal open.

---

## Phase 1 — Push the Code to GitHub

**1. Navigate to the project**
```bash
cd /Users/pro/Documents/projects/arthurite/comsit
```

**2. Initialize git**
```bash
git init
git branch -M main
```

**3. Stage all files**
```bash
git add .
git status   # verify connect.php, DB/, and pictures/ do NOT appear
```

**4. Commit**
```bash
git commit -m "Initial commit — COMSIT Bursary System"
```

**5. Create a new repository on GitHub**

Go to **github.com → New repository**. Name it `comsit`. Set it to **Private** — this system contains financial data. Do NOT initialize with a README.

**6. Connect and push**
```bash
git remote add origin https://github.com/YOUR_USERNAME/comsit.git
git push -u origin main
```

---

## Phase 2 — Create the Lightsail Instance

**1. Open Lightsail**

Log in to `console.aws.amazon.com` → search **Lightsail** → click **Create instance**.

**2. Select platform and blueprint**

Platform: **Linux/Unix**. Blueprint: **OS Only → Debian**.

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

The browser terminal bypasses the firewall; external SSH does not. Fix it before trying to connect from your Mac:

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
sudo apt-get install -y apache2 mysql-server php php-mysqli php-gd php-zip php-mbstring unzip git
sudo systemctl enable apache2 mysql
sudo systemctl start apache2 mysql
```

**3. Secure MySQL and set a root password**
```bash
sudo mysql
```

Inside the MySQL prompt:
```sql
ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'YOUR_CHOSEN_PASSWORD';
FLUSH PRIVILEGES;
EXIT;
```

> Pick a strong password and save it — you'll use it for every `mysql` command from here on.

**4. Clear the default Apache web root**
```bash
sudo rm -rf /var/www/html/*
```

**5. Clone the repository into the web root**
```bash
sudo git clone https://github.com/YOUR_USERNAME/comsit.git /var/www/html
```

If the repo is private, use a GitHub **Personal Access Token** as the password (GitHub → Settings → Developer settings → Personal access tokens → classic, with `repo` scope).

**6. Create connect.php from the template**
```bash
sudo cp /var/www/html/connect.example.php /var/www/html/connect.php
sudo nano /var/www/html/connect.php
```

Replace `YOUR_MYSQL_PASSWORD` with the password you set in step 3. Save: **Ctrl+O → Enter → Ctrl+X**.

**7. Set file ownership and permissions**
```bash
sudo chown -R www-data:www-data /var/www/html
sudo find /var/www/html -type d -exec chmod 755 {} \;
sudo find /var/www/html -type f -exec chmod 644 {} \;
```

**8. Raise PHP limits** (required for PHPExcel and CSV imports)
```bash
# Find your PHP version first
php -v

# Edit php.ini — replace 8.2 with your actual version if different
sudo nano /etc/php/8.2/apache2/php.ini
```

Find and update these four values (Ctrl+W to search in nano):
```ini
memory_limit = 256M
upload_max_filesize = 50M
post_max_size = 55M
max_execution_time = 300
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
  "/Users/pro/Documents/projects/arthurite/comsit/DB/uilkashdb_backup_1.sql" \
  admin@YOUR_STATIC_IP:~/uilkashdb_backup_1.sql
```

> **756 MB — allow 5–15 minutes depending on your upload speed.**

**2. Upload the pictures directory** *(staff photos and signatures)*
```bash
scp -r -i ~/.ssh/comsit-key.pem \
  "/Users/pro/Documents/projects/arthurite/comsit/pictures/" \
  admin@YOUR_STATIC_IP:/var/www/html/pictures/
```

**3. Create the database** *(back in the SSH session)*
```bash
mysql -uroot -pYOUR_CHOSEN_PASSWORD -e \
  "CREATE DATABASE IF NOT EXISTS uilkashdb_b CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

> Paste the password immediately after `-p` with no space.

**4. Import the dump**
```bash
mysql -uroot -pYOUR_CHOSEN_PASSWORD uilkashdb_b < ~/uilkashdb_backup_1.sql
```

No output means it's working. Wait for the prompt to return (~5 minutes).

**5. Verify the import**
```bash
mysql -uroot -pYOUR_CHOSEN_PASSWORD -e "USE uilkashdb_b; SHOW TABLES;" | wc -l
```

Expect **175+**. If it's near 0, the import didn't complete — retry step 4.

**6. Open the app**

Navigate to `http://YOUR_STATIC_IP/` in your browser. You should see the COMSIT login page.

**7. Find login credentials**
```bash
mysql -uroot -pYOUR_CHOSEN_PASSWORD uilkashdb_b \
  -e "SELECT fileno, surname, firstname, password FROM stafftb LIMIT 10;"
```

Passwords are base64. Decode in the browser console:
```js
atob("PASTE_BASE64_VALUE_HERE")
```

**8. Fix pictures directory ownership** (if staff photos aren't loading)
```bash
sudo chown -R www-data:www-data /var/www/html/pictures
```

**9. Clean up the dump from the server**
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
