# ✅ PRE-DEPLOYMENT CHECKLIST

Use this checklist before pushing to production.

## 📝 LOCAL TESTING (Do this FIRST)

### 1. Start Local Server
```bash
# Using PHP built-in server
cd d:\xampp\htdocs\public_html
php -S localhost:8000

# OR use XAMPP/Laragon
# Visit: http://localhost/public_html
```

### 2. Check Browser Console (F12)
Open developer tools and check for:
- [ ] **No** `$ is not defined` errors
- [ ] **No** Tailwind CDN warnings
- [ ] **No** 404 errors for CSS files
- [ ] **No** 404 errors for JS files
- [ ] jQuery loads successfully

### 3. Test Core Functionality
- [ ] Homepage loads
- [ ] Login page works
- [ ] Registration page works
- [ ] Quote creation works
- [ ] Like/Save buttons work
- [ ] Notifications appear
- [ ] User profile loads
- [ ] Search functionality works

### 4. Check Asset Loading
Open Network tab (F12 → Network) and verify:
- [ ] `style.css` loads (200 status)
- [ ] `quotes-index.css` loads (200 status)
- [ ] `hero-section.css` loads (200 status)
- [ ] `scroll.module.js` loads (200 status)
- [ ] jQuery loads from CDN
- [ ] Font Awesome icons appear

### 5. Verify jQuery Works
Open Console (F12) and type:
```javascript
$('body').css('background-color')
```
- [ ] Should return a color value (not an error)

---

## 🚀 GIT PREPARATION

### 1. Check Git Status
```bash
git status
```

Should see changes in:
- [ ] `app/views/layouts/base.php`
- [ ] Multiple view files (Tailwind removed)
- [ ] `.env.production.example`
- [ ] `DEPLOYMENT_GUIDE.md`
- [ ] `deploy.sh`
- [ ] `CHECKLIST.md`

### 2. Verify .env is NOT Staged
```bash
git status | grep ".env"
```
- [ ] Should **NOT** show `.env` (only `.env.production.example` is OK)

### 3. Review Changes
```bash
git diff app/views/layouts/base.php
```
- [ ] jQuery moved to `<head>`
- [ ] Tailwind CDN removed
- [ ] Font Awesome still present

---

## 📤 COMMIT & PUSH

```bash
# Stage all changes
git add .

# Commit with descriptive message
git commit -m "fix: production frontend optimization

- Move jQuery to <head> for proper load order
- Remove Tailwind CDN from all pages (not production-safe)
- Add .env.production.example template
- Add deployment guide and automation scripts
- Fix asset loading order for better performance"

# Push to GitHub
git push origin main
```

Expected output:
- [ ] Push successful
- [ ] GitHub shows new commit

---

## 🖥️ PRODUCTION DEPLOYMENT

### 1. SSH to Server
```bash
ssh root@your_server_ip
```

### 2. Navigate to Project
```bash
cd /var/www/quoteshub
```

### 3. Pull Latest Changes
```bash
git pull origin main
```

### 4. Verify .env Exists (CRITICAL)
```bash
cat .env | head -5
```
- [ ] File exists
- [ ] Has production database credentials
- [ ] `APP_DEBUG=false`
- [ ] `APP_ENV=production`

If missing:
```bash
cp .env.production.example .env
nano .env  # Edit with real values
```

### 5. Set Permissions
```bash
chmod -R 755 /var/www/quoteshub
chown -R www-data:www-data /var/www/quoteshub/public/uploads
chown -R www-data:www-data /var/www/quoteshub/storage
```

### 6. Clear Cache
```bash
rm -rf /var/www/quoteshub/storage/temp/*
```

### 7. Reload Nginx
```bash
systemctl reload nginx
```

### 8. Check Service Status
```bash
systemctl status nginx
systemctl status php8.1-fpm
```
- [ ] Nginx is active (running)
- [ ] PHP-FPM is active (running)

---

## 🧪 PRODUCTION TESTING

### 1. Visit Website
Open: https://www.thequoteshub.info

### 2. Check Browser Console
Press F12 and check Console tab:
- [ ] **No** JavaScript errors
- [ ] **No** `$ is not defined`
- [ ] **No** 404 errors
- [ ] **No** Tailwind warnings

### 3. Check Network Tab
- [ ] CSS files load quickly
- [ ] JS files load quickly
- [ ] No timeouts
- [ ] Status codes are 200

### 4. Test Functionality
- [ ] Login works
- [ ] Quote creation works
- [ ] Like button works
- [ ] Save button works
- [ ] Notifications work
- [ ] User profile loads

### 5. Check Load Speed
- [ ] Page loads in < 3 seconds
- [ ] No spinning loader for too long
- [ ] Images appear quickly

### 6. Mobile Testing (Optional)
Use browser DevTools device emulation:
- [ ] Responsive design works
- [ ] Touch interactions work
- [ ] Mobile menu works

---

## 🔍 ERROR CHECKING

If something doesn't work:

### Check Nginx Logs
```bash
tail -50 /var/log/nginx/error.log
```

### Check PHP Logs
```bash
tail -50 /var/log/php8.1-fpm.log
```

### Check File Permissions
```bash
ls -la /var/www/quoteshub/public/assets/css/
ls -la /var/www/quoteshub/public/assets/js/
```
Should be readable (644 or 755)

### Verify Database Connection
```bash
mysql -u your_db_user -p your_db_name
# If fails, check .env credentials
```

---

## ✅ SUCCESS CRITERIA

Your deployment is successful when:
- ✅ No console errors
- ✅ All assets load (CSS, JS, images)
- ✅ jQuery works
- ✅ Core features work (login, quotes, likes)
- ✅ Page loads fast (< 3 seconds)
- ✅ No Tailwind CDN warnings
- ✅ Mobile responsive

---

## 🆘 ROLLBACK (If needed)

If deployment breaks something:

```bash
cd /var/www/quoteshub
git log  # Find previous commit hash
git revert HEAD  # Undo last commit
# OR
git checkout <previous-commit-hash>
systemctl reload nginx
```

---

## 📊 WHAT WE FIXED

| Item | Status |
|------|--------|
| jQuery load order | ✅ Fixed |
| Tailwind CDN removed | ✅ Fixed |
| Asset paths | ✅ Verified |
| .env security | ✅ Protected |
| Deployment docs | ✅ Created |
| Deploy script | ✅ Created |

---

## 🎉 NEXT STEPS (After successful deploy)

1. Monitor for 24 hours
2. Check Google Analytics (if enabled)
3. Review error logs daily
4. Consider these optimizations:
   - [ ] Generate Tailwind production CSS
   - [ ] Enable HTTPS/HTTP2
   - [ ] Add CDN for static assets
   - [ ] Implement Redis caching
   - [ ] Set up automated backups

---

**You're ready to deploy!** 🚀

Follow this checklist step by step, and your deployment will be smooth and professional.
