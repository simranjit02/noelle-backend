# Noelle Cosmetics - Backend API (PHP + MySQL)

A PHP REST API with MySQL database for the Noelle cosmetics e-commerce store. Provides product data endpoints for the React frontend.

---

## 📋 Prerequisites

Before starting the backend, ensure you have:

- **PHP** (v8.0 or higher) - [Download here](https://www.php.net/downloads)
- **MySQL** (v8.0 or higher) - [Download here](https://dev.mysql.com/downloads/)
- **Homebrew** (macOS) - For package management

Check versions:
```bash
php -v
mysql --version
```

---

## 🔧 Initial Setup (One-time)

### Step 1: Start MySQL Service

**macOS with Homebrew:**
```bash
brew services start mysql
# Verify it's running:
brew services list | grep mysql
```

**Windows:**
- MySQL should auto-start or use MySQL Installer

**Linux:**
```bash
sudo systemctl start mysql
sudo systemctl enable mysql  # Auto-start on boot
```

### Step 2: Verify MySQL Connection

```bash
mysql -u root
# You should see: mysql>
# Exit with: exit
```

### Step 3: Create Database and Migrate Data

Run this command once to set up the database:

```bash
cd /Users/simranjitsingh/clg-prj/noelle-backend
php db_migrate.php
```

**Expected output:**
```
Migration complete!
Migrated: 21
Errors: 0
```

This imports all 21 products from `api.json` into MySQL.

### Step 4: Verify Database Setup

```bash
mysql -u root -e "USE noelle_store; SELECT COUNT(*) as total FROM products;"
```

Should show:
```
+-------+
| total |
+-------+
|    21 |
+-------+
```

---

## 📁 Project Structure

```
noelle-backend/
├── api/
│   └── products.php          # Main API endpoint
├── config/
│   └── Database.php          # Database connection class
├── public/
│   └── index.php             # API documentation
├── index.php                 # Router
├── .htaccess                 # Apache rewrite rules
├── db_migrate.php            # Data migration script (run once)
├── router.php                # Backup router
└── README_BACKEND.md         # This file

```

---

## 🚀 Starting the Backend

### Step 1: Ensure MySQL is Running

```bash
# macOS
brew services list | grep mysql

# Should show: mysql ... started
```

If not running, start it:
```bash
brew services start mysql
```

### Step 2: Navigate to Backend Directory

```bash
cd /Users/simranjitsingh/clg-prj/noelle-backend
```

### Step 3: Start PHP Development Server

```bash
php -S localhost:5000
```

**Expected output:**
```
[Sat Mar 15 10:00:00 2026] PHP 8.2.30 Development Server started
[Sat Mar 15 10:00:00 2026] Listening on [::1]:5000
[Sat Mar 15 10:00:00 2026] Press Ctrl-C to quit
```

### Quick Start (One-liner)

```bash
cd /Users/simranjitsingh/clg-prj/noelle-backend && php -S localhost:5000
```

---

## ⏹️ Stopping the Backend

### Method 1: Keyboard Shortcut (Recommended)

In the terminal where the server is running, press:
```
Ctrl + C  (Windows/Linux)
or
Cmd + C   (macOS)
```

**Expected output:**
```
^C[Sat Mar 15 10:05:30 2026] Shutting down
```

### Method 2: Kill the Process

**Find the process:**
```bash
lsof -i :5000
```

**Kill it:**
```bash
kill -9 <PID>
# Example: kill -9 12345
```

### Method 3: Using Terminal Commands

**macOS/Linux:**
```bash
lsof -ti :5000 | xargs kill -9
```

**Windows:**
```bash
netstat -ano | findstr :5000
taskkill /PID <PID> /F
```

### Stop MySQL Service (Optional)

```bash
# macOS
brew services stop mysql

# Linux
sudo systemctl stop mysql

# Windows
Use MySQL Installer to stop the service
```

---

## 🌐 API Endpoints

### Base URL
```
http://localhost:5000
```

### 1. Get All Products

**Request:**
```bash
GET http://localhost:5000/api/products.php
```

**Response:**
```json
[
  {
    "id": "Lips_1 ",
    "img": "https://...",
    "img2": "https://...",
    "name": "Luxe Velvet Lipstick",
    "price": "$15.00",
    "des": "Product description...",
    "code": "SKU001",
    "category": "Lips",
    "popular": "false",
    "newProducts": "new"
  }
]
```

### 2. Get Products by Category

**Request:**
```bash
GET http://localhost:5000/api/products.php?category=Lips
GET http://localhost:5000/api/products.php?category=Face
GET http://localhost:5000/api/products.php?category=Eye
```

### 3. Get Popular Products

**Request:**
```bash
GET http://localhost:5000/api/products.php?popular=1
```

### 4. Get New Products

**Request:**
```bash
GET http://localhost:5000/api/products.php?new=1
```

### 5. Get Single Product

**Request:**
```bash
GET http://localhost:5000/api/products.php?id=Lips_1
```

### 6. Limit Results

**Request:**
```bash
GET http://localhost:5000/api/products.php?limit=6
```

### 7. Combine Filters

**Request:**
```bash
GET http://localhost:5000/api/products.php?category=Lips&popular=1&limit=5
```

---

## 🧪 Testing API Endpoints

### Using cURL

```bash
# Get all products
curl http://localhost:5000/api/products.php

# Get Lips products
curl http://localhost:5000/api/products.php?category=Lips

# Get 2 products
curl http://localhost:5000/api/products.php?limit=2

# Get with prettier JSON output
curl http://localhost:5000/api/products.php | python3 -m json.tool
```

### Using Browser

Simply visit in your browser:
```
http://localhost:5000/api/products.php
http://localhost:5000/api/products.php?category=Lips
http://localhost:5000/api/products.php?limit=6
```

---

## 📊 Database Schema

### Table: `products`

```sql
CREATE TABLE products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    product_id VARCHAR(50) UNIQUE NOT NULL,
    img LONGTEXT,
    img2 LONGTEXT,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2),
    description TEXT,
    sku VARCHAR(50),
    category VARCHAR(100),
    popular BOOLEAN DEFAULT FALSE,
    is_new BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### Sample Queries

```bash
# Connect to MySQL
mysql -u root

# View all products
USE noelle_store;
SELECT * FROM products LIMIT 5;

# Count products by category
SELECT category, COUNT(*) FROM products GROUP BY category;

# Get popular products
SELECT * FROM products WHERE popular = 1;

# Get new products
SELECT * FROM products WHERE is_new = 1;

# Exit
exit
```

---

## 🔒 Configuration

### Database Credentials

**Current Settings (in `config/Database.php`):**
```php
private $host = 'localhost';
private $db_name = 'noelle_store';
private $user = 'root';
private $pass = '';  // No password by default
```

### Change Credentials

Edit `config/Database.php`:
```php
private $host = 'localhost';
private $db_name = 'noelle_store';
private $user = 'your_username';  // Change this
private $pass = 'your_password';  // Change this
```

---

## 🛡️ CORS Settings

Current CORS settings (in `api/products.php`):

```php
header('Access-Control-Allow-Origin: *');  // Allow all origins
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
```

### Restrict to React Frontend Only

Change to:
```php
header('Access-Control-Allow-Origin: http://localhost:3000');
```

---

## 📝 Files Overview

### `api/products.php`
- Main API endpoint that handles all product requests
- Connects to MySQL database
- Filters data based on query parameters
- Returns JSON response

### `config/Database.php`
- Database connection class
- MySQL connection setup
- Error handling

### `public/index.php`
- API documentation page
- Lists all available endpoints
- Shows example requests

### `index.php`
- Router that handles URL rewriting
- Routes API requests to correct files
- Handles static file serving

### `db_migrate.php`
- One-time migration script
- Copies data from `api.json` to MySQL
- Can be re-run to refresh data

---

## 🔄 Data Management

### Re-migrate Data from JSON

If you want to reset data from the original `api.json`:

```bash
cd /Users/simranjitsingh/clg-prj/noelle-backend
php db_migrate.php
```

### Add a New Product

```bash
mysql -u root
USE noelle_store;

INSERT INTO products 
(product_id, img, img2, name, price, description, sku, category, popular, is_new)
VALUES 
('Test_1', 'https://...', 'https://...', 'Test Product', 20.00, 'Test desc', 'SKU999', 'Lips', 0, 1);

SELECT * FROM products WHERE product_id = 'Test_1';
```

### Update a Product

```bash
mysql -u root
USE noelle_store;

UPDATE products 
SET price = 25.00, popular = 1 
WHERE product_id = 'Lips_1';
```

### Delete a Product

```bash
mysql -u root
USE noelle_store;

DELETE FROM products WHERE product_id = 'Test_1';
```

---

## 🧪 Troubleshooting

### Issue: MySQL won't start

**macOS:**
```bash
# Check if MySQL is running
brew services list | grep mysql

# Try starting it
brew services start mysql

# Check logs
tail -f /usr/local/var/log/mysqld.log
```

**Solution:** Restart your computer or reinstall MySQL

### Issue: "Connection refused" error

```
Error: Connection Error: mysqli_sql_exception: Connection refused
```

**Solutions:**
1. Verify MySQL is running: `brew services list | grep mysql`
2. Start MySQL: `brew services start mysql`
3. Verify credentials in `config/Database.php`

### Issue: Port 5000 already in use

```
ERROR: Addr already in use
```

**Solution:** Use a different port:
```bash
php -S localhost:5001
```

Then update frontend `.env`:
```env
REACT_APP_API_URL=http://localhost:5001
```

### Issue: Database doesn't exist

**Solution:** Run migration:
```bash
php db_migrate.php
```

### Issue: API returns empty array

**Possible causes:**
1. Database isn't set up: Run `php db_migrate.php`
2. MySQL isn't running: Start with `brew services start mysql`
3. Filter is too specific: Try `/api/products.php` without filters

### Issue: CORS Error in Frontend

```
Cross-Origin Request Blocked
```

**Solution:**
1. Verify backend is running at `http://localhost:5000`
2. Check frontend `.env` has correct API URL
3. Verify CORS headers are in `api/products.php`

---

## 📊 Monitoring

### Check Backend Status

```bash
# Is the server running?
curl -s -o /dev/null -w "%{http_code}" http://localhost:5000/api/products.php
# Should return: 200

# Get product count
curl http://localhost:5000/api/products.php | grep -o '"id"' | wc -l
```

### Check MySQL Status

```bash
# Is MySQL running?
mysql -u root -e "SELECT 1;"
# Should return: 1

# View all databases
mysql -u root -e "SHOW DATABASES;"

# View tables in noelle_store
mysql -u root -e "USE noelle_store; SHOW TABLES;"
```

---

## 📈 Performance Tips

1. **Use query parameters** to limit results:
   ```bash
   # Bad: fetches all 21 products
   curl http://localhost:5000/api/products.php
   
   # Good: fetches only 6
   curl "http://localhost:5000/api/products.php?limit=6"
   ```

2. **Filter by category** to reduce data:
   ```bash
   curl "http://localhost:5000/api/products.php?category=Lips"
   ```

3. **Add indexes** for faster queries (already done in setup)

---

## 📜 Production Deployment

### For Production Use:

1. Use a real web server (Apache/Nginx)
2. Set up proper database backups
3. Use environment variables for credentials
4. Enable HTTPS
5. Restrict CORS to frontend domain
6. Add authentication if needed
7. Set up error logging
8. Monitor performance

### Production Start Command

```bash
# Use Apache/Nginx instead of PHP built-in server
# Configure in your web server config
```

---

## 🔗 Frontend Integration

The React frontend connects to this API using:

```javascript
const apiUrl = process.env.REACT_APP_API_URL || "http://localhost:5000";
fetch(`${apiUrl}/api/products.php?category=Lips`)
  .then(res => res.json())
  .then(data => setProducts(data));
```

---

## 📞 Support

For issues:
1. Check troubleshooting section
2. Verify MySQL is running
3. Check PHP version: `php -v`
4. Review error logs in terminal
5. Test endpoints with cURL

---

## 🔗 Related Documentation

- [Frontend README](../noelle-main/README_FRONTEND.md) - Frontend setup and usage
- [PHP Documentation](https://www.php.net/docs.php)
- [MySQL Documentation](https://dev.mysql.com/doc/)

---

## 📝 Quick Reference

| Command | Purpose |
|---------|---------|
| `php -S localhost:5000` | Start backend |
| `Ctrl+C` | Stop backend |
| `brew services start mysql` | Start MySQL |
| `brew services stop mysql` | Stop MySQL |
| `php db_migrate.php` | Migrate data |
| `mysql -u root` | Connect to MySQL |
| `curl http://localhost:5000/api/products.php` | Test API |

---

**Last Updated:** March 15, 2026  
**Backend Version:** PHP 8.2+  
**Database:** MySQL 9.0+  
**API Version:** 1.0
