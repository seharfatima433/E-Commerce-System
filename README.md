# 🛍️ Exclusive — Full-Stack E-Commerce Platform

<div align="center">

![Laravel](https://img.shields.io/badge/Laravel-9.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.x-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/TailwindCSS-3.x-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white)
![Deployed](https://img.shields.io/badge/Live-devlura.site-2D6A4F?style=for-the-badge)

**A complete, production-ready E-Commerce web application built with Laravel 9.**  
Submitted for **Devixo Solutions Internship — Task 04**

🌐 **Live Demo:** [devlura.site/E-Commerce/public](https://devlura.site/E-Commerce/public)  
⚙️ **Admin Panel:** [devlura.site/E-Commerce/public/admin](https://devlura.site/E-Commerce/public/admin)

</div>

---

## 📋 Table of Contents

- [About the Project](#about)
- [Features](#features)
- [Tech Stack](#tech-stack)
- [Database Schema](#database-schema)
- [Installation & Setup](#installation--setup)
- [Environment Variables](#environment-variables)
- [Admin Credentials](#admin-credentials)
- [Project Structure](#project-structure)
- [Screenshots](#screenshots)

---

## About

**Exclusive** is a luxury e-commerce platform that allows customers to browse and purchase products online. It features a complete admin panel for business management, Safepay payment gateway integration, a coupon discount system, product reviews, and a wishlist — all deployed live on a production server.

---

## Features

### 🔐 Authentication
- User Registration & Login / Logout
- Password hashing (bcrypt) & session management
- Remember Me functionality
- Role-based access control (admin / customer)
- Guest cart → User cart merge on login

### 🛍️ Storefront
- Luxury Emerald Green themed UI
- Homepage with hero, featured products & categories
- Product listing with search, filters (category, price) & sort
- Product detail with image gallery, reviews & ratings
- Wishlist system
- Related products section

### 🛒 Cart & Checkout
- Guest & authenticated shopping cart
- Add / Update / Remove cart items
- Coupon code system (percentage & fixed discounts, usage limits, expiry)
- Free shipping on orders above Rs. 10,000
- Cash on Delivery (COD) & online card payment
- **Safepay** payment gateway integration (Pakistan)
- Database transaction-protected order creation

### 👤 Customer Account
- Account dashboard with stats (orders, spending, reviews)
- Full order history with status tracking
- Profile & password management
- Wishlist management
- Product reviews (submit & delete)

### ⚙️ Admin Panel
- Dashboard with live statistics & 6-month revenue chart
- **Products** — Full CRUD with multiple image upload, stock management, featured/active toggles
- **Categories** — Create, edit, delete
- **Orders** — View all orders, update status (Pending → Processing → Shipped → Delivered)
- **Users** — View all users, promote/demote admin role, delete
- **Coupons** — Create percentage/fixed coupons with limits and expiry

### 🔒 Security
- CSRF token protection on all forms
- Admin middleware route guard
- Server-side input validation
- Eloquent ORM (prepared statements — no raw SQL injection risk)
- Webhook signature handling for Safepay

---

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | Laravel 9.52 (PHP 8.2) |
| Database | MySQL 8.x |
| Frontend | Blade Templates + Tailwind CSS 3.x |
| Icons | Font Awesome 6 |
| Fonts | Google Fonts — Outfit |
| Payment | Safepay PHP SDK v0.0.21 |
| Hosting | cPanel Shared Hosting |

---

## Database Schema

```
users           — id, name, email, password, role, phone, address
categories      — id, name, slug, description, image, is_active
products        — id, name, slug, price, sale_price, stock, category_id,
                  brand, images(JSON), attributes(JSON), is_featured, is_active
coupons         — id, code, type, value, min_order, max_uses, uses, expires_at
carts           — id, user_id, session_id, coupon_code, discount
cart_items      — id, cart_id, product_id, quantity
orders          — id, order_number, user_id, status, payment_status,
                  payment_method, subtotal, shipping, discount, total, shipping_address...
order_items     — id, order_id, product_id, product_name(snapshot), quantity, price
reviews         — id, product_id, user_id, rating, title, body, is_approved
wishlists       — id, user_id, product_id
```

---

## Installation & Setup

### Prerequisites
- PHP >= 8.0
- Composer
- MySQL
- Node.js (optional, for Vite)

### Steps

```bash
# 1. Clone the repository
git clone https://github.com/YOUR_USERNAME/E-Commerce.git
cd E-Commerce

# 2. Install PHP dependencies
composer install

# 3. Copy environment file
cp .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Configure your database in .env (see below)

# 6. Run migrations
php artisan migrate

# 7. (Optional) Seed the database
php artisan db:seed

# 8. Create storage symlink
php artisan storage:link

# 9. Start development server
php artisan serve
```

Visit `http://localhost:8000` in your browser.

---

## Environment Variables

Edit `.env` with your local settings:

```env
APP_NAME=Exclusive
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ecommerce_db
DB_USERNAME=root
DB_PASSWORD=

# Safepay Payment Gateway (Sandbox)
SAFEPAY_KEY_SECRET=your_safepay_secret_key
SAFEPAY_KEY_PUBLIC=your_safepay_public_key
SAFEPAY_API_BASE=https://sandbox.api.getsafepay.com
```

---

## Admin Credentials

To create an admin user, run this SQL query in your MySQL database:

```sql
INSERT INTO users (name, email, password, role, created_at, updated_at)
VALUES (
  'Admin',
  'admin@luxeshop.com',
  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',  -- password: password
  'admin',
  NOW(),
  NOW()
);
```

Or register a user normally and update their role:
```sql
UPDATE users SET role = 'admin' WHERE email = 'your@email.com';
```

---

## Project Structure

```
E-Commerce/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/              # Admin panel controllers
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── ProductController.php
│   │   │   │   ├── CategoryController.php
│   │   │   │   ├── OrderController.php
│   │   │   │   ├── UserController.php
│   │   │   │   └── CouponController.php
│   │   │   ├── AuthController.php  # Registration, Login, Logout
│   │   │   ├── CartController.php  # Shopping cart
│   │   │   ├── CheckoutController.php  # Order + Safepay
│   │   │   ├── HomeController.php
│   │   │   ├── ProductController.php
│   │   │   ├── OrderController.php
│   │   │   ├── AccountController.php
│   │   │   ├── ReviewController.php
│   │   │   └── WishlistController.php
│   │   └── Middleware/
│   └── Models/
│       ├── User.php
│       ├── Product.php
│       ├── Category.php
│       ├── Order.php / OrderItem.php
│       ├── Cart.php / CartItem.php
│       ├── Coupon.php
│       ├── Review.php
│       └── Wishlist.php
├── database/
│   └── migrations/     # 10 migration files
├── resources/
│   └── views/
│       ├── layouts/
│       │   ├── app.blade.php       # Storefront layout
│       │   └── admin.blade.php     # Admin layout (green theme)
│       ├── admin/                  # Admin panel views
│       │   ├── dashboard.blade.php
│       │   ├── products/
│       │   ├── categories/
│       │   ├── orders/
│       │   ├── users/
│       │   └── coupons/
│       ├── auth/                   # Login & Register
│       ├── products/               # Storefront product pages
│       ├── cart/
│       ├── checkout/
│       └── account/                # Customer account pages
└── routes/
    └── web.php                     # All route definitions
```

---

## Screenshots

> Add your actual screenshots in a `/screenshots` folder and link them here.

| Page | Screenshot |
|---|---|
| Homepage | `screenshots/homepage.png` |
| Products | `screenshots/products.png` |
| Cart | `screenshots/cart.png` |
| Checkout | `screenshots/checkout.png` |
| Admin Dashboard | `screenshots/admin-dashboard.png` |
| Admin Products | `screenshots/admin-products.png` |

---

## License

This project was built as part of the Devixo Solutions Internship Program (Task 04).

---

<div align="center">
Made with ❤️ using Laravel & Tailwind CSS
</div>
#   E - C o m m e r c e - S y s t e m  
 