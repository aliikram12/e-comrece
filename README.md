# TechStore - E-Commerce Website

A complete, modern e-commerce website built with PHP, MySQL, HTML, CSS, and JavaScript. TechStore is a fully functional online marketplace for selling technology products with a clean, user-friendly interface.

## Features

### Customer Features
- **User Authentication**: Secure signup, login, and account management
- **Product Browsing**: Browse products with advanced search and filtering
- **Shopping Cart**: Add, update, and manage cart items
- **Checkout System**: Complete checkout with multiple payment methods
- **Order Management**: Track orders and view order history
- **Reviews & Ratings**: Leave and view product reviews
- **Wishlist**: Save products for later
- **Recently Viewed**: Track your browsing history

### Admin Features
- **Dashboard**: Overview of orders, revenue, and products
- **Product Management**: Add, edit, and delete products
- **Order Management**: Track and manage customer orders
- **Inventory Tracking**: Monitor stock levels
- **User Management**: Manage customer accounts

### Additional Features
- **Mobile Responsive**: Works perfectly on all devices
- **Dark Mode Support**: Optional dark mode for better viewing
- **Multiple Payment Methods**: Credit card, Cash on Delivery, Bank Transfer
- **Coupon System**: Support for discount codes
- **Email Notifications**: Order status updates
- **Security**: Secure password hashing and SQL injection prevention

## Tech Stack

- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, JavaScript (ES6+)
- **Framework**: Bootstrap 5
- **Icons**: Font Awesome 6

## Installation & Setup

### Prerequisites
- XAMPP or similar local server environment
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web browser (Chrome, Firefox, Safari, Edge)

### Step 1: Download & Setup

1. Extract the files to your `htdocs` folder:
   ```
   c:\xampp\htdocs\e-comrece\
   ```

2. Start XAMPP Apache and MySQL services

### Step 2: Create Database

1. Open phpMyAdmin: `http://localhost/phpmyadmin/`
2. Create a new database named `techstore`
3. Import the SQL schema:
   - Go to the `database` folder
   - Open `schema.sql` in phpMyAdmin
   - Execute all SQL commands

### Step 3: Configure Database

