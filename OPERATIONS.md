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
1. Code is committed and pushed to `main` on GitHub
2. GitHub Actions checks out the latest code
3. rsync transfers only the changed files to the server — skipping `connect.php`, `upload_files/`, and `pictures/` so live data is never overwritten
4. File ownership is reset so Apache can serve the files
5. Changes are live within seconds — no manual steps needed

### One-time setup

**Step 1 — Generate an SSH key pair on the server** (from the Lightsail browser terminal):
```bash
ssh-keygen -t ed25519 -f ~/.ssh/deploy_key -N ""
```

**Step 2 — Add the public key to authorized_keys:**
```bash
cat ~/.ssh/deploy_key.pub >> ~/.ssh/authorized_keys
```

**Step 3 — Copy the private key output:**
```bash
cat ~/.ssh/deploy_key
```
Copy the full output including the `-----BEGIN OPENSSH PRIVATE KEY-----` and `-----END OPENSSH PRIVATE KEY-----` lines.

**Step 4 — Add it as a GitHub Actions secret:**

> GitHub → repository → **Settings** → **Secrets and variables** → **Actions** → **New repository secret**
> - Name: `SSH_PRIVATE_KEY`
> - Value: paste the private key

**Step 5 — Add collaborator** (so UNILORIN team can push changes):

> GitHub → repository → **Settings** → **Collaborators** → **Add people**
> Enter the GitHub username provided by Mr. Abubakar

### Monitor deployments

> GitHub → repository → **Actions** tab

Each run shows a full log. Green tick = deployed successfully. Red cross = something failed — click the run to see exactly what went wrong.

### What gets deployed / what is protected

| Item | Deployed on push | Protected |
|---|---|---|
| PHP source files | Yes | |
| Frontend assets (JS, CSS) | Yes | |
| `connect.php` | | Never overwritten |
| `upload_files/` | | Never overwritten |
| `pictures/` | | Never overwritten |

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
