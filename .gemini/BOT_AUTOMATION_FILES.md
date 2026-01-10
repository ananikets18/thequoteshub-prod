# 🤖 BOT & AUTOMATION FILES IN PROJECT

## Summary
Found **3 bot/automation-related files** in the project.

---

## 📁 **1. Quote Creation Bot**
**File:** `scripts/bot.php`  
**Size:** 6,155 bytes (190 lines)  
**Purpose:** Automated quote creation bot

### **What it does:**
- ✅ Reads user credentials from Excel file (`storage/data/quotes_data.xlsx`)
- ✅ Reads quote data (author, text, category) from Excel
- ✅ Automatically logs in as different users
- ✅ Creates quotes on behalf of users
- ✅ Uses dynamic `APP_URL` from environment config
- ✅ Handles sessions with cookies

### **Key Features:**
- Uses PHPSpreadsheet library to read Excel data
- Automated login via cURL
- CSRF token handling
- Session management with cookie files
- Automated quote submission

### **Data Source:**
- Excel file: `storage/data/quotes_data.xlsx`
  - Sheet 0: User credentials (username, password)
  - Sheet 1: Quote data (author_name, quote_text, category)

### **URLs Used:**
- Login: `APP_URL/login`
- Create Quote: `APP_URL/create-quote`
- Logout: `APP_URL/logout`

---

## 📁 **2. Like & Save Bot**
**File:** `scripts/like_save_bot.php`  
**Size:** 4,217 bytes (138 lines)  
**Purpose:** Automated like and save actions bot

### **What it does:**
- ✅ Reads user credentials from Excel file (`storage/data/quotes_like_save.xlsx`)
- ✅ Automatically logs in as random users
- ✅ Browses the main page to find quotes
- ✅ Randomly likes and saves quotes
- ✅ Runs in an infinite loop
- ✅ Uses dynamic `APP_URL` from environment config

### **Key Features:**
- Continuous operation (infinite loop)
- Random user selection
- Random like/save actions on quotes
- Session management with cookies
- HTML parsing to extract quote IDs

### **Data Source:**
- Excel file: `storage/data/quotes_like_save.xlsx`
  - Sheet 0: User credentials (username, password)

### **URLs Used:**
- Login: `APP_URL/login`
- Index: `APP_URL/` (main page)
- Like: `APP_URL/quote/{id}/like`
- Save: `APP_URL/quote/{id}/save`
- Logout: `APP_URL/logout`

### **Cookie Storage:**
- `storage/temp/cookies.txt`

---

## 📁 **3. Robots.txt**
**File:** `robots.txt`  
**Size:** 32 bytes (3 lines)  
**Purpose:** SEO/Search engine crawler instructions

### **Content:**
```
User-agent: *
Disallow: /admin/
```

### **What it does:**
- Tells search engines not to crawl `/admin/` directory
- Allows all other pages to be indexed

---

## 🔧 **Technical Details**

### **Dependencies:**
Both bots require:
- ✅ PHPSpreadsheet library (`vendor/autoload.php`)
- ✅ Environment configuration (`config/env.php`)
- ✅ cURL extension enabled
- ✅ Excel files in `storage/data/` directory

### **Environment Variables Used:**
- `APP_URL` - Base URL for the application (from `.env` file)

### **Storage Requirements:**
```
storage/
├── data/
│   ├── quotes_data.xlsx          # Quote creation bot data
│   └── quotes_like_save.xlsx     # Like/save bot data
└── temp/
    └── cookies.txt               # Session cookies
```

---

## ⚠️ **Important Notes**

### **Security Considerations:**
1. **Bot files use real user credentials** - Ensure Excel files are secured
2. **Cookie files** - Stored in `storage/temp/` directory
3. **Admin directory** - Protected from search engines via robots.txt

### **Usage:**
These bots are likely used for:
- 🎯 **Testing** - Automated testing of quote creation/interaction
- 📊 **Data Population** - Populating the database with test data
- 🔄 **Simulation** - Simulating user activity

### **Recommendations:**
1. ✅ Keep Excel files with credentials **outside public directory**
2. ✅ Add `.gitignore` entries for bot data files
3. ✅ Consider using environment variables for bot credentials
4. ✅ Add rate limiting to prevent abuse
5. ✅ Monitor bot activity in logs

---

## 📊 **Summary Table**

| File | Type | Purpose | Data Source | Status |
|------|------|---------|-------------|--------|
| `scripts/bot.php` | Automation | Create quotes | `quotes_data.xlsx` | ✅ Active |
| `scripts/like_save_bot.php` | Automation | Like/save quotes | `quotes_like_save.xlsx` | ✅ Active |
| `robots.txt` | SEO | Search engine rules | N/A | ✅ Active |

---

## 🚀 **Next Steps (Optional)**

1. **Security Audit** - Review bot credential storage
2. **Rate Limiting** - Add delays between bot actions
3. **Logging** - Add detailed logging for bot activities
4. **Error Handling** - Improve error handling in bots
5. **Documentation** - Add usage instructions for bots
