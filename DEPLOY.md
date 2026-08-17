# Deploying to AWS Lightsail

Step-by-step guide — from pushing to GitHub to a live LAMP instance.

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

Platform: **Linux/Unix**. Blueprint: **Apps + OS → LAMP (PHP 8)**.

**3. Choose a plan**

The **$10/month** plan (2 GB RAM, 1 vCPU, 60 GB SSD) is a safe minimum. Consider $20/month for heavier usage.

**4. Name and launch**

Name it (e.g. `comsit-prod`), click **Create instance**. Wait ~60 seconds for status to show *Running*.

**5. Attach a static IP**

Go to **Networking → Create static IP** → attach it to the instance. Note the IP — you'll use it in every command below.

**6. Download the SSH key**

**Account → SSH keys** → download the default key for your region. Then:
```bash
mv ~/Downloads/LightsailDefaultKey-us-east-1.pem ~/.ssh/lightsail.pem
chmod 400 ~/.ssh/lightsail.pem
```

---

## Phase 3 — Configure the Server

**1. SSH into the server**
```bash
ssh -i ~/.ssh/lightsail.pem bitnami@YOUR_STATIC_IP
```

The default user on Bitnami LAMP is `bitnami`.

**2. Get the MySQL root password**
```bash
cat /home/bitnami/bitnami_application_password
```

Copy this — you'll use it multiple times.

**3. Install git**
```bash
sudo apt-get update -y && sudo apt-get install git -y
```

**4. Clear the default Bitnami web root**
```bash
sudo rm -rf /opt/bitnami/apache2/htdocs/*
```

**5. Clone the repository into the web root**
```bash
sudo git clone https://github.com/YOUR_USERNAME/comsit.git /opt/bitnami/apache2/htdocs
```

If the repo is private, use a GitHub **Personal Access Token** as the password (GitHub → Settings → Developer settings → Personal access tokens → classic, with `repo` scope).

**6. Create connect.php from the template**
```bash
sudo cp /opt/bitnami/apache2/htdocs/connect.example.php \
        /opt/bitnami/apache2/htdocs/connect.php

sudo nano /opt/bitnami/apache2/htdocs/connect.php
```

Replace `YOUR_MYSQL_PASSWORD` with the Bitnami password from step 2. Save: **Ctrl+O → Enter → Ctrl+X**.

**7. Set file ownership and permissions**
```bash
sudo chown -R daemon:daemon /opt/bitnami/apache2/htdocs
sudo find /opt/bitnami/apache2/htdocs -type d -exec chmod 755 {} \;
sudo find /opt/bitnami/apache2/htdocs -type f -exec chmod 644 {} \;
```

**8. Raise PHP limits** (required for PHPExcel and CSV imports)
```bash
sudo nano /opt/bitnami/php/etc/php.ini
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
sudo /opt/bitnami/ctlscript.sh restart apache
```

---

## Phase 4 — Database Import & Go Live

**1. Upload the SQL dump** *(open a new Terminal tab on your Mac)*
```bash
scp -i ~/.ssh/lightsail.pem \
  "/Users/pro/Documents/projects/arthurite/comsit/DB/uilkashdb_backup_1.sql" \
  bitnami@YOUR_STATIC_IP:~/uilkashdb_backup_1.sql
```

> **756 MB — allow 5–15 minutes depending on your upload speed.**

**2. Upload the pictures directory** *(staff photos and signatures)*
```bash
scp -r -i ~/.ssh/lightsail.pem \
  "/Users/pro/Documents/projects/arthurite/comsit/pictures/" \
  bitnami@YOUR_STATIC_IP:/opt/bitnami/apache2/htdocs/pictures/
```

**3. Create the database** *(back in the SSH session)*
```bash
mysql -uroot -pBITNAMI_PASSWORD -e \
  "CREATE DATABASE IF NOT EXISTS uilkashdb_b CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

> Paste the password immediately after `-p` with no space.

**4. Import the dump**
```bash
mysql -uroot -pBITNAMI_PASSWORD uilkashdb_b < ~/uilkashdb_backup_1.sql
```

No output means it's working. Wait for the prompt to return (~5 minutes).

**5. Verify the import**
```bash
mysql -uroot -pBITNAMI_PASSWORD -e "USE uilkashdb_b; SHOW TABLES;" | wc -l
```

Expect **175+**. If it's near 0, the import didn't complete — retry step 4.

**6. Open the app**

Navigate to `http://YOUR_STATIC_IP/` in your browser. You should see the COMSIT login page.

**7. Find login credentials**
```bash
mysql -uroot -pBITNAMI_PASSWORD uilkashdb_b \
  -e "SELECT fileno, surname, firstname, password FROM stafftb LIMIT 10;"
```

Passwords are base64. Decode in the browser console:
```js
atob("PASTE_BASE64_VALUE_HERE")
```

**8. Clean up the dump from the server**
```bash
rm ~/uilkashdb_backup_1.sql
```

---

## After Go-Live

**Custom domain:** Create a DNS A record pointing to the static IP. Lightsail also has a built-in DNS zone manager.

**HTTPS / SSL:** Run the Bitnami Let's Encrypt helper — it provisions a certificate and reconfigures Apache automatically:
```bash
sudo /opt/bitnami/bncert-tool
```

**Future code updates:** On the server, pull the latest changes:
```bash
cd /opt/bitnami/apache2/htdocs && sudo git pull
```
