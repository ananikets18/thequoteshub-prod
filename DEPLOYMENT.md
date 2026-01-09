# 🚀 DigitalOcean App Platform Deployment Guide

## Overview
This guide will help you deploy **The Quotes Hub** to DigitalOcean App Platform with automatic deployments from GitHub.

---

## 📋 Prerequisites

1. **GitHub Account** with your code pushed to a repository
2. **DigitalOcean Account** ([Sign up here](https://www.digitalocean.com/))
3. **Domain configured** (thequoteshub.info pointing to DigitalOcean)
4. **Local testing complete** with all features working

---

## 🔧 Step-by-Step Deployment

### **Step 1: Prepare Your GitHub Repository**

1. **Create a GitHub repository** (if not already created):
   ```bash
   # In your project directory
   git init
   git add .
   git commit -m "Initial commit - Ready for deployment"
   git branch -M main
   git remote add origin https://github.com/YOUR_USERNAME/quoteshub.git
   git push -u origin main
   ```

2. **Important files to commit**:
   - ✅ All code files
   - ✅ `.do/app.yaml` (configuration)
   - ✅ `.env.example` (template)
   - ✅ `composer.json`
   - ✅ `.htaccess`
   - ❌ `.env` (DO NOT COMMIT - add to .gitignore)
   - ❌ `config/.env` (DO NOT COMMIT)

3. **Create/Update `.gitignore`**:
   ```
   .env
   config/.env
   /vendor/
   /storage/logs/*
   /storage/temp/*
   .DS_Store
   Thumbs.db
   ```

---

### **Step 2: Update App Configuration**

1. **Edit `.do/app.yaml`** and update these lines:
   ```yaml
   github:
     repo: YOUR_GITHUB_USERNAME/YOUR_REPO_NAME  # e.g., john/quoteshub
     branch: main
   ```

2. **Commit the changes**:
   ```bash
   git add .do/app.yaml
   git commit -m "Update GitHub repo in app.yaml"
   git push origin main
   ```

---

### **Step 3: Create App on DigitalOcean**

1. **Login to DigitalOcean** → Navigate to [App Platform](https://cloud.digitalocean.com/apps)

2. **Click "Create App"**

3. **Connect GitHub**:
   - Select "GitHub" as source
   - Authorize DigitalOcean to access your GitHub
   - Select your repository: `YOUR_USERNAME/quoteshub`
   - Select branch: `main`
   - ✅ Check "Autodeploy" (deploys automatically on push)

4. **Configure Resources**:
   
   **Web Service:**
   - Name: `web`
   - Environment: `PHP`
   - Build Command: `composer install --no-dev --optimize-autoloader`
   - Run Command: (use default or from app.yaml)
   - HTTP Port: `8080`
   - Instance Size: `Basic ($5/mo)` or `Professional ($12/mo)`

   **Database:**
   - Click "Add Resource" → "Database"
   - Engine: `MySQL 8`
   - Name: `quoteshub-db`
   - Plan: `Basic ($15/mo)` for production

5. **Environment Variables** (DigitalOcean will detect from app.yaml):
   
   Auto-configured:
   - `DB_HOST` → ${db.HOSTNAME}
   - `DB_USER` → ${db.USERNAME}
   - `DB_PASS` → ${db.PASSWORD}
   - `DB_NAME` → ${db.DATABASE}
   
   Verify/Add manually:
   - `APP_URL` → `https://www.thequoteshub.info`
   - `APP_ENV` → `production`
   - `APP_DEBUG` → `false`

6. **App Info**:
   - Name: `quoteshub`
   - Region: `New York (nyc)` or closest to your users

7. **Review and Create**

---

### **Step 4: Configure Domain**

1. **In App Settings** → **Domains**:
   - Click "Add Domain"
   - Enter: `www.thequoteshub.info`
   - Add: `thequoteshub.info` (will redirect to www)

2. **Update DNS** (at your domain registrar):
   ```
   Type: CNAME
   Name: www
   Value: [Your App URL from DigitalOcean]
   TTL: 3600
   
   Type: A
   Name: @
   Value: [DigitalOcean IP provided]
   TTL: 3600
   ```

3. **SSL Certificate**:
   - DigitalOcean automatically provisions Let's Encrypt SSL
   - Wait 5-10 minutes for DNS propagation and SSL activation

---

### **Step 5: Import Database**

1. **Export your local database**:
   ```bash
   # From phpMyAdmin or command line
   mysqldump -u root -p u821584890_quoteshub > database_backup.sql
   ```

2. **Connect to DigitalOcean Database**:
   - In App Platform → Database Component → "Connection Details"
   - Note: Host, Port, Username, Password, Database name

3. **Import database**:
   
   **Option A: Using MySQL Workbench**
   - Connect using DigitalOcean database credentials
   - Import SQL file
   
   **Option B: Command line**
   ```bash
   mysql -h your-db-host.db.ondigitalocean.com -P 25060 -u doadmin -p database_name < database_backup.sql
   ```
   
   **Option C: phpMyAdmin on DO**
   - Install phpMyAdmin as separate app or use DO database console

---

### **Step 6: First Deployment**

1. **Trigger deployment**:
   - Either: Wait for automatic deployment (if you pushed to GitHub)
   - Or: Manual deploy from DigitalOcean dashboard → "Deploy"

2. **Monitor deployment**:
   - DigitalOcean console → Your App → "Activity"
   - Watch build logs for errors
   - Typical deployment time: 2-5 minutes

3. **Check deployment status**:
   - ✅ Building
   - ✅ Deploying
   - ✅ Live

---

### **Step 7: Verify Deployment**

1. **Test your site**:
   - Visit: `https://www.thequoteshub.info`
   - Test key features:
     - Homepage loads
     - Database connection works
     - User registration/login
     - Quote creation
     - Image uploads

2. **Check logs**:
   - DigitalOcean console → Runtime Logs
   - Look for errors or warnings

---

## 🔄 Automatic Deployment Workflow

Once set up, your workflow is simple:

```bash
# 1. Make changes locally
# Edit files in VS Code or your editor

# 2. Test locally
# Test on XAMPP/local server

# 3. Commit and push
git add .
git commit -m "Add new feature or fix bug"
git push origin main

# 4. Automatic deployment happens!
# DigitalOcean detects push → builds → deploys → live in 2-5 minutes
```

### What Happens Automatically:
1. **GitHub webhook** notifies DigitalOcean of push
2. **DigitalOcean** pulls latest code
3. **Build process**:
   - Runs `composer install`
   - Prepares environment variables
   - Configures Apache/PHP
4. **Deploy**:
   - New version goes live
   - Old version kept as rollback option
5. **Health check**:
   - Verifies site is responding
   - Rolls back if health check fails

---

## 🛠️ Common Tasks

### **View Logs**
```bash
# From DigitalOcean Console
App → Runtime Logs → Select time range
```

### **Rollback Deployment**
```bash
# From DigitalOcean Console
App → Activity → Select previous deployment → "Rollback"
```

### **Update Environment Variables**
```bash
# From DigitalOcean Console
App → Settings → Environment Variables → Edit
# Note: Requires redeployment
```

### **Scale App**
```bash
# From DigitalOcean Console
App → Settings → Resources → Adjust instance size or count
```

### **Database Backup**
```bash
# Automatic daily backups included
# Manual backup: Database → Backups → "Create Backup"
```

---

## 🐛 Troubleshooting

### **Deployment Failed**

1. Check build logs in DigitalOcean Activity tab
2. Common issues:
   - Composer dependencies: Check `composer.json`
   - PHP version: Ensure compatibility
   - Missing files: Check .gitignore

### **Database Connection Error**

1. Verify environment variables are set correctly
2. Check database component is running
3. Ensure `config/env.php` reads environment variables properly

### **Site Not Loading**

1. Check health check status
2. Verify Apache/PHP is running
3. Check runtime logs for PHP errors
4. Ensure `.htaccess` is present

### **Images Not Uploading**

1. Check write permissions on `/public/uploads/`
2. Verify `UPLOAD_MAX_SIZE` environment variable
3. Check PHP memory limits
4. Consider using DigitalOcean Spaces for file storage

---

## 💰 Cost Estimate

| Resource | Plan | Cost |
|----------|------|------|
| Web Service | Basic (512MB RAM) | $5/mo |
| MySQL Database | Basic (1GB) | $15/mo |
| **Total** | | **$20/mo** |

*Upgrade as traffic grows*

---

## 📊 Monitoring

### **Built-in Monitoring**
- CPU, Memory, Bandwidth usage
- Request rate and response time
- Error rate

### **Alerts**
- Set up alerts for:
  - High CPU/Memory usage
  - Deployment failures
  - Database connection issues

---

## 🔐 Security Best Practices

1. **Never commit `.env` files** to Git
2. **Use strong database passwords**
3. **Enable HTTPS only** (automatic with DO)
4. **Keep dependencies updated**: `composer update`
5. **Monitor logs** for suspicious activity
6. **Regular database backups**
7. **Use DigitalOcean Firewall** for database

---

## 📚 Additional Resources

- [DigitalOcean App Platform Docs](https://docs.digitalocean.com/products/app-platform/)
- [PHP on App Platform](https://docs.digitalocean.com/products/app-platform/languages-frameworks/php/)
- [App Spec Reference](https://docs.digitalocean.com/products/app-platform/reference/app-spec/)
- [Troubleshooting Guide](https://docs.digitalocean.com/products/app-platform/how-to/troubleshoot-deploys/)

---

## ✅ Deployment Checklist

- [ ] Code pushed to GitHub
- [ ] `.gitignore` configured (excludes .env)
- [ ] `.do/app.yaml` updated with GitHub repo
- [ ] DigitalOcean App created
- [ ] GitHub connected with autodeploy enabled
- [ ] Database component added
- [ ] Environment variables configured
- [ ] Domain configured and DNS updated
- [ ] Database imported
- [ ] First deployment successful
- [ ] Site tested and working
- [ ] Monitoring and alerts set up

---

## 🎉 You're Live!

Your site is now deployed with automatic updates:
- **Production URL**: https://www.thequoteshub.info
- **Automatic Deployments**: Push to `main` branch
- **SSL**: Automatically managed
- **Backups**: Daily database backups
- **Monitoring**: Built-in performance tracking

### Next Push Flow:
```bash
# Local: Make changes → Test → Commit → Push
git push origin main

# DigitalOcean: Auto-detect → Build → Deploy → Live (2-5 min)
```

**Happy Deploying! 🚀**
