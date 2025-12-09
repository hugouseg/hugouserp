# HugousERP - Enterprise Resource Planning System

A comprehensive, modern ERP system built with Laravel 12, Livewire 3, and Tailwind CSS.
This system provides complete business management capabilities including inventory, sales,
purchases, HRM, accounting, POS, and rental management.

## 🚀 Features

### Core Modules
- **Inventory Management**: Product catalog, stock movements, warehouses, serial/batch tracking
- **Sales & POS**: Point of Sale terminal, sales orders, invoicing, payment processing
- **Purchases**: Purchase orders, supplier management, receiving, returns
- **Customer Relationship**: Customer profiles, transaction history, loyalty programs
- **Human Resources**: Employee management, attendance tracking, payroll processing
- **Accounting**: Journal entries, accounts management, financial reporting
- **Rental Management**: Property/equipment rentals, contracts, invoicing
- **Reports & Analytics**: Comprehensive reporting with scheduled exports
- **Multi-Branch**: Branch-based operations with centralized management
- **Store Integration**: WooCommerce and Shopify synchronization

### Security Features
- Two-Factor Authentication (2FA) with Google Authenticator
- Role-based access control (RBAC) with granular permissions
- Session management with device tracking
- Rate limiting on sensitive endpoints
- Security headers (XSS, clickjacking, MIME sniffing protection)
- Audit logging for all critical operations
- CSRF protection on all forms
- SQL injection prevention
- Password hashing with bcrypt

### Technical Highlights
- **Framework**: Laravel 12 with PHP 8.2+
- **Frontend**: Livewire 3 for reactive components
- **UI**: Tailwind CSS with responsive design
- **Database**: MySQL/PostgreSQL/SQLite support with optimized indexes
- **Authentication**: Sanctum for API, session-based for web
- **Queue System**: Background job processing
- **Caching**: Multi-layer caching strategy
- **Testing**: PHPUnit with feature and unit tests

## 📋 Requirements

- PHP 8.2 or higher
- Composer
- Node.js and NPM
- MySQL 8.0+ / PostgreSQL 13+ / SQLite 3.35+
- Extensions: OpenSSL, PDO, Mbstring, Tokenizer, XML, Ctype, JSON, BCMath

## 🛠️ Installation

### Quick Setup

```bash
# Clone the repository
git clone https://github.com/hugouseg/hugouserp.git
cd hugouserp

# Copy environment file and prepare SQLite database
cp .env.example .env
touch database/database.sqlite

# Run automated setup
composer run setup

# Start development server
composer run dev
```

> **Note:** The default configuration uses SQLite for quick local testing with zero database configuration required. Simply ensure the `database/database.sqlite` file exists before running migrations.

### Manual Setup

```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Create database and run migrations
touch database/database.sqlite
php artisan migrate --seed

# Build frontend assets
npm run build

# Start development server
php artisan serve
```

## 🔧 Configuration

### Environment Variables

Key configuration options in `.env`:

```env
# Application
APP_NAME="HugoERP"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hugouserp
DB_USERNAME=root
DB_PASSWORD=

# Mail (for notifications and password resets)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null

# Queue (for background jobs)
QUEUE_CONNECTION=database

# Session
SESSION_DRIVER=database
SESSION_LIFETIME=120
```

### Cron Jobs

For scheduled reports and automated tasks, add this to your crontab:

```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

See [CRON_JOBS.md](CRON_JOBS.md) for detailed scheduling information.

## 📚 System Architecture

### Directory Structure

```
app/
├── Console/          # Artisan commands
├── Http/
│   ├── Controllers/  # API and web controllers
│   ├── Middleware/   # Request/response middleware
│   └── Requests/     # Form request validation
├── Livewire/         # Livewire components (99 components)
├── Models/           # Eloquent models (80+ models)
├── Services/         # Business logic layer
├── Repositories/     # Data access layer
├── Policies/         # Authorization policies
└── Observers/        # Model observers

database/
├── migrations/       # Database schema (48 migrations)
├── seeders/         # Data seeders
└── factories/       # Model factories

resources/
├── views/           # Blade templates (122 templates)
│   ├── livewire/    # Livewire views
│   ├── layouts/     # Layout templates
│   └── components/  # Reusable components
└── js/              # JavaScript assets

