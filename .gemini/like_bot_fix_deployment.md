# Like Bot Fix - Deployment Guide

## Problem Identified
The like bot was working but **not targeting the latest created quotes**. It was:
- Extracting quote IDs from homepage HTML using regex
- Not guaranteeing proper order (latest first)
- Sometimes liking old quotes instead of newly created ones

## Solution Implemented
Changed the bot to **query the database directly** for the 10 most recently created quotes, ordered by `created_at DESC`.

## Changes Made

### File: `scripts/like_save_bot.php`

**What Changed:**
- Removed HTML regex scraping for quote IDs
- Added database query to fetch latest 10 approved quotes
- Now properly orders by creation date (newest first)
- Bot will now like/save the 3 newest quotes + 1 random from the top 10

**Key Code Change (lines 132-151):**
```php
// Connect to database to get latest quotes
require_once __DIR__ . '/../config/database.php';

// Get the 10 most recently created quotes
$stmt = $conn->prepare("SELECT id FROM quotes WHERE status = 'approved' ORDER BY created_at DESC LIMIT 10");
$stmt->execute();
$result = $stmt->get_result();

$quoteIds = [];
while ($row = $result->fetch_assoc()) {
    $quoteIds[] = $row['id'];
}
$stmt->close();
```

## Deployment to Production

### Step 1: Backup Current Bot
```bash
cd /var/www/quoteshub
cp scripts/like_save_bot.php scripts/like_save_bot.php.backup_$(date +%Y%m%d_%H%M%S)
```

### Step 2: Upload New Version
Upload the updated `scripts/like_save_bot.php` file from your local machine to production:

```bash
# From your local machine (if you have SCP access)
scp d:\xampp\htdocs\public_html\scripts\like_save_bot.php root@your-server-ip:/var/www/quoteshub/scripts/

# OR manually copy the file content and paste it
```

### Step 3: Test the Bot
```bash
cd /var/www/quoteshub
php scripts/like_save_bot.php
```

**Expected Output:**
```
============================================================
[2026-01-19 XX:XX:XX] Like & Save Bot Started
============================================================
[INFO] Trying to log in as: [username]
[INFO] CSRF token extracted successfully
[SUCCESS] Login response received.
[INFO] Using login CSRF token for requests
[INFO] Found 10 latest quotes from database.
[INFO] Processing latest Quote ID: [newest quote]
[SUCCESS] Liked Quote ID: [newest quote]
[SUCCESS] Saved Quote ID: [newest quote]
...
```

### Step 4: Verify It's Working
```bash
# Check the log after the next cron run (runs every 5 minutes)
tail -50 /var/www/quoteshub/storage/logs/like_save_bot.log
```

## Manual Deployment (Copy-Paste Method)

If you can't upload the file, here's how to manually update it:

```bash
# 1. Backup
cp /var/www/quoteshub/scripts/like_save_bot.php /var/www/quoteshub/scripts/like_save_bot.php.backup

# 2. Edit the file
nano /var/www/quoteshub/scripts/like_save_bot.php
```

Then find this section (around line 133-143):
```php
// Extract quote IDs using regex
preg_match_all('/\/quote\/(\d+)/', $pageHtml, $matches);
$quoteIds = array_unique($matches[1]);

if (empty($quoteIds)) {
    echo "[ERROR] No quotes found on the page.\n";
    curl_close($ch);
    exit(1);
}

echo "[INFO] Found " . count($quoteIds) . " quotes.\n";
```

**Replace it with:**
```php
// Connect to database to get latest quotes
require_once __DIR__ . '/../config/database.php';

// Get the 10 most recently created quotes
$stmt = $conn->prepare("SELECT id FROM quotes WHERE status = 'approved' ORDER BY created_at DESC LIMIT 10");
$stmt->execute();
$result = $stmt->get_result();

$quoteIds = [];
while ($row = $result->fetch_assoc()) {
    $quoteIds[] = $row['id'];
}
$stmt->close();

if (empty($quoteIds)) {
    echo "[ERROR] No quotes found in database.\n";
    curl_close($ch);
    exit(1);
}

echo "[INFO] Found " . count($quoteIds) . " latest quotes from database.\n";
```

Save the file (Ctrl+O, Enter, Ctrl+X in nano).

## Verification

After deployment, the bot will now:
1. ✅ Query the database for the 10 newest quotes
2. ✅ Like/save the 3 most recent ones
3. ✅ Like/save 1 random quote from the top 10
4. ✅ Always target newly created content

## Rollback (If Needed)

If something goes wrong:
```bash
cd /var/www/quoteshub
cp scripts/like_save_bot.php.backup scripts/like_save_bot.php
```

## Additional Improvements Made

1. **CSRF Token Handling**: Now uses login CSRF token as fallback (eliminates warning)
2. **Database Query**: Direct database access ensures accurate ordering
3. **Better Logging**: Clearer messages about what quotes are being processed

## Notes

- The bot runs every 5 minutes via cron
- It uses different random users from the Excel file
- Each run processes 4 quotes total (3 latest + 1 random)
- The database connection is already configured in `config/database.php`
