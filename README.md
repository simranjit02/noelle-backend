# Noelle Backend API

MySQL + PHP backend for the Noelle cosmetics store.

## Setup

### Database

- **Database Name**: `noelle_store`
- **Table**: `products` (21 products migrated from api.json)

### Running the Server

```bash
cd /Users/simranjitsingh/clg-prj/noelle-backend
php -S localhost:5000
```

Server will run on: `http://localhost:5000`

---

## API Endpoints

### Get All Products

```
GET http://localhost:5000/api/products.php
```

### Get Products by Category

```
GET http://localhost:5000/api/products.php?category=Lips
GET http://localhost:5000/api/products.php?category=Face
GET http://localhost:5000/api/products.php?category=Eye
```

### Get Popular Products

```
GET http://localhost:5000/api/products.php?popular=1
```

### Get New Products

```
GET http://localhost:5000/api/products.php?new=1
```

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
