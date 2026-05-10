# 🎫 Tikiti Kenya - Event Ticketing Platform

[![Laravel Version](https://img.shields.io/badge/Laravel-13.x-red.svg)](https://laravel.com)
[![Filament Version](https://img.shields.io/badge/Filament-5.x-purple.svg)](https://filamentphp.com)
[![SQLite](https://img.shields.io/badge/SQLite-3.x-blue.svg)](https://sqlite.org)
[![Paystack](https://img.shields.io/badge/Paystack-API-green.svg)](https://paystack.com)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

A modern, full-featured event ticketing platform built for the Kenyan market, supporting Sportpesa Premier League football matches and international concerts with secure M-Pesa and card payments via Paystack.

## 📋 Table of Contents

- [Features](#-features)
- [Technology Stack](#-technology-stack)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [Database Setup](#-database-setup)
- [Admin Panel](#-admin-panel)
- [Payment Integration](#-payment-integration)
- [Project Structure](#-project-structure)
- [API Endpoints](#-api-endpoints)
- [Troubleshooting](#-troubleshooting)
- [Deployment](#-deployment)

## 🌟 Features

### 🎯 Core Features
- **Event Management** - Create, edit, and manage events with multiple ticket tiers
- **Ticket Sales** - Sell tickets for football matches, concerts, and special events
- **Secure Payments** - Integrated Paystack payment gateway (M-Pesa & Card payments)
- **User Management** - Role-based access (Admin, Event Manager, Customer)
- **Guest Checkout** - Purchase tickets without creating an account
- **Digital Tickets** - Instant ticket generation after successful payment
- **Image Upload** - Event images with automatic storage handling

### 🎨 Admin Panel (Filament)
- **Dashboard** - Real-time statistics and analytics
- **Event Management** - Create events with 6-step wizard form
- **Venue Management** - Manage venues, capacities, and amenities
- **Order Management** - Track orders and payment status
- **Ticket Management** - View and manage sold tickets
- **User Management** - Manage user accounts, roles, and permissions

### 🏟️ Frontend Features
- **Responsive Design** - Mobile-friendly interface with Tailwind CSS
- **Event Discovery** - Browse events by category (Football, Concerts)
- **Featured Events** - Highlight important events
- **Venue Directory** - View popular venues with event counts
- **Secure Checkout** - 3-step checkout process
- **Email Notifications** - Payment confirmation and tickets via email

## 🚀 Technology Stack

| Technology | Version | Purpose |
|------------|---------|---------|
| Laravel | 13.x | Backend Framework |
| Filament | 5.x | Admin Panel |
| SQLite | 3.x | Database (Development) |
| Paystack | API | Payment Gateway |
| Tailwind CSS | 3.x | Frontend Styling |
| Livewire | 3.x | Dynamic UI Components |
| PHP | 8.5+ | Server-side Language |

## 📋 Prerequisites

- PHP >= 8.5 with extensions: `sqlite3`, `curl`, `json`, `openssl`, `mbstring`, `fileinfo`
- Composer
- Node.js & NPM (optional, for asset compilation)
- SQLite3 (for development)
- Paystack account (for payment integration)

## 🛠️ Installation

### Step 1: Clone the Repository

```bash
git clone https://github.com/wamwagii/tikiti.git
cd tikiti

Step 2: Install Dependencies
bash
composer install
Step 3: Environment Configuration
Copy the example environment file:

bash
cp .env.example .env
Generate application key:

bash
php artisan key:generate
Step 4: Configure Database (SQLite)
bash
# Create SQLite database file
touch database/database.sqlite
Update your .env file:

env
DB_CONNECTION=sqlite
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=laravel
# DB_USERNAME=root
# DB_PASSWORD=
Step 5: Run Migrations
bash
php artisan migrate
Step 6: Create Storage Link
bash
php artisan storage:link
This creates a symbolic link from public/storage to storage/app/public for image uploads.

Step 7: Seed Database (Optional)
bash
php artisan db:seed --class=AdminUserSeeder
Step 8: Create Admin User
bash
php artisan make:filament-user
Follow the prompts to create an admin user with email and password.

Step 9: Start Development Server
bash
php artisan serve
Visit http://localhost:8000 to view the application.

⚙️ Configuration
Paystack Configuration
Create a Paystack account at https://dashboard.paystack.com

Navigate to Settings → API Keys & Webhooks

Copy your test keys (starts with sk_test_ and pk_test_)

Add to your .env file:

env
PAYSTACK_SECRET_KEY=sk_test_xxxxxxxxxxxxx
PAYSTACK_PUBLIC_KEY=pk_test_xxxxxxxxxxxxx
PAYSTACK_CALLBACK_URL=http://localhost:8000/payment/callback
PAYSTACK_WEBHOOK_URL=http://localhost:8000/payment/webhook
Webhook Configuration (Production)
In Paystack dashboard, set your webhook URL to:

text
https://yourdomain.com/payment/webhook
Image Upload Configuration
Images are stored in storage/app/public/events/ and accessed via the custom image route /images/{path}.

🗄️ Database Setup
SQLite Database Location
text
database/database.sqlite
Key Tables
Table	Description
users	User accounts (admin, event managers, customers)
events	Event details with ticket tiers stored as JSON
venues	Venue information (name, location, capacity)
orders	Ticket orders with payment status
tickets	Individual tickets generated after payment
Database Migrations
All migrations are located in database/migrations/. Run migrations with:

bash
php artisan migrate
To reset and re-run all migrations:

bash
php artisan migrate:fresh
🔧 Admin Panel (Filament)
Access Admin Panel
text
http://localhost:8000/admin
Admin Features
Events Resource
Create Event: 6-step wizard form

Basic Information (title, type, venue, description, image)

Date & Time (start/end dates)

Ticket Tiers (multiple categories with prices)

Inventory Management (total/available tickets)

Status & Visibility (draft/published, featured)

Edit Event: Full editing capabilities

View Events: Table with filters and search

Delete Events: With confirmation

Venues Resource
Manage venue information

Track capacity and amenities

View associated events

Orders Resource
View all ticket orders

Track payment status

View order details

Tickets Resource
View generated tickets

Track ticket status (sold/used/refunded)

Ticket validation

Users Resource
Manage user accounts

Assign roles (Admin, Event Manager, Customer)

Enable/disable accounts

Force password resets

💳 Payment Integration
Paystack Flow
Initialize Payment: User submits ticket purchase form

Create Order: Order stored with pending status

Redirect to Paystack: User redirected to Paystack checkout

Process Payment: User completes payment via M-Pesa or Card

Callback: Paystack redirects back to success/failed page

Verification: System verifies payment with Paystack API

Generate Tickets: Tickets created and marked as sold

Email Confirmation: User receives ticket confirmation

Test Payment Details
Use these test credentials in development:

Field	Value
Card Number	4111111111111111
Expiry Date	Any future date
CVV	Any 3 digits
PIN	Any 4 digits
OTP	12345
Test M-Pesa
Paystack provides test phone numbers on their staging environment.

📁 Project Structure
text
tikiti-kenya/
├── app/
│   ├── Filament/                    # Filament admin panel
│   │   └── Resources/               # Admin resources
│   │       ├── Events/              # Event management
│   │       │   ├── Pages/           # Index, Create, Edit, View
│   │       │   ├── Schemas/         # Forms and infolists
│   │       │   └── Tables/          # Table configuration
│   │       ├── Venues/              # Venue management
│   │       ├── Orders/              # Order management
│   │       ├── Tickets/             # Ticket management
│   │       └── Users/               # User management
│   ├── Http/
│   │   └── Controllers/
│   │       ├── HomeController.php   # Homepage with events
│   │       ├── EventController.php  # Event details page
│   │       ├── TicketPurchaseController.php  # Ticket purchase flow
│   │       ├── PaystackController.php        # Payment handling
│   │       └── ImageController.php  # Image serving
│   ├── Models/                      # Eloquent models
│   │   ├── User.php                 # User model with roles
│   │   ├── Event.php                # Event model with ticket tiers
│   │   ├── Venue.php                # Venue model
│   │   ├── Order.php                # Order model
│   │   └── Ticket.php               # Ticket model
│   └── Services/
│       └── PaystackService.php      # Paystack API integration
├── database/
│   ├── migrations/                  # Database migrations
│   ├── seeders/                     # Database seeders
│   └── database.sqlite              # SQLite database file
├── resources/
│   └── views/
│       ├── home.blade.php           # Homepage
│       ├── event/
│       │   └── show.blade.php       # Event details
│       ├── tickets/
│       │   └── purchase.blade.php   # Ticket purchase form
│       └── payment/
│           ├── success.blade.php    # Payment success page
│           └── failed.blade.php     # Payment failed page
├── routes/
│   └── web.php                      # Web routes
├── storage/
│   └── app/public/events/           # Uploaded event images
└── public/
    └── storage/                     # Symlink to storage
🔌 API Endpoints
Web Routes
Method	URL	Name	Description
GET	/	home	Homepage with events
GET	/events/{event}	events.show	Event details page
GET	/tickets/{event}/buy	tickets.buy	Ticket purchase form
POST	/tickets/{event}/process	tickets.process	Process ticket purchase
GET	/payment/callback	payment.callback	Paystack callback
GET	/payment/success/{order}	payment.success	Success page
GET	/payment/failed	payment.failed	Failed page
POST	/payment/webhook	payment.webhook	Paystack webhook
GET	/images/{path}	-	Serve event images
Admin Routes (Filament)
All admin routes are prefixed with /admin and handled by Filament.

🐛 Troubleshooting
Images Not Showing
bash
# Recreate storage symlink
rm public/storage
php artisan storage:link

# Check if images exist
ls storage/app/public/events/

# Test image URL
php artisan tinker
>>> asset('storage/events/filename.jpg')
403 Forbidden on Images
bash
# Windows (Run as Administrator)
icacls storage /grant "Everyone:(OI)(CI)F" /T
icacls public\storage /grant "Everyone:(OI)(CI)F" /T

# Linux/Mac
chmod -R 755 storage
chmod -R 755 public/storage
Paystack Integration Issues
bash
# Clear configuration cache
php artisan config:clear

# Check Paystack keys
php artisan tinker
>>> config('services.paystack.secret_key')
>>> config('services.paystack.public_key')

# Check logs
tail -f storage/logs/laravel.log
Database Migration Issues
bash
# Reset database (⚠️ deletes all data)
php artisan migrate:fresh --seed

# Check migration status
php artisan migrate:status

# Run specific migration
php artisan migrate --path=database/migrations/2026_05_09_000000_create_events_table.php
SQLite Issues
bash
# Verify SQLite is installed
php -m | grep sqlite

# Check database file permissions
ls -la database/database.sqlite

# Repair SQLite database
sqlite3 database/database.sqlite "PRAGMA integrity_check;"
Filament Admin Panel Issues
bash
# Publish Filament assets
php artisan filament:assets

# Clear Filament cache
php artisan filament:cache-clear

# Check Filament version
composer show filament/filament
🚢 Deployment
Production Requirements
Update .env for production:

env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Use MySQL for production (recommended)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tikiti
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Paystack Live Keys
PAYSTACK_SECRET_KEY=sk_live_xxxxxxxxxxxxx
PAYSTACK_PUBLIC_KEY=pk_live_xxxxxxxxxxxxx
PAYSTACK_CALLBACK_URL=https://yourdomain.com/payment/callback
PAYSTACK_WEBHOOK_URL=https://yourdomain.com/payment/webhook
Optimize Laravel:

bash
php artisan optimize
php artisan route:cache
php artisan view:cache
php artisan event:cache
Set Proper Permissions:

bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chmod -R 755 public/storage
Configure Web Server:

Point your web server (Apache/Nginx) to the public directory.

Deploy to Shared Hosting
Upload all files to your hosting provider

Set the document root to the public folder

Configure .env with production credentials

Run migrations: php artisan migrate --force

Deploy to VPS (Ubuntu)
bash
# Install dependencies
sudo apt update
sudo apt install php8.5 sqlite3 nginx composer

# Clone repository
git clone https://github.com/yourusername/tikiti-kenya.git
cd tikiti-kenya

# Install PHP dependencies
composer install --optimize-autoloader --no-dev

# Set permissions
chown -R www-data:www-data storage
chown -R www-data:www-data bootstrap/cache

# Configure Nginx
sudo nano /etc/nginx/sites-available/tikiti
Example Nginx configuration:

nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /var/www/tikiti-kenya/public;

    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.5-fpm.sock;
    }
}
🤝 Contributing
Fork the repository

Create a feature branch: git checkout -b feature/amazing-feature

Commit changes: git commit -m 'Add amazing feature'

Push to branch: git push origin feature/amazing-feature

Open a Pull Request

📄 License
This project is open-sourced software licensed under the MIT license.

🙏 Acknowledgments
Laravel - The PHP framework

Filament - Admin panel framework

Paystack - Payment gateway for African businesses

Tailwind CSS - Utility-first CSS framework

Plus Jakarta Sans - Modern font

📞 Support
For support, please:

Check the Troubleshooting section

Review the logs: storage/logs/laravel.log

Open an issue on GitHub

Contact: support@tikiti.co.ke

<div align="center"> Made with ❤️ for Kenya's vibrant event scene
Tikiti Kenya - Your Gateway to Amazing Experiences

