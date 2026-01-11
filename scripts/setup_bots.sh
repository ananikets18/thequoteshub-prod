#!/bin/bash
# 🤖 Bot Setup Script for The Quotes Hub
# This script sets up automated bots on your production server

echo "🤖 Setting up automated bots for The Quotes Hub..."

# Define paths
PROJECT_DIR="/var/www/quoteshub"
SCRIPTS_DIR="$PROJECT_DIR/scripts"
STORAGE_DIR="$PROJECT_DIR/storage"
LOGS_DIR="$STORAGE_DIR/logs"
TEMP_DIR="$STORAGE_DIR/temp"

# Create necessary directories
echo "📁 Creating directories..."
mkdir -p "$LOGS_DIR"
mkdir -p "$TEMP_DIR"

# Set proper permissions
echo "🔒 Setting permissions..."
chmod +x "$SCRIPTS_DIR/bot.php"
chmod +x "$SCRIPTS_DIR/like_save_bot.php"
chmod -R 755 "$LOGS_DIR"
chmod -R 755 "$TEMP_DIR"
chown -R www-data:www-data "$LOGS_DIR"
chown -R www-data:www-data "$TEMP_DIR"

# Create cron jobs
echo "⏰ Setting up cron jobs..."

# Backup existing crontab
crontab -l > /tmp/crontab_backup_$(date +%Y%m%d_%H%M%S).txt 2>/dev/null

# Remove old bot cron jobs if they exist
crontab -l 2>/dev/null | grep -v "quote.*bot.php" | grep -v "like_save_bot.php" | crontab -

# Add new cron jobs
(crontab -l 2>/dev/null; echo "") | crontab -
(crontab -l 2>/dev/null; echo "# The Quotes Hub - Automated Bots") | crontab -
(crontab -l 2>/dev/null; echo "# Quote Creation Bot - Runs every 30 minutes") | crontab -
(crontab -l 2>/dev/null; echo "*/30 * * * * /usr/bin/php $SCRIPTS_DIR/bot.php >> $LOGS_DIR/quote_bot.log 2>&1") | crontab -
(crontab -l 2>/dev/null; echo "") | crontab -
(crontab -l 2>/dev/null; echo "# Like & Save Bot - Runs every 5 minutes") | crontab -
(crontab -l 2>/dev/null; echo "*/5 * * * * /usr/bin/php $SCRIPTS_DIR/like_save_bot.php >> $LOGS_DIR/like_save_bot.log 2>&1") | crontab -

echo ""
echo "✅ Bot setup complete!"
echo ""
echo "📋 Current cron jobs:"
crontab -l | grep -A 1 "Quotes Hub"
echo ""
echo "📊 Monitor logs at:"
echo "   - Quote Bot: $LOGS_DIR/quote_bot.log"
echo "   - Like/Save Bot: $LOGS_DIR/like_save_bot.log"
echo ""
echo "🔍 To view logs in real-time:"
echo "   tail -f $LOGS_DIR/quote_bot.log"
echo "   tail -f $LOGS_DIR/like_save_bot.log"
echo ""
echo "⚠️  Note: Make sure your Excel data files exist:"
echo "   - $STORAGE_DIR/data/quotes_data.xlsx"
echo "   - $STORAGE_DIR/data/quotes_like_save.xlsx"
echo ""
