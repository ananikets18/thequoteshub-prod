#!/bin/bash
# 🚀 Quick Deploy Script for The Quotes Hub
# Run this on your production server after pushing to GitHub

echo "🚀 Starting deployment..."

# Navigate to project directory
cd /var/www/quoteshub || exit 1

echo "📥 Pulling latest changes from GitHub..."
git pull origin main

echo "🔒 Setting correct permissions..."
chmod -R 755 .
chown -R www-data:www-data public/uploads
chown -R www-data:www-data storage

echo "🗑️ Clearing temporary files..."
rm -rf storage/temp/*

echo "🤖 Setting up automated bots..."
if [ -f "scripts/setup_bots.sh" ]; then
    chmod +x scripts/setup_bots.sh
    bash scripts/setup_bots.sh
else
    echo "⚠️  Bot setup script not found, skipping..."
fi

echo "🔄 Reloading Nginx..."
systemctl reload nginx

echo "✅ Deployment complete!"
echo "🌐 Visit: https://www.thequoteshub.info"
echo "📊 Check logs: tail -f storage/logs/quote_bot.log"

