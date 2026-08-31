# Operations Guide — COMSIT Bursary System

Day-to-day reference for server access, database management, file uploads, and deployments.

**Production URL:** https://uilbursary.unilorin.edu.ng  
**Production IP:** 16.60.39.136  
**AWS Account:** 238086621606  

---

## 1. Server Access

The recommended way to access the server is via the **AWS Lightsail browser terminal** — no SSH key required.

> AWS Console → Lightsail → click the production instance → click the orange **Connect using SSH** button

This opens a full terminal session in the browser.

---

## 2. Database Access

From the server terminal:

```bash
# Open the database
sudo mysql uilkashdb_b

# Useful queries inside MySQL
SHOW TABLES;
SELECT fileno, surname, first_name FROM stafftb LIMIT 20;
EXIT;
```

**Take a manual backup:**
```bash
sudo mysqldump uilkashdb_b > ~/backup_$(date +%F).sql
```

**Restore from a backup:**
```bash
sudo mysql uilkashdb_b < ~/backup_YYYY-MM-DD.sql
```

---

## 3. File Uploads

All files uploaded through the application are stored at:

| Path | Contents |
|---|---|
| `/var/www/html/upload_files/` | General uploads (memos, vouchers, attachments) |
| `/var/www/html/upload_files/recon/` | Reconciliation files |
| `/var/www/html/upload_files/final/` | Final account journal uploads |

### First-time setup (if directory is missing)

```bash
sudo mkdir -p /var/www/html/upload_files/recon
sudo mkdir -p /var/www/html/upload_files/final
sudo chown -R www-data:www-data /var/www/html/upload_files
sudo chmod -R 755 /var/www/html/upload_files
```

### Check what's uploaded

```bash
ls -lh /var/www/html/upload_files/
du -sh /var/www/html/upload_files/
```

### If file uploads are failing silently

The most common cause is a permissions issue. Fix it with:

```bash
sudo chown -R www-data:www-data /var/www/html/upload_files
```

### Migrating historical files from the old server

On the **old on-premises server**, zip the upload folder:
```bash
zip -r upload_files.zip /var/www/html/upload_files/
```

Upload the zip to the production server, then extract:
```bash
cd /var/www/html
sudo unzip ~/upload_files.zip
sudo chown -R www-data:www-data upload_files/
```

---

## 4. CI/CD Pipeline

Every push to the `main` branch automatically deploys to the production server via GitHub Actions (see `.github/workflows/deploy.yml`).

**How it works:**
1. A change is made to a PHP file (or any file), committed, and pushed to `main`
2. GitHub Actions SSHes into the server
3. The server runs `git pull` to grab the latest changes
4. Apache reloads to serve the updated files
5. Live within seconds — no manual steps needed

### One-time setup

Two separate SSH keys are needed — one so GitHub Actions can SSH into the server, and one so the server can pull from the private GitHub repo.

---

**Key 1 — Let GitHub Actions SSH into the server**

From the Lightsail browser terminal:

```bash
# Generate a key pair
ssh-keygen -t ed25519 -f ~/.ssh/deploy_key -N ""

# Allow this key to log in
cat ~/.ssh/deploy_key.pub >> ~/.ssh/authorized_keys

# Print the private key — copy all of it
cat ~/.ssh/deploy_key
```

Add it to GitHub:
> Repository → **Settings** → **Secrets and variables** → **Actions** → **New repository secret**
> - Name: `SSH_PRIVATE_KEY`
> - Value: paste the full private key (including the `-----BEGIN` and `-----END` lines)

---

**Key 2 — Let the server pull from the private GitHub repo**

From the Lightsail browser terminal:

```bash
# Generate a separate deploy key for GitHub
ssh-keygen -t ed25519 -f ~/.ssh/github_deploy -N ""

# Print the public key — copy it
cat ~/.ssh/github_deploy.pub

# Tell git to use this key when connecting to GitHub
echo -e "Host github.com\n  IdentityFile ~/.ssh/github_deploy\n  StrictHostKeyChecking no" >> ~/.ssh/config
```

Add the public key to GitHub:
> Repository → **Settings** → **Deploy keys** → **Add deploy key**
> - Title: `production-server`
> - Key: paste the public key
> - Allow write access: **No** (read-only is enough)

Then switch the git remote on the server from HTTPS to SSH:
```bash
cd /var/www/html
sudo git remote set-url origin git@github.com:Arthurite-Integrated/comsit-busary-system.git
```

---

**Add Mr. Abubakar as a collaborator** (so he can push changes):
> Repository → **Settings** → **Collaborators** → **Add people**
> Enter his GitHub username

---

### Monitor deployments

> GitHub → repository → **Actions** tab

Each run shows a full log. Green tick = deployed successfully. Red cross = something failed — click the run to see the error.

### Manual deploy (fallback)

If the pipeline ever fails and you need to deploy immediately, from the server terminal:

```bash
cd /var/www/html
sudo git pull
sudo systemctl reload apache2
```

---

## 5. SSL Certificate

The SSL certificate was provisioned via Let's Encrypt and renews automatically every 90 days.

**Check certificate status:**
```bash
sudo certbot certificates
```

**Test HTTPS is working:**
```bash
curl -I https://uilbursary.unilorin.edu.ng
```

**Force a manual renewal (if needed):**
```bash
sudo certbot renew --force-renewal
sudo systemctl reload apache2
```

---

## 6. Useful Server Commands

```bash
# Restart Apache
sudo systemctl restart apache2

# Check Apache status
sudo systemctl status apache2

# View Apache error log (last 50 lines)
sudo tail -50 /var/log/apache2/error.log

# Check disk usage
df -h

# Check upload folder size
du -sh /var/www/html/upload_files/
```
