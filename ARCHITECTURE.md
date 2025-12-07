# HugousERP System Architecture

## Overview

HugousERP is a multi-tenant, multi-branch Enterprise Resource Planning system built with a modern PHP stack. The architecture follows Domain-Driven Design (DDD) principles with a clear separation of concerns.

## Technology Stack

### Backend
- **Framework**: Laravel 12.x
- **PHP Version**: 8.2+
- **Database**: MySQL 8.0+, PostgreSQL 13+, SQLite 3.35+
- **ORM**: Eloquent
- **API**: Laravel Sanctum (token-based authentication)
- **Queue**: Database driver (upgradeable to Redis/SQS)
- **Cache**: Database/Redis/Memcached

### Frontend
- **Framework**: Livewire 3.x (TALL stack)
- **UI Framework**: Tailwind CSS 3.x
- **Build Tool**: Vite
- **JavaScript**: Alpine.js (via Livewire)

### Security
- **Authentication**: Laravel Sanctum + Session
- **Authorization**: Spatie Laravel Permission
- **2FA**: pragmarx/google2fa
- **Password Hashing**: Bcrypt (configurable rounds)

## Architecture Layers

### 1. Presentation Layer

#### Livewire Components
- Located in `app/Livewire/`
- 99+ reactive components organized by module
- Handle user interactions and form validation
- Emit events for cross-component communication

#### Blade Templates
- Located in `resources/views/`
- Component-based views with layouts
- CSRF protection on all forms
- XSS protection through automatic escaping

#### HTTP Controllers
- Located in `app/Http/Controllers/`
- RESTful API endpoints
- Export and report generation
- File handling

### 2. Application Layer

#### Services
Located in `app/Services/`, these encapsulate business logic:

**Core Services:**
- `AuthService`: Authentication, authorization, token management
- `ProductService`: Product CRUD, pricing, variants
- `SaleService`: Sales order processing, invoicing
- `PurchaseService`: Purchase order management
- `InventoryService`: Stock movements, transfers, adjustments
- `POSService`: Point of sale operations
- `HRMService`: Employee, payroll, attendance management
- `ReportService`: Report generation and scheduling
- `NotificationService`: Multi-channel notifications

**Service Characteristics:**
- Contract-based (interfaces in `app/Services/Contracts/`)
- Error handling with `HandlesServiceErrors` trait
- Transaction management
- Event dispatching
- Audit logging

### 3. Domain Layer

#### Models
Located in `app/Models/`, extending `BaseModel`:

**Core Models:**
- `User`: System users with roles and permissions
- `Branch`: Multi-branch support
- `Product`: Inventory items with variants
- `Sale`, `SaleItem`: Sales transactions
- `Purchase`, `PurchaseItem`: Purchase transactions
- `Customer`, `Supplier`: Business partners
- `StockMovement`: Inventory tracking
- `AuditLog`: Comprehensive audit trail

**Model Features:**
- Eloquent relationships
- Soft deletes
- UUID generation
- Timestamps
- Observers for lifecycle hooks
- Scopes for query reusability

#### Repositories
Located in `app/Repositories/`:
- Data access abstraction
- Complex query encapsulation
- Caching strategies

### 4. Infrastructure Layer

#### Middleware
Located in `app/Http/Middleware/`:

**Security Middleware:**
- `SecurityHeaders`: HTTP security headers
- `Authenticate`: Authentication check
- `Require2FA`: Two-factor authentication enforcement
- `EnsurePermission`: Permission-based access control
- `EnsureBranchAccess`: Branch-level authorization
- `ApiRateLimiter`: Rate limiting for API endpoints

**Utility Middleware:**
- `SetLocale`: Internationalization
- `SetBranchContext`: Branch context injection
- `SetModuleContext`: Module context injection
- `PaginationSanitizer`: Pagination parameter validation
- `CorrelationId`: Request tracking
- `ETag`: HTTP caching

#### Jobs
Located in `app/Jobs/`:
- Background task processing
- Email notifications
- Report generation
- Data imports/exports

#### Events & Listeners
- Located in `app/Events/` and `app/Listeners/`
- Domain event handling
- Decoupled notifications

## Data Flow

### Web Request Flow
```
User Request
    ↓
Route (web.php)
    ↓
Middleware Stack
    ↓
Livewire Component or Controller
    ↓
Service Layer
    ↓
Repository (if needed)
    ↓
Model
    ↓
Database
```

### API Request Flow
```
API Request
    ↓
Route (api.php)
    ↓
API Middleware (auth:sanctum, branch access, permissions)
    ↓
Controller
    ↓
Form Request Validation
    ↓
Service Layer
    ↓
Model
    ↓
Database
    ↓
API Response (standardized format)
```

## Database Design

### Schema Organization

#### User Management
- `users`: System users
- `roles`, `permissions`: RBAC implementation
- `model_has_roles`, `model_has_permissions`: Polymorphic relationships
- `user_sessions`: Active session tracking
- `login_activities`: Login audit trail

#### Branch Management
- `branches`: Company branches
- `branch_user`: User-branch associations
- `branch_modules`: Enabled modules per branch

#### Inventory
- `products`: Product catalog
- `product_categories`: Hierarchical categories
- `product_variations`: Product variants
- `warehouses`: Storage locations
- `stock_movements`: All inventory transactions

#### Sales & Purchases
- `sales`, `sale_items`, `sale_payments`: Sales transactions
- `purchases`, `purchase_items`: Purchase transactions
- `customers`: Customer master
- `suppliers`: Supplier master