routes/
├── web.php          # Web routes
├── api.php          # API routes
└── console.php      # Console routes
```

### Database Schema

The system uses a comprehensive relational database schema with 40+ tables:

**Core Tables**:
- `users`, `branches`, `roles`, `permissions` - User management
- `products`, `categories`, `warehouses` - Inventory
- `sales`, `sale_items`, `sale_payments` - Sales management
- `purchases`, `purchase_items` - Purchase management
- `customers`, `suppliers` - Business partners
- `stock_movements` - Inventory tracking
- `audit_logs` - Audit trail

See individual migration files in `database/migrations/` for detailed schema.

### Service Layer Architecture

The application follows a service-oriented architecture:

```
Controller/Livewire → Service → Repository → Model → Database
```

**Key Services**:
- `AuthService`: Authentication and authorization
- `ProductService`: Product management
- `SaleService`: Sales processing
- `POSService`: Point of sale operations
- `InventoryService`: Stock management
- `ReportService`: Report generation
- `NotificationService`: User notifications

## 🔐 Security

### Authentication

The system supports multiple authentication methods:

1. **Web Authentication**: Session-based with remember me
2. **API Authentication**: Laravel Sanctum token-based
3. **Two-Factor Authentication**: Google Authenticator compatible
4. **Multi-Session Management**: Control active sessions per user

### Authorization

Fine-grained permission system using Spatie Laravel Permission:

- **Super Admin**: Full system access
- **Admin**: Branch-level management
- **Manager**: Department operations
- **User**: Standard access

Permissions are enforced at:
- Route level (middleware)
- Controller level (authorization)
- UI level (Blade directives)
- API level (policy checks)

### Security Best Practices

1. **Input Validation**: All user inputs validated using Form Requests
2. **SQL Injection Prevention**: Parameterized queries and Eloquent ORM
3. **XSS Prevention**: Automatic output escaping in Blade templates
4. **CSRF Protection**: Token verification on all state-changing requests
5. **Rate Limiting**: Prevents brute force attacks
6. **Security Headers**: XSS, clickjacking, MIME sniffing protection
7. **Password Security**: Bcrypt hashing with configurable rounds
8. **Audit Logging**: All critical operations logged

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit

# Run with coverage
php artisan test --coverage

# Code style check
./vendor/bin/pint --test

# Fix code style
./vendor/bin/pint
```

## 📊 Business Workflows

### Sales Process
1. Customer places order (POS or web)
2. System validates inventory availability
3. Stock is reserved for the order
4. Payment is processed
5. Stock movement recorded
6. Invoice generated
7. Notification sent to customer

### Purchase Process
1. Create purchase order
2. Send to supplier
3. Receive goods
4. Verify quantities
5. Update inventory
6. Record payment
7. Update supplier balance

### Inventory Management
1. Product creation with details
2. Initial stock entry
3. Movement tracking (sales, purchases, transfers, adjustments)
4. Stock alerts for low inventory
5. Periodic stock counts
6. Reconciliation

## 🔄 API Documentation

### Authentication

```bash
# Login
POST /api/auth/login
Content-Type: application/json

{
  "email": "user@example.com",
  "password": "password"
}

Response: { "token": "..." }
```

### Products

```bash
# List products
GET /api/products?branch_id=1&page=1

# Create product
POST /api/products
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Product Name",
  "sku": "SKU123",
  "price": 99.99,
  "branch_id": 1
}
```

### Sales

```bash
# Create sale
POST /api/sales
Authorization: Bearer {token}

{
  "customer_id": 1,
  "items": [
    {
      "product_id": 1,
      "qty": 2,
      "price": 50.00
    }
  ]
}
```

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

### Coding Standards

- Follow PSR-12 coding standard
- Use type declarations (strict_types=1)
- Write PHPDoc comments for all public methods
- Add tests for new features
- Run `./vendor/bin/pint` before committing

## 📝 License

This project is proprietary software. All rights reserved.

## 🆘 Support

For support, please contact the development team or open an issue in the repository.

## 🔄 Changelog

See [CHANGELOG.md](CHANGELOG.md) for version history and updates.

## 👥 Authors

- Development Team - [hugouseg](https://github.com/hugouseg)

## 🙏 Acknowledgments

Built with:
- [Laravel](https://laravel.com) - PHP Framework
- [Livewire](https://laravel-livewire.com) - Full-stack framework
- [Tailwind CSS](https://tailwindcss.com) - CSS framework
- [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission) - Permission management
