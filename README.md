# The Quotes Hub

A social quote sharing platform built with PHP and MySQL.

## 🚀 Quick Start

### Requirements
- PHP 7.4+
- MySQL 5.7+
- Apache with mod_rewrite
- Composer

### Installation

1. Clone the repository
2. Install dependencies:
   ```bash
   composer install
   ```

3. Configure database:
   - Copy `config/database.php.example` to `config/database.php`
   - Update with your database credentials

4. Set up storage permissions:
   ```bash
   chmod -R 775 storage/
   chmod -R 775 public/uploads/
   ```

5. Configure your web server to point to the project root

## 📁 Project Structure

```
public_html/
├── app/
│   ├── api/              # API endpoints (JSON responses)
│   ├── controllers/      # Business logic
│   ├── models/           # Data access layer
│   └── views/            # Presentation layer
├── config/               # Configuration files
├── public/
│   ├── assets/           # Static assets (CSS, JS)
│   └── uploads/          # User-generated content
├── scripts/              # Automation scripts (bots)
├── storage/
│   ├── data/             # Bot data files
│   ├── logs/             # Application logs
│   └── temp/             # Temporary files
└── vendor/               # Composer dependencies
```

## 🤖 Running Bots

### Quote Posting Bot
```bash
php scripts/bot.php
```

### Like/Save Bot
```bash
php scripts/like_save_bot.php
```

## 🔐 Security

- Never commit `config/database.php` with real credentials
- Keep `storage/` directory outside web root in production
- Regularly review `storage/logs/error.log`

## 📝 License

Proprietary - All rights reserved

## 🔗 Live Site

https://thequoteshub.info
