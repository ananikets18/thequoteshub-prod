#!/bin/bash

# 🤖 Bot Status Checker for The Quotes Hub
# This script checks if the automated bots are running correctly on production

echo "=================================================="
echo "🤖 The Quotes Hub - Bot Status Checker"
echo "=================================================="
echo ""

# Colors for output
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Check if we're on the production server
if [ ! -d "/var/www/quoteshub" ]; then
    echo -e "${RED}❌ Error: Not on production server${NC}"
    echo "This script should be run on the production server at /var/www/quoteshub"
    exit 1
fi

cd /var/www/quoteshub

echo "1️⃣  Checking Cron Jobs..."
echo "-----------------------------------"
CRON_COUNT=$(crontab -l 2>/dev/null | grep -c "quoteshub")
if [ "$CRON_COUNT" -ge 2 ]; then
    echo -e "${GREEN}✅ Cron jobs are configured${NC}"
    echo ""
    crontab -l | grep "quoteshub"
else
    echo -e "${RED}❌ Cron jobs NOT found${NC}"
    echo "Run: sudo bash scripts/setup_bots.sh"
fi
echo ""

echo "2️⃣  Checking Log Files..."
echo "-----------------------------------"
if [ -f "storage/logs/quote_bot.log" ]; then
    echo -e "${GREEN}✅ Quote bot log exists${NC}"
    LOG_SIZE=$(du -h storage/logs/quote_bot.log | cut -f1)
    echo "   Size: $LOG_SIZE"
    echo "   Last 3 entries:"
    tail -n 3 storage/logs/quote_bot.log | sed 's/^/   /'
else
    echo -e "${RED}❌ Quote bot log NOT found${NC}"
fi
echo ""

if [ -f "storage/logs/like_save_bot.log" ]; then
    echo -e "${GREEN}✅ Like/Save bot log exists${NC}"
    LOG_SIZE=$(du -h storage/logs/like_save_bot.log | cut -f1)
    echo "   Size: $LOG_SIZE"
    echo "   Last 3 entries:"
    tail -n 3 storage/logs/like_save_bot.log | sed 's/^/   /'
else
    echo -e "${RED}❌ Like/Save bot log NOT found${NC}"
fi
echo ""

echo "3️⃣  Checking Data Files..."
echo "-----------------------------------"
if [ -f "storage/data/quotes_data.xlsx" ]; then
    echo -e "${GREEN}✅ quotes_data.xlsx exists${NC}"
    FILE_SIZE=$(du -h storage/data/quotes_data.xlsx | cut -f1)
    echo "   Size: $FILE_SIZE"
else
    echo -e "${RED}❌ quotes_data.xlsx NOT found${NC}"
fi

if [ -f "storage/data/quotes_like_save.xlsx" ]; then
    echo -e "${GREEN}✅ quotes_like_save.xlsx exists${NC}"
    FILE_SIZE=$(du -h storage/data/quotes_like_save.xlsx | cut -f1)
    echo "   Size: $FILE_SIZE"
else
    echo -e "${RED}❌ quotes_like_save.xlsx NOT found${NC}"
fi
echo ""

echo "4️⃣  Checking Cron Service..."
echo "-----------------------------------"
if systemctl is-active --quiet cron; then
    echo -e "${GREEN}✅ Cron service is running${NC}"
elif systemctl is-active --quiet crond; then
    echo -e "${GREEN}✅ Crond service is running${NC}"
else
    echo -e "${RED}❌ Cron service is NOT running${NC}"
    echo "Run: sudo systemctl start cron"
fi
echo ""

echo "5️⃣  Testing Bot Scripts..."
echo "-----------------------------------"
echo "Testing quote creation bot..."
if php scripts/bot.php 2>&1 | grep -q "Error"; then
    echo -e "${RED}❌ Quote bot has errors${NC}"
    php scripts/bot.php 2>&1 | tail -n 5
else
    echo -e "${GREEN}✅ Quote bot executed successfully${NC}"
fi
echo ""

echo "Testing like/save bot..."
if php scripts/like_save_bot.php 2>&1 | grep -q "Error"; then
    echo -e "${RED}❌ Like/Save bot has errors${NC}"
    php scripts/like_save_bot.php 2>&1 | tail -n 5
else
    echo -e "${GREEN}✅ Like/Save bot executed successfully${NC}"
fi
echo ""

echo "6️⃣  Recent Activity Summary..."
echo "-----------------------------------"
if [ -f "storage/logs/quote_bot.log" ]; then
    LAST_QUOTE_TIME=$(tail -n 1 storage/logs/quote_bot.log | grep -oP '\[\K[^\]]+' | head -n 1)
    echo "Last quote created: ${LAST_QUOTE_TIME:-Never}"
fi

if [ -f "storage/logs/like_save_bot.log" ]; then
    LAST_ENGAGE_TIME=$(tail -n 1 storage/logs/like_save_bot.log | grep -oP '\[\K[^\]]+' | head -n 1)
    echo "Last engagement: ${LAST_ENGAGE_TIME:-Never}"
fi
echo ""

echo "=================================================="
echo "✅ Status Check Complete!"
echo "=================================================="
echo ""
echo "📝 Quick Commands:"
echo "   View quote bot logs:     tail -f storage/logs/quote_bot.log"
echo "   View engagement logs:    tail -f storage/logs/like_save_bot.log"
echo "   Edit cron jobs:          crontab -e"
echo "   Restart cron:            sudo systemctl restart cron"
echo ""