#### Financial
- `accounts`: Chart of accounts
- `journal_entries`, `journal_entry_lines`: Double-entry bookkeeping
- `installment_plans`, `installment_payments`: Payment plans

#### HRM
- `hr_employees`: Employee records
- `attendances`: Time tracking
- `payrolls`: Salary processing
- `leave_requests`: Leave management

#### Audit & Logs
- `audit_logs`: Comprehensive audit trail
- `notifications`: In-app notifications

### Indexing Strategy

**Primary Indexes:**
- Primary keys on all tables
- Unique constraints on natural keys (email, code, SKU)

**Foreign Key Indexes:**
- Indexes on all foreign key columns
- Composite indexes for common joins

**Query Optimization Indexes:**
- Status columns for filtering
- Date columns for reporting
- Composite indexes for frequent query patterns
- Full-text indexes for search functionality

## Security Architecture

### Authentication Flow

1. **User Login**
   - Credentials validated
   - Rate limiting applied (5 attempts per minute)
   - Password hashed with bcrypt
   - Session created on success
   - Last login timestamp updated

2. **Two-Factor Authentication**
   - Optional per user
   - Google Authenticator compatible
   - Recovery codes provided
   - Time-based OTP validation

3. **API Authentication**
   - Sanctum token-based
   - Scoped abilities
   - Token expiration configurable
   - Multiple tokens per user

### Authorization

**Permission System:**
- Fine-grained permissions (100+ permissions)
- Role-based assignment
- Hierarchical roles (Super Admin > Admin > Manager > User)
- Dynamic permission checking
- Policy-based authorization for models

**Branch-Level Security:**
- Users assigned to branches
- Data isolation by branch
- Cross-branch restrictions
- Centralized admin access

### Data Security

**At Rest:**
- Database encryption support
- Sensitive fields encrypted (2FA secrets, tokens)
- Backup encryption

**In Transit:**
- HTTPS enforced in production
- HSTS headers
- Secure cookie flags

**Input Validation:**
- Form Request classes for validation
- Type-safe parameters
- Sanitization of user inputs
- CSRF token verification

### Audit Trail

All critical operations logged in `audit_logs`:
- User identification
- Action performed
- Before/after states
- Timestamp
- IP address
- User agent

## Performance Optimization

### Database Optimization
- Query optimization with eager loading
- Composite indexes on frequent queries
- Database-level constraints
- Connection pooling
- Read/write replica support

### Caching Strategy
- **Page Cache**: Full page caching for public pages
- **Query Cache**: Database query result caching
- **Object Cache**: Model instance caching
- **Fragment Cache**: Partial view caching

### Queue System
- Background processing for heavy operations
- Email sending
- Report generation
- Data exports
- Scheduled tasks

## Module System

### Core Modules
Each module is self-contained with:
- Livewire components
- Services
- Models
- Migrations
- Routes
- Policies

**Available Modules:**
- Inventory
- Sales
- Purchases
- POS
- Accounting
- HRM
- Rental
- Store Integration
- Reports

### Module Activation
- Per-branch module enablement
- Permission-based access
- Dynamic routing
- Feature flags

## API Design

### RESTful Principles
- Resource-based URLs
- HTTP methods (GET, POST, PUT, DELETE)
- Stateless communication
- JSON payloads

### Response Format
```json
{
  "success": true,
  "message": "Operation successful",
  "data": { ... },
  "meta": {
    "pagination": { ... },
    "timestamp": "2025-12-07T12:00:00Z"
  }
}
```

### Error Handling
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "field": ["Error message"]
  },
  "meta": {
    "code": "VALIDATION_ERROR"
  }
}
```

## Deployment Architecture

### Production Deployment
```
Load Balancer (Nginx/Apache)
    ↓
Application Servers (PHP-FPM)
    ↓
Database Cluster (MySQL/PostgreSQL)
    ↓
Cache Cluster (Redis)
    ↓
Queue Workers
    ↓
File Storage (S3/Local)
```

### Scaling Strategy
- **Horizontal Scaling**: Multiple application servers
- **Database Replication**: Master-slave setup
- **Cache Cluster**: Redis/Memcached cluster
- **Queue Workers**: Multiple worker processes
- **CDN**: Static asset distribution

## Monitoring & Logging

### Application Monitoring
- Error tracking (Sentry integration ready)
- Performance monitoring
- Query performance analysis
- Queue monitoring

### Logging
- **Application Logs**: Laravel log channels
- **Access Logs**: HTTP request logging
- **Audit Logs**: Business operation tracking
- **Error Logs**: Exception and error tracking

### Health Checks
- Database connectivity
- Cache availability
- Queue worker status
- Disk space monitoring

## Testing Strategy

### Test Pyramid
1. **Unit Tests**: Service and model logic
2. **Feature Tests**: HTTP requests and responses
3. **Integration Tests**: Component interactions
4. **Browser Tests**: End-to-end workflows (optional)

### Test Coverage
- Critical business logic: 100%
- Services: 80%+
- Controllers: 70%+
- Models: 60%+

## Future Enhancements

### Planned Features
- Microservices architecture for scaling
- GraphQL API
- Mobile application
- Advanced analytics and BI
- Machine learning for forecasting
- Blockchain integration for supply chain

### Technical Debt
- Migrate to PHP 8.3+
- Upgrade to Laravel 13 when available
- Implement CQRS pattern for complex domains
- Add event sourcing for audit trail
- Improve test coverage to 90%+

## Conclusion

HugousERP follows modern software engineering principles with a focus on maintainability, security, and scalability. The architecture supports both current business needs and future growth through its modular design and clean separation of concerns.
