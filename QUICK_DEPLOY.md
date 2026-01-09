# 🚀 QUICK DEPLOY COMMANDS

## Right Now (Local):

```bash
# 1. Check status
git status

# 2. Add everything
git add .

# 3. Commit
git commit -m "feat: production Tailwind CSS (99% smaller, 40x faster)"

# 4. Push to GitHub
git push origin main
```

---

## On Production Server:

```bash
# SSH to server
ssh root@165.232.186.118

# Pull updates
cd /var/www/quoteshub
git pull origin main

# Reload server
systemctl reload nginx

# Verify
ls -lh public/assets/css/tailwind.min.css
```

---

## Test URLs:
- **Local**: http://localhost:8000
- **Production**: https://www.thequoteshub.info

---

## What to Check (F12 Console):
✅ No red errors  
✅ No "$ is not defined"  
✅ No Tailwind warnings  
✅ tailwind.min.css loads (50 KB)  

**Done!** 🎉