The database configuration is already set up in `includes/config.php`:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'techstore');
```

If your database credentials are different, update the config.php file accordingly.

### Step 4: Access the Website

Open your browser and navigate to:
```
http://localhost/e-comrece/
```

## Default Accounts

### Admin Account
- **Email**: admin@techstore.com
- **Password**: password123
- **Access**: http://localhost/e-comrece/admin/

### Demo Customer Account
- **Email**: demo@techstore.com
- **Password**: password123

## Project Structure

```
e-comrece/
├── admin/
│   ├── dashboard.php
│   ├── products.php
│   └── orders.php
├── api/
│   ├── add-to-cart.php
│   ├── wishlist.php
│   ├── get-cart-count.php
│   └── logout.php
├── assets/
│   ├── css/
│   │   ├── style.css
│   │   └── responsive.css
│   ├── js/
│   │   └── main.js
│   └── images/
├── classes/
│   ├── Database.php
│   ├── User.php
│   ├── Product.php
│   ├── Cart.php
│   ├── Order.php
│   ├── Category.php
│   ├── Review.php
│   ├── Coupon.php
│   └── Wishlist.php
├── database/
│   └── schema.sql
├── includes/
│   ├── config.php
│   ├── header.php
│   └── footer.php
├── pages/
│   ├── index.php (Home)
│   ├── register.php
│   ├── login.php
│   ├── shop.php
│   ├── product-detail.php
│   ├── cart.php
│   ├── checkout.php
│   ├── order-confirmation.php
│   ├── profile.php
│   ├── orders.php
│   ├── addresses.php
│   ├── wishlist.php
│   ├── about.php
│   ├── contact.php
│   └── faq.php
└── index.php (Main entry point)
```

## Database Tables

### Users
Stores customer and admin accounts with secure password hashing

### Products
Product catalog with pricing, descriptions, and inventory

### Categories
Product categories and subcategories for organization

### Orders
Customer orders with status tracking

### Cart
Shopping cart items for registered and guest users

### Wishlist
Saved products for later purchase

### Reviews
Product reviews and ratings

### And more...

See `database/schema.sql` for complete database structure.

## Usage Guide

### For Customers

1. **Sign Up**: Create a new account on the registration page
2. **Browse Products**: Use the shop page to find products
3. **Search & Filter**: Use search bar and filters to find specific products
4. **Add to Cart**: Click "Add" button on product cards
5. **Checkout**: Proceed to checkout with your cart items
6. **Track Orders**: View your orders in the "My Orders" section
7. **Leave Reviews**: Rate and review purchased products

### For Administrators

1. **Login**: Use admin credentials to log in
2. **Access Admin Panel**: Navigate to `/admin/dashboard.php`
3. **Manage Products**: Add, edit, or delete products
4. **Manage Orders**: View and update order statuses
5. **View Analytics**: Check dashboard for sales and revenue data

## Key Classes & Methods

### User Class
- `register()`: Create new user account
- `login()`: Authenticate user
- `updateProfile()`: Update user information
- `changePassword()`: Update password

### Product Class
- `getAllProducts()`: Retrieve all products
- `searchProducts()`: Search products
- `getProductById()`: Get specific product details
- `getProductsByCategory()`: Filter by category

### Cart Class
- `addToCart()`: Add product to cart
- `getCartItems()`: Retrieve cart contents
- `updateQuantity()`: Change item quantity
- `clearCart()`: Remove all items

### Order Class
- `createOrder()`: Place new order
- `getUserOrders()`: Get customer orders
- `updateOrderStatus()`: Change order status
- `getOrderItems()`: Retrieve order details

## Important Notes

- **Security**: All user inputs are sanitized to prevent SQL injection
- **Passwords**: Hashed using PHP's `password_hash()` for security
- **Session Management**: Uses PHP sessions for user authentication
- **Responsive Design**: Optimized for mobile, tablet, and desktop devices
- **Browser Support**: Works on all modern browsers

## Customization

### Change Site Name
Edit `includes/config.php`:
```php
define('SITE_NAME', 'YourStoreName');
```

### Change Colors
Edit `assets/css/style.css` CSS variables:
```css
:root {
    --primary-color: #007bff;
    --secondary-color: #6c757d;
    /* ... */
}
```

### Add New Pages
1. Create a new PHP file in the `pages/` folder
2. Include header and footer:
   ```php
   <?php require_once '../includes/header.php'; ?>
   <!-- Your content -->
   <?php require_once '../includes/footer.php'; ?>
   ```

## Troubleshooting

### Database Connection Error
- Ensure MySQL is running
- Check database credentials in `includes/config.php`
- Verify database name is `techstore`

### Pages Not Loading
- Clear browser cache
- Check file permissions
- Ensure all files are in the correct directories

### Login Issues
- Clear browser cookies/session
- Check email format
- Verify user account exists in database

## Future Enhancements

- [ ] Payment gateway integration (Stripe, PayPal)
- [ ] Email notifications
- [ ] Advanced analytics dashboard
- [ ] Vendor/seller management
- [ ] API for mobile app
- [ ] Multi-language support
- [ ] Advanced search with AI recommendations
- [ ] Live chat support
- [ ] Refund management system

## Support

For issues or questions:
1. Check the FAQ page: `http://localhost/e-comrece/pages/faq.php`
2. Contact support: support@techstore.com
3. Visit contact page: `http://localhost/e-comrece/pages/contact.php`

## License

This project is provided as-is for educational and commercial purposes.

## Credits

Built with Bootstrap 5, Font Awesome, and modern web technologies.

---

**Happy Selling! 🛍️**
