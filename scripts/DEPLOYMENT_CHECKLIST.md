# 🚀 Quick Deployment Checklist for Automated Bots

## ✅ Pre-Deployment Checklist

- [x] Bot scripts updated (`bot.php`, `like_save_bot.php`)
- [x] Setup script created (`setup_bots.sh`)
- [x] Documentation created (`README_BOTS.md`)
- [x] Deployment script updated (`deploy.sh`)
- [x] Excel data files exist in `storage/data/`

## 📦 Deployment Steps

### 1. Commit and Push Changes

```bash
git add .
git commit -m "🤖 Add automated bots for quote creation and engagement"
git push origin main
```

### 2. SSH into Production Server

```bash
ssh root@your-digitalocean-server-ip
```

### 3. Deploy

```bash
cd /var/www/quoteshub
bash deploy.sh
```

This will automatically:
- Pull latest code
- Set permissions
- **Set up cron jobs for bots**
- Reload Nginx

### 4. Verify Bots Are Running

```bash
# Check cron jobs
crontab -l

# Test bots manually
php scripts/bot.php
php scripts/like_save_bot.php

# Monitor logs
tail -f storage/logs/quote_bot.log
tail -f storage/logs/like_save_bot.log
```

## 🎯 What the Bots Do

### Quote Creation Bot
- **Frequency:** Every 30 minutes
- **Action:** Creates 1 random quote from Excel data
- **Log:** `storage/logs/quote_bot.log`

### Like & Save Bot
- **Frequency:** Every 5 minutes
- **Action:** Likes/saves 3 latest quotes + 1 random quote
- **Log:** `storage/logs/like_save_bot.log`

## 📊 Monitoring Commands

```bash
# View real-time logs
tail -f /var/www/quoteshub/storage/logs/quote_bot.log
tail -f /var/www/quoteshub/storage/logs/like_save_bot.log

# Check last execution
tail -n 20 /var/www/quoteshub/storage/logs/quote_bot.log

# Check cron status
systemctl status cron

# List all cron jobs
crontab -l
```

## 🔧 Troubleshooting

### Bots Not Running?

1. Check cron service:
   ```bash
   systemctl status cron
   sudo systemctl restart cron
   ```

2. Check permissions:
   ```bash
   ls -la /var/www/quoteshub/scripts/
   chmod +x /var/www/quoteshub/scripts/*.php
   ```

3. Test manually:
   ```bash
   cd /var/www/quoteshub
   php scripts/bot.php
   ```

### View Errors

```bash
# Check PHP errors
tail -f /var/log/php-fpm/error.log

# Check system logs
journalctl -u cron -f
```

## 🎉 Success Indicators

You'll know it's working when:
- ✅ Cron jobs appear in `crontab -l`
- ✅ Log files are created in `storage/logs/`
- ✅ New quotes appear on the website every 30 minutes
- ✅ Quotes get likes/saves every 5 minutes
- ✅ No errors in log files

## 📞 Need Help?

Read the full documentation: `scripts/README_BOTS.md`
