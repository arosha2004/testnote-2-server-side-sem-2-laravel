# AWS Deployment and Git Integration Specification

This documentation details the hosting deployment architecture, server security hardening steps, and GitHub deployment pipelines for the **NoteHub** application.

---

## 1. Hosting Deployment

### 1.1 Features & Options Used
* **Computer Instance:** AWS EC2 instance running **Ubuntu 24.04 LTS**.
* **Database Service:** AWS RDS (MySQL/Aurora) deployed securely within a **private subnet**.
* **Network Location:** AWS Virtual Private Cloud (VPC) isolated environment equipped with custom security groups acting as stateful firewalls.
* **Web Server/Proxy:** **Nginx** configured to drop unauthorized file requests and proxy to **PHP 8.3-FPM**.
* **Encryption:** AWS EBS Volume encryption at rest using **AES-256** standards.
* **Authentication:** **2048-bit RSA private key (`.pem`)** strictly required for SSH access; password-based SSH authentication is disabled.

### 1.2 Security Implementation Steps

#### Step 1: EC2 Setup and SSH Security
* An Ubuntu 24.04 LTS EC2 instance was created to host the application.
* **Security Measures:**
  * A 2048-bit private RSA key file (`.pem`) was generated for SSH access.
  * Password-based SSH login was disabled in the SSH daemon configuration (`PasswordAuthentication no`).
  * Only authorized users holding the matching `.pem` file can access the server.

#### Step 2: AWS Security Groups
Security groups were configured as stateful firewalls controlling traffic to resources:
* **EC2 Web Server Security Group:**
  * Port `80` (HTTP) and Port `443` (HTTPS) were opened for public web access.
  * Port `22` (SSH) was strictly restricted to trusted administrative IP addresses only.
* **RDS Database Security Group:**
  * Public database access disabled.
  * Port `3306` (MySQL) configured to only accept incoming connections originating from the EC2 web server's security group.

#### Step 3: Nginx & Server Hardening
Nginx and PHP 8.3-FPM were installed, configured, and hardened:
* The Nginx root directory was set strictly to Laravel's `/public` folder.
* Access to hidden files, configuration folders, and sensitive system logs (such as `.env`, `.git`, `.htaccess`) is blocked at the Nginx level.
* Added custom security headers to reduce web vulnerabilities (e.g., `X-Frame-Options`, `X-Content-Type-Options`, `Content-Security-Policy`, `Referrer-Policy`).

#### Step 4: SSL/HTTPS Configurations
Certbot was utilized to provision and manage Let's Encrypt SSL/TLS certificates:
* Configured automated redirection of all HTTP traffic to HTTPS (Port 443).
* User data and session tokens are encrypted in transit using secure TLS cipher suites.

#### Step 5: File Permission Security
Linux file permissions were hardened to protect application source files:
* Files and directories are owned by the `ubuntu` user and belong to the `www-data` group.
* Standard directory permissions set to `755` and files set to `644`.
* Only the necessary directories (`storage/` and `bootstrap/cache/`) are granted write permissions (`775`) for the web server process.

#### Step 6: Environment Variable Protection
All sensitive runtime configurations and credentials are stored strictly in the `.env` file rather than being hardcoded in the source code:
* Database credentials and passwords
* Application Encryption Key (`APP_KEY`)
* Third-Party OAuth secrets (Google Client ID and Secret)
* Mail Server credentials

---

## 2. GitHub Integration

Version control and deployment pipelines are managed securely via **GitHub**.

### 2.1 Repository Information
* **Remote Repository URL:** [https://github.com/arosha2004/testnote-2-server-side-sem-2-laravel](https://github.com/arosha2004/testnote-2-server-side-sem-2-laravel)
* **Default Branch:** `main` (Protected deployment branch).
* **Version Control Exclusions (`.gitignore`):**
  * Vendor dependencies (`/vendor/`)
  * Frontend packages (`/node_modules/`)
  * Local configuration files (`.env`)
  * Local SQLite databases (`*.sqlite`)
  * Compiled CSS/JS assets from build directories (`/public/build/`).

### 2.2 Server Deployment Workflow
Updates are securely synced from the local development machine to the live AWS EC2 server using Git:

#### Local Machine Push
Once local changes are verified, they are committed and pushed to GitHub:
```bash
# Stage changes
git add .

# Create commit
git commit -m "Commit description details"

# Push to GitHub main branch
git push origin main
```

#### AWS EC2 Server Sync
Connect to the server via SSH using the private key pair (`notehub-key.pem`) and execute the deployment pipeline:
```bash
# Connect to the EC2 Instance
ssh -i "path/to/notehub-key.pem" ubuntu@44.197.113.192

# Navigate to the web deployment directory
cd /var/www/notehub

# Pull the latest commits from the GitHub main branch
git pull origin main

# Recompile frontend assets for production
npm install
npm run build

# Apply database migrations
php artisan migrate --force

# Reclear and compile production caches
php artisan optimize:clear
```
