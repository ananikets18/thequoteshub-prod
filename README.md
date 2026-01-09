# The Quotes Hub (Beta v0.9.0)
> *Where Words Matter.*

Welcome to **The Quotes Hub**, a platform designed for sharing, discovering, and connecting through the power of words. We are currently in **Open Beta**.

## 🚧 Beta Status
We are actively refining the experience. As we are in **Beta (v0.9.0)**, you may encounter occasional bugs or incomplete features. Your feedback is invaluable in helping us shape the future of this platform.

**Current Focus:**
- Enhanced Security & Performance
- Improved Mobile Experience
- Optimized Content Discovery

## ✨ Highlights
- **Curated Wisdom:** Discover a vast collection of quotes across various categories.
- **Community Driven:** Share your own insights and connect with like-minded individuals.
- **Personalized Experience:** Follow authors, save your favorites, and get recommendations.

## 🚀 Getting Started
Explore the platform and start sharing your thoughts today at [thequoteshub.info](https://thequoteshub.info)

---

## 🔧 Deployment

### **Production Deployment (DigitalOcean App Platform)**

This project uses **automatic deployment** from GitHub to DigitalOcean:

```
┌─────────────┐    git push    ┌──────────┐   auto-detect   ┌────────────────┐    
│   Local     │───────────────>│  GitHub  │────────────────>│  DigitalOcean  │
│   (XAMPP)   │                │   Repo   │                 │  App Platform  │
└─────────────┘                └──────────┘                 └────────────────┘
                                                                     │
                                                                     │ deploy
                                                                     ▼
                                                           ┌──────────────────┐
                                                           │   🌐 LIVE SITE   │
                                                           │  thequoteshub    │
                                                           └──────────────────┘
```

#### **Quick Deploy:**
1. Update `.do/app.yaml` with your GitHub repo
2. Push code to GitHub (`main` branch)
3. Create app on DigitalOcean (connects to GitHub)
4. Automatic deployments on every push!

📚 **Detailed guides:**
- **[QUICKSTART.md](./QUICKSTART.md)** - 5-minute setup
- **[DEPLOYMENT.md](./DEPLOYMENT.md)** - Complete step-by-step guide

---

## 💻 Local Development

### Prerequisites
- PHP 8.0+
- MySQL 8.0+
- Apache/Nginx
- Composer

### Setup
```bash
# 1. Clone repository
git clone https://github.com/YOUR_USERNAME/quoteshub.git
cd quoteshub

# 2. Install dependencies
composer install

# 3. Configure environment
cp config/.env.example config/.env
# Edit config/.env with your local database credentials

# 4. Import database
mysql -u root -p < database.sql

# 5. Start local server (XAMPP/MAMP or PHP built-in)
php -S localhost:8000
```

### Development Workflow
```bash
# Make changes locally
# Test thoroughly

# Commit and push to GitHub
git add .
git commit -m "Description of changes"
git push origin main

# ✅ DigitalOcean automatically deploys in 2-5 minutes!
```

---

> *Note: This repository contains the source code for the platform. For security reasons, detailed architectural documentation is restricted.*


