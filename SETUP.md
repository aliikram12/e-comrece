# TechStore Setup Guide

## Quick Start - Complete Installation in 5 Steps

### Step 1: Start Your Server
1. Open XAMPP Control Panel
2. Click "Start" for Apache
3. Click "Start" for MySQL

### Step 2: Create Database
1. Open phpMyAdmin: `http://localhost/phpmyadmin/`
2. Click "New" in the left sidebar
3. Database name: `techstore`
4. Click "Create"
5. Select the `techstore` database
6. Click "Import" tab
7. Choose file: `c:\xampp\htdocs\e-comrece\database\schema.sql`
8. Click "Go" to import

### Step 3: Verify Configuration
- Open `c:\xampp\htdocs\e-comrece\includes\config.php`
- Verify these settings:
  ```
  DB_HOST = localhost
  DB_USER = root
  DB_PASS = (empty)
  DB_NAME = techstore
  ```

### Step 4: Access the Website
- Home Page: `http://localhost/e-comrece/`
- Admin Panel: `http://localhost/e-comrece/admin/`

### Step 5: Test Login
**Customer Account:**
- Email: demo@techstore.com
- Password: password123

**Admin Account:**
- Email: admin@techstore.com
- Password: password123

## Features Included

✅ Complete product catalog
✅ User authentication system
✅ Shopping cart and checkout
✅ Order management
✅ Product reviews and ratings
✅ Wishlist functionality
✅ Admin dashboard
✅ Product management
✅ Order tracking
✅ Responsive design
✅ Multiple payment methods
✅ Search and filters
✅ Customer support pages

## Important Files to Know

| File | Purpose |
|------|---------|
| `index.php` | Home page |
| `includes/config.php` | Database configuration |
| `includes/header.php` | Navigation and header |
| `includes/footer.php` | Footer template |
| `pages/shop.php` | Product listing |
| `pages/cart.php` | Shopping cart |
| `pages/checkout.php` | Order placement |
| `admin/dashboard.php` | Admin panel |
| `database/schema.sql` | Database structure |
| `assets/css/style.css` | Main styling |
| `assets/js/main.js` | JavaScript functionality |

## Common Issues & Solutions

**Issue: "Connection refused" error**
- Solution: Make sure MySQL is running in XAMPP

**Issue: Database not found**
- Solution: Import schema.sql into phpMyAdmin

**Issue: Products not loading**
- Solution: Check if products table has data in phpMyAdmin

**Issue: Logout not working**
- Solution: Clear browser cookies/cache

## Adding Products

1. Login as admin: `http://localhost/e-comrece/admin/`
2. Click "Products"
3. Click "Add New Product"
4. Fill in product details
5. Click "Add Product"

## Managing Orders

1. Login as admin
2. Click "Orders"
3. Click "View" on an order
4. Update order status as needed

## Need Help?

- Check FAQ: `http://localhost/e-comrece/pages/faq.php`
- Contact page: `http://localhost/e-comrece/pages/contact.php`
- Read README.md for detailed documentation

---

**You're all set! Start selling! 🚀**
