# AWS Deployment and Git Integration Specification

This documentation details the hosting deployment architecture, server configurations, and GitHub deployment pipelines for the **NoteHub** application.

---

## 1. Hosting Deployment

NoteHub is hosted on **Amazon Web Services (AWS)** to guarantee high availability, scalability, and robust security.

### 1.1 Infrastructure Overview
* **Virtual Server (VPS):** AWS EC2 Instance.
* **Operating System:** Ubuntu 22.04 LTS (HVM), SSD Volume.
* **Public IPv4 Address:** `44.197.113.192`
* **Wildcard Domain Name:** `http://44-197-113-192.nip.io` (Mapped to the public IP to comply with Google OAuth requirements, which restrict raw IP callback registration).

### 1.2 Software Stack Configuration
The server runs a fully optimized **LAMP** stack configured for production:
* **Web Server:** Apache/2.4.52 (Ubuntu) with `mod_rewrite` enabled to handle Laravel's front-controller pattern.
* **PHP Engine:** PHP 8.2 with required extensions (`openssl`, `pdo_mysql`, `mbstring`, `xml`, `curl`, `zip`).
* **Database Management System:** MySQL Server 8.0.
  * **Database Name:** `notehub`
  * **Database User:** `notehubuser`
  * **Host:** `127.0.0.1` (Configured for internal access only, preventing external database exposure).

### 1.3 Production Optimization
To secure and accelerate the live site, configuration files and routes are compiled in the cache:
```bash
# Clear all local development caches
php artisan optimize:clear

# Cache configurations, routes, and views for faster execution
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 2. GitHub Integration

Version control, deployment pipelines, and collaboration workflows are managed using **GitHub**.

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
Updates are securely synced from the local environment to the live AWS EC2 server using Git:

#### Step 2.2.1: Local Machine Push
Once local changes are tested and verified, they are committed and pushed to GitHub:
```bash
# Stage all changes
git add .

# Create a versioned commit
git commit -m "Commit description details"

# Push to GitHub main branch
git push origin main
```

#### Step 2.2.2: AWS EC2 Server Sync
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

# Apply database updates and re-seed defaults (in production mode)
php artisan migrate --force

# Reclear and compile production caches
php artisan optimize:clear
```
