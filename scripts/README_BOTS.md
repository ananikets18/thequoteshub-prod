# 🤖 The Quotes Hub - Automated Bots Setup Guide

This guide explains how to set up and run automated bots for **The Quotes Hub** on your production server.

## 📋 Overview

We have two automated bots:

1. **Quote Creation Bot** (`bot.php`)
   - Creates random quotes from Excel data
   - Runs every **30 minutes**
   - Logs in as random users and posts quotes

2. **Like & Save Bot** (`like_save_bot.php`)
   - Automatically likes and saves quotes
   - Runs every **5 minutes**
   - Engages with latest and random quotes

## 🚀 Quick Setup (Production Server)

### Step 1: Prepare Your Data Files

Make sure these Excel files exist in your production server:

```bash
/var/www/quoteshub/storage/data/quotes_data.xlsx
/var/www/quoteshub/storage/data/quotes_like_save.xlsx
```

**quotes_data.xlsx** should have 2 sheets:
- **Sheet 1 (Users)**: Columns: `username`, `password`
- **Sheet 2 (Quotes)**: Columns: `author_name`, `quote_text`, `category`

**quotes_like_save.xlsx** should have:
- **Sheet 1 (Users)**: Columns: `username`, `password`

### Step 2: Upload Files to Production

Push your changes to GitHub:

```bash
git add .
git commit -m "Add automated bots for quote creation and engagement"
git push origin main
```

### Step 3: SSH into Your Production Server

```bash
ssh root@your-server-ip
# or
ssh your-username@your-server-ip
```

### Step 4: Navigate to Project Directory

```bash
cd /var/www/quoteshub
```

### Step 5: Pull Latest Changes

```bash
git pull origin main
```

### Step 6: Run the Bot Setup Script

```bash
chmod +x scripts/setup_bots.sh
sudo bash scripts/setup_bots.sh
```

This script will:
- ✅ Create necessary directories (`storage/logs`, `storage/temp`)
- ✅ Set proper permissions
- ✅ Configure cron jobs to run bots automatically
- ✅ Display current cron configuration

### Step 7: Verify Cron Jobs

Check that cron jobs are set up correctly:

```bash
crontab -l
```

You should see:

```bash
# The Quotes Hub - Automated Bots
# Quote Creation Bot - Runs every 30 minutes
*/30 * * * * /usr/bin/php /var/www/quoteshub/scripts/bot.php >> /var/www/quoteshub/storage/logs/quote_bot.log 2>&1

# Like & Save Bot - Runs every 5 minutes
*/5 * * * * /usr/bin/php /var/www/quoteshub/scripts/like_save_bot.php >> /var/www/quoteshub/storage/logs/like_save_bot.log 2>&1
```

## 📊 Monitoring

### View Real-Time Logs

**Quote Creation Bot:**
```bash
tail -f /var/www/quoteshub/storage/logs/quote_bot.log
```

**Like & Save Bot:**
```bash
tail -f /var/www/quoteshub/storage/logs/like_save_bot.log
```

### View Last 50 Lines of Logs

```bash
tail -n 50 /var/www/quoteshub/storage/logs/quote_bot.log
tail -n 50 /var/www/quoteshub/storage/logs/like_save_bot.log
```

### Check Log File Sizes

```bash
ls -lh /var/www/quoteshub/storage/logs/
```

## 🧪 Manual Testing

Test the bots manually before setting up cron:

### Test Quote Creation Bot

```bash
cd /var/www/quoteshub
php scripts/bot.php
```

### Test Like & Save Bot

```bash
cd /var/www/quoteshub
php scripts/like_save_bot.php
```

## ⚙️ Customization

### Change Bot Frequency

Edit crontab:

```bash
crontab -e
```

**Cron Schedule Examples:**

- Every 15 minutes: `*/15 * * * *`
- Every hour: `0 * * * *`
- Every 2 hours: `0 */2 * * *`
- Every day at 9 AM: `0 9 * * *`
- Every Monday at 10 AM: `0 10 * * 1`

### Disable Bots Temporarily

Comment out the cron jobs:

```bash
crontab -e
```

Add `#` before each bot line:

```bash
# */30 * * * * /usr/bin/php /var/www/quoteshub/scripts/bot.php >> /var/www/quoteshub/storage/logs/quote_bot.log 2>&1
# */5 * * * * /usr/bin/php /var/www/quoteshub/scripts/like_save_bot.php >> /var/www/quoteshub/storage/logs/like_save_bot.log 2>&1
```

### Re-enable Bots

Remove the `#` from the cron lines.

## 🔧 Troubleshooting

### Bot Not Running

1. **Check cron service status:**
   ```bash
   systemctl status cron
   # or on some systems:
   systemctl status crond
   ```

2. **Restart cron service:**
   ```bash
   sudo systemctl restart cron
   ```

3. **Check PHP path:**
   ```bash
   which php
   ```
   Update crontab if PHP is in a different location.

### Permission Errors

```bash
sudo chmod -R 755 /var/www/quoteshub/storage
sudo chown -R www-data:www-data /var/www/quoteshub/storage
```

### Excel File Not Found

Verify files exist:

```bash
ls -la /var/www/quoteshub/storage/data/
```

Upload missing files via SFTP or create them on the server.

### cURL SSL Errors

If you see SSL certificate errors, check your server's CA certificates:

```bash
sudo apt-get update
sudo apt-get install ca-certificates
```

### Database Connection Issues

Ensure your `.env` file has correct database credentials:

```bash
cat /var/www/quoteshub/.env
```

## 📝 Log Rotation (Optional)

To prevent log files from growing too large, set up log rotation:

Create `/etc/logrotate.d/quoteshub`:

```bash
sudo nano /etc/logrotate.d/quoteshub
```

Add:

```
/var/www/quoteshub/storage/logs/*.log {
    daily
    rotate 7
    compress
    delaycompress
    missingok
    notifempty
    create 0644 www-data www-data
}
```

Test log rotation:

```bash
sudo logrotate -f /etc/logrotate.d/quoteshub
```

## 🎯 Expected Behavior

### Quote Creation Bot
- Runs every 30 minutes
- Logs in as a random user from Excel
- Creates 1 random quote per execution
- Logs all activity with timestamps

### Like & Save Bot
- Runs every 5 minutes
- Logs in as a random user
- Likes and saves the 3 latest quotes
- Likes and saves 1 random quote
- Logs all activity with timestamps

## 📞 Support

If you encounter issues:

1. Check the log files first
2. Verify Excel data files are properly formatted
3. Ensure cron jobs are active: `crontab -l`
4. Test bots manually to isolate issues
5. Check server resources: `top` or `htop`

## 🔐 Security Notes

- Bot user credentials are stored in Excel files
- Ensure `storage/data/` is not publicly accessible
- Use strong passwords for bot accounts
- Regularly rotate bot account passwords
- Monitor bot activity for unusual patterns

---

**Last Updated:** January 11, 2026  
**Version:** 1.0  
**Author:** The Quotes Hub Development Team
