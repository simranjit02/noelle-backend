# Noelle Backend API

PHP + MySQL backend for the Noelle cosmetics e-commerce store.

## 📋 Prerequisites

- PHP 8.2+
- MySQL 9.0+
- Composer (optional)

## 🗄️ Database Setup

### Create Database and Tables

```bash
# Connect to MySQL
mysql -u root

# Create database
CREATE DATABASE noelle_store CHARACTER SET utf8mb4;

# Create users table for authentication
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

## 🚀 Running the Server

```bash
cd noelle-backend
php -S localhost:5000 .router.php
```

Server runs at: **http://localhost:5000**

## 📡 API Endpoints

### Products

- `GET /api/products.php` - Get all products
- `GET /api/products.php?category=Lips` - Get products by category (Lips, Face, Eye)
- `GET /api/products.php?limit=6` - Get limited products
- `GET /api/products.php?new=1` - Get new products

### Authentication

- `POST /auth/register.php` - Register user
- `POST /auth/login.php` - Login user

**Register Request:**

```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123"
}
```

**Login Request:**

```json
{
  "email": "john@example.com",
  "password": "password123"
}
```

## 📁 Project Structure

```
noelle-backend/
├── .router.php              # Main router with CORS headers
├── index.php                # Entry point
├── db_migrate.php           # Database initialization
├── config/
│   └── Database.php         # Database connection
├── api/
│   └── products.php         # Products API
├── auth/
│   ├── register.php         # Registration endpoint
│   └── login.php            # Login endpoint
└── README.md                # This file
```

## 🔐 Security Features

✅ SQL injection prevention (prepared statements)
✅ Password hashing with bcrypt
✅ CORS headers enabled
✅ Input validation
✅ Proper HTTP status codes

## 🧪 Testing API

```bash
# Get all products
curl http://localhost:5000/api/products.php

# Register user
curl -X POST http://localhost:5000/auth/register.php \
  -H "Content-Type: application/json" \
  -d '{"name":"Test","email":"test@test.com","password":"123456"}'

# Login
curl -X POST http://localhost:5000/auth/login.php \
  -H "Content-Type: application/json" \
  -d '{"email":"test@test.com","password":"123456"}'
```

## 📝 Configuration

Edit `config/Database.php` to change database credentials:

```php
private $host = 'localhost';
private $db_name = 'noelle_store';
private $user = 'root';
private $pass = '';  // Add password if needed
```

## 🐛 Troubleshooting

**Port already in use:**

```bash
lsof -ti :5000 | xargs kill -9
```

**Database connection error:**

- Check MySQL is running
- Verify database name in `config/Database.php`
- Ensure credentials are correct

**CORS errors:**

- Verify `.router.php` is being used
- Check frontend API URL matches backend URL

## 🔗 Related Documentation

- [Frontend README](../noelle-main/README.md) - Frontend setup
- [React Documentation](https://react.dev)
- [PHP Documentation](https://www.php.net/docs.php)
- [MySQL Documentation](https://dev.mysql.com/doc/)

---

**Last Updated:** March 15, 2026
**Backend Version:** PHP 8.2+
**Database:** MySQL 9.0+

### Get Single Product

```
GET http://localhost:5000/api/products.php?id=Lips_1
```

### Limit Results

```
GET http://localhost:5000/api/products.php?limit=6
```

### Combine Filters

```
GET http://localhost:5000/api/products.php?category=Lips&popular=1&limit=5
```

---

## Response Format

```json
[
  {
    "id": "Lips_1",
    "img": "https://...",
    "img2": "https://...",
    "name": "Luxe Velvet Lipstick",
    "price": "$15.00",
    "des": "Product description...",
    "code": "SKU001",
    "category": "Lips",
    "popular": "true",
    "newProducts": "new"
  }
]
```

---

## Database Schema

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

---

## File Structure

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
├── db_migrate.php            # Migration script (run once)
└── router.php                # Backup router
```

---

## Stopping the Server

Press `Ctrl+C` in the terminal where the server is running.

---

## MySQL Management

Access MySQL:

```bash
mysql -u root
USE noelle_store;
SELECT * FROM products LIMIT 5;
```

---

## CORS

The API allows cross-origin requests from any origin (for development).
For production, restrict in `api/products.php`:

```php
header('Access-Control-Allow-Origin: http://localhost:3000');
```
