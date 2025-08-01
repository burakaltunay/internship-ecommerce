# TechShop E-Commerce Platform

A modern e-commerce platform built with Laravel, featuring user authentication, product management, shopping cart functionality, order processing, and email notifications.

## 🚀 Features

- **User Authentication**: Secure login/registration system with email verification
- **Product Management**: CRUD operations for products with categories
- **Shopping Cart**: Add/remove products, quantity management
- **Order Processing**: Complete order workflow with status tracking
- **Email Notifications**: Beautiful HTML email templates for various events
- **Responsive Design**: Modern UI with Tailwind CSS
- **Discount System**: Automatic discount codes for loyal customers

## 📋 Prerequisites

Before running this application, make sure you have the following installed:

- **PHP** >= 8.1
- **Composer** >= 2.0
- **Node.js** >= 16.0
- **npm** >= 8.0
- **MySQL** >= 8.0 or **PostgreSQL** >= 13.0
- **Git**

## 🛠️ Installation

### 1. Clone the Repository

```bash
git clone https://github.com/yourusername/techshop-ecommerce.git
cd techshop-ecommerce
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Node.js Dependencies

```bash
npm install
```

### 4. Environment Configuration

```bash
cp .env.example .env
```

Edit the `.env` file with your database and mail configuration:

```env
APP_NAME=TechShop
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=techshop
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="your_mail"
MAIL_FROM_NAME="TechShop"
```

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Database Setup

```bash
# Create database
mysql -u root -p -e "CREATE DATABASE techshop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Run migrations
php artisan migrate

# Seed the database (optional)
php artisan db:seed
```

### 7. Build Frontend Assets

```bash
npm run build
```

### 8. Storage Setup

```bash
php artisan storage:link
```

### 9. Queue Setup (Optional - for email processing)

```bash
# Start queue worker
php artisan queue:work

# Or run in background
php artisan queue:work --daemon
```

## 🚀 Running the Application

### Development Server

```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

### Production Server

For production deployment, configure your web server (Apache/Nginx) to point to the `public` directory.

## 📧 Email Configuration

The application sends emails for:
- Welcome password (new user registration)
- Order confirmation
- Order delivery notification
- Order rejection notification
- Discount codes

### Mail Configuration

Update your `.env` file with your SMTP settings:

```env
MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@techshop.com"
MAIL_FROM_NAME="TechShop"
```

### Testing Emails

```bash
# Test email configuration
php artisan tinker
Mail::raw('Test email', function($message) { $message->to('test@example.com')->subject('Test'); });
```

## 🗄️ Database Structure

### Main Tables

- **users**: User accounts and authentication
- **products**: Product catalog with categories
- **carts**: Shopping cart items
- **baskets**: Orders and order status
- **discount_codes**: Promotional codes
- **categories**: Product categories

### Key Relationships

- Users can have multiple orders (baskets)
- Products belong to categories
- Carts are temporary shopping lists
- Baskets represent confirmed orders

## 🔧 Available Commands

### Artisan Commands

```bash
# Clear application cache
php artisan cache:clear

# Clear config cache
php artisan config:clear

# Clear route cache
php artisan route:clear

# Clear view cache
php artisan view:clear

# Optimize application
php artisan optimize

# List all routes
php artisan route:list

# Create a new controller
php artisan make:controller ProductController

# Create a new model
php artisan make:model Product -m

# Create a new migration
php artisan make:migration create_products_table

# Create a new mail class
php artisan make:mail WelcomePasswordMail
```

### Queue Commands

```bash
# Start queue worker
php artisan queue:work

# Monitor queue
php artisan queue:monitor

# Clear failed jobs
php artisan queue:flush

# Retry failed jobs
php artisan queue:retry all
```

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/AuthTest.php

# Run tests with coverage
php artisan test --coverage
```

## 📁 Project Structure

```
techshop-ecommerce/
├── app/
│   ├── Http/Controllers/     # Application controllers
│   ├── Models/              # Eloquent models
│   ├── Mail/                # Mail classes
│   └── Services/            # Business logic services
├── config/                  # Configuration files
├── database/
│   ├── migrations/          # Database migrations
│   ├── seeders/            # Database seeders
│   └── factories/          # Model factories
├── public/                  # Public assets
├── resources/
│   ├── views/              # Blade templates
│   ├── css/                # Stylesheets
│   └── js/                 # JavaScript files
├── routes/                  # Route definitions
├── storage/                 # File storage
└── tests/                   # Test files
```

## 🎨 Frontend Features

- **Tailwind CSS**: Utility-first CSS framework
- **Responsive Design**: Mobile-first approach
- **Modern UI**: Clean and professional design
- **Interactive Elements**: JavaScript-powered interactions
- **Loading States**: User feedback during operations

## 📱 API Endpoints

### Authentication
- `POST /ajax/login` - User login
- `POST /ajax/register` - User registration
- `POST /logout` - User logout

### Products
- `GET /dashboard` - Product listing
- `GET /api/products` - API product list

### Cart
- `POST /cart/add` - Add to cart
- `POST /cart/remove` - Remove from cart
- `GET /cart` - View cart

### Orders
- `POST /checkout` - Complete order
- `GET /orders` - View orders

## 🚀 Deployment

### Production Checklist

1. **Environment Setup**
   ```bash
   APP_ENV=production
   APP_DEBUG=false
   ```

2. **Optimize Application**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   php artisan optimize
   ```

3. **Database Migration**
   ```bash
   php artisan migrate --force
   ```

4. **Queue Worker**
   ```bash
   php artisan queue:work --daemon
   ```

5. **Web Server Configuration**
   - Point document root to `public/` directory
   - Enable HTTPS
   - Configure proper file permissions


---

**TechShop E-Commerce Platform** - Built with ❤️ using Laravel

