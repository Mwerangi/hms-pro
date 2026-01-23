# HMS Tech Stack & Architecture

**Project**: Hospital Management System (HMS Pro)  
**Date**: January 19, 2026  
**Architecture**: Monolithic MVC with RESTful API capabilities

---

## 🛠️ **Technology Stack**

### Backend Framework
- **Laravel 11.x** (PHP 8.2+)
  - Modern PHP framework
  - MVC architecture
  - Built-in authentication & authorization
  - Eloquent ORM for database operations
  - Laravel Sanctum for API authentication
  - Laravel Queues for background jobs
  - Laravel Scheduler for cron jobs

### Database
- **MySQL 8.0+**
  - Primary relational database
  - Full ACID compliance
  - Excellent for complex relationships (patients, appointments, billing)
  - Good performance for read-heavy operations

### Frontend
- **Blade Templates** (Laravel's templating engine)
  - Server-side rendering
  - Component-based structure
  - Easy integration with Laravel
  
- **Bootstrap 5.3+**
  - Responsive grid system
  - Pre-built components
  - Already integrated in theme
  
- **Alpine.js** (Lightweight JS framework)
  - Reactive components
  - Minimal overhead
  - Perfect for interactive UI elements
  - Better alternative to jQuery for modern apps
  
- **Chart.js / ApexCharts**
  - Data visualization
  - Interactive charts for analytics
  
- **Bootstrap Icons**
  - Icon library (already in theme)

### Additional Tools & Libraries

**PHP Packages** (via Composer):
- `laravel/sanctum` - API authentication
- `spatie/laravel-permission` - Role & permission management
- `barryvdh/laravel-dompdf` - PDF generation (prescriptions, reports, bills)
- `maatwebsite/excel` - Excel import/export
- `intervention/image` - Image processing (patient photos, reports)
- `laravel/telescope` - Debugging & monitoring (development)

**JavaScript Libraries** (via NPM):
- `alpinejs` - Reactive UI components
- `chart.js` or `apexcharts` - Charts
- `sweetalert2` - Beautiful alerts & confirmations
- `datatables` - Advanced table features
- `flatpickr` - Date/time picker
- `select2` - Enhanced select dropdowns

### Development Tools
- **Composer** - PHP dependency manager
- **NPM/Yarn** - JavaScript package manager
- **Laravel Vite** - Asset bundling (CSS/JS)
- **Laravel Mix** - Alternative asset compilation
- **PHP CodeSniffer** - Code quality
- **PHPUnit** - Testing

### Server Requirements
- **PHP**: 8.2 or higher
- **MySQL**: 8.0 or higher
- **Composer**: Latest version
- **Node.js**: 18.x or higher
- **NPM**: Latest version

### Recommended Hosting
- **Development**: Laravel Valet (Mac), Laragon (Windows), Docker
- **Production**: 
  - VPS: DigitalOcean, AWS EC2, Linode
  - Shared: Hostinger, SiteGround (with SSH access)
  - Platform: Laravel Forge (recommended), Ploi

---

## 📁 **Project Structure**

```
HMS/
├── app/
│   ├── Console/
│   │   └── Commands/              # Custom artisan commands
│   ├── Exceptions/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/             # Authentication
│   │   │   ├── Patient/          # Patient management
│   │   │   ├── Appointment/      # Appointments
│   │   │   ├── OPD/              # OPD module
│   │   │   ├── IPD/              # IPD module
│   │   │   ├── Laboratory/       # Lab module
│   │   │   ├── Pharmacy/         # Pharmacy module
│   │   │   ├── Billing/          # Billing module
│   │   │   ├── Report/           # Reports & analytics
│   │   │   └── Dashboard/        # Dashboard
│   │   ├── Middleware/           # Custom middleware
│   │   ├── Requests/             # Form validation
│   │   └── Resources/            # API resources
│   ├── Models/
│   │   ├── User.php
│   │   ├── Patient.php
│   │   ├── Appointment.php
│   │   ├── Doctor.php
│   │   ├── Prescription.php
│   │   ├── LabTest.php
│   │   ├── Invoice.php
│   │   └── ... (30+ models)
│   ├── Services/                 # Business logic layer
│   │   ├── PatientService.php
│   │   ├── AppointmentService.php
│   │   ├── BillingService.php
│   │   └── NotificationService.php
│   └── Traits/                   # Reusable traits
│       ├── HasPatientId.php
│       └── Billable.php
├── bootstrap/
├── config/
│   ├── database.php
│   ├── auth.php
│   ├── permission.php
│   └── hms.php                   # Custom HMS config
├── database/
│   ├── factories/                # Model factories for testing
│   ├── migrations/               # Database migrations
│   │   ├── 2026_01_19_000001_create_patients_table.php
│   │   ├── 2026_01_19_000002_create_appointments_table.php
│   │   └── ... (50+ migrations)
│   └── seeders/                  # Database seeders
│       ├── RoleSeeder.php
│       ├── UserSeeder.php
│       └── DemoDataSeeder.php
├── public/
│   ├── theme/                    # Copied from existing theme folder
│   ├── uploads/
│   │   ├── patients/
│   │   ├── reports/
│   │   └── prescriptions/
│   ├── css/
│   ├── js/
│   └── index.php
├── resources/
│   ├── css/
│   │   └── app.css
│   ├── js/
│   │   ├── app.js
│   │   └── alpine/               # Alpine.js components
│   └── views/
│       ├── layouts/
│       │   ├── app.blade.php     # Main layout
│       │   ├── auth.blade.php    # Auth layout
│       │   └── components/       # Blade components
│       ├── auth/
│       │   ├── login.blade.php
│       │   └── register.blade.php
│       ├── dashboard/
│       │   └── index.blade.php
│       ├── patients/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   ├── edit.blade.php
│       │   └── show.blade.php
│       ├── appointments/
│       ├── opd/
│       ├── ipd/
│       ├── laboratory/
│       ├── pharmacy/
│       ├── billing/
│       └── reports/
├── routes/
│   ├── web.php                   # Web routes
│   ├── api.php                   # API routes (future mobile app)
│   └── console.php
├── storage/
│   ├── app/
│   │   ├── public/
│   │   └── reports/
│   ├── framework/
│   └── logs/
├── tests/
│   ├── Feature/
│   └── Unit/
├── .env                          # Environment configuration
├── .env.example
├── artisan                       # Laravel CLI
├── composer.json
├── package.json
├── vite.config.js
├── phpunit.xml
└── README.md
```

---

## 🗄️ **Database Architecture**

### Core Tables (Phase 1)

**Users & Authentication**:
- `users` - System users (doctors, nurses, staff, admin)
- `roles` - User roles
- `permissions` - System permissions
- `role_user` - Pivot table
- `password_reset_tokens`
- `sessions`

**Patient Management**:
- `patients` - Patient master data
- `patient_medical_history` - Medical history
- `emergency_contacts` - Emergency contact details

**Appointments**:
- `appointments` - Appointment bookings
- `appointment_slots` - Doctor time slots
- `appointment_statuses` - Status tracking

**OPD**:
- `opd_consultations` - Consultation records
- `vital_signs` - Patient vitals (BP, temp, pulse)
- `prescriptions` - Prescription master
- `prescription_items` - Medicine details

**Billing**:
- `invoices` - Bill master
- `invoice_items` - Bill line items
- `payments` - Payment records

### Additional Tables (Phase 2-5)
- IPD: `admissions`, `beds`, `wards`, `bed_allocations`
- Lab: `lab_tests`, `test_results`, `test_categories`
- Pharmacy: `medicines`, `medicine_stock`, `medicine_categories`
- Radiology: `radiology_tests`, `radiology_results`
- OT: `surgeries`, `operation_theaters`, `surgery_schedules`
- Reports: `audit_logs`, `activity_logs`

---

## 🔐 **Authentication & Authorization**

### User Roles
1. **Super Admin** - Full system access
2. **Admin** - Hospital management
3. **Doctor** - Medical staff
4. **Nurse** - Nursing staff
5. **Receptionist** - Front desk
6. **Pharmacist** - Pharmacy operations
7. **Lab Technician** - Laboratory
8. **Radiologist** - Radiology
9. **Accountant** - Billing & finance

### Permission System (using Spatie Laravel Permission)
```php
// Example permissions
'view-patients'
'create-patients'
'edit-patients'
'delete-patients'
'view-appointments'
'create-appointments'
'cancel-appointments'
'view-billing'
'create-invoice'
'view-reports'
// ... 50+ permissions
```

### Authentication Flow
- Laravel Breeze/Jetstream (Customized)
- Session-based authentication (web)
- Token-based authentication (API - future mobile app)
- Role-based dashboard redirection
- Password policies & 2FA (Phase 5)

---

## 🌐 **Routing Structure**

### Web Routes
```php
// Public routes
Route::get('/', [HomeController::class, 'index']);

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login']);
    Route::post('/login', [AuthController::class, 'authenticate']);
});

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    
    // Patients
    Route::resource('patients', PatientController::class);
    
    // Appointments
    Route::resource('appointments', AppointmentController::class);
    
    // OPD
    Route::prefix('opd')->group(function () {
        Route::get('/', [OPDController::class, 'index']);
        Route::get('/consultation/{id}', [OPDController::class, 'consultation']);
    });
    
    // Role-specific routes
    Route::middleware(['role:doctor'])->group(function () {
        // Doctor-only routes
    });
    
    Route::middleware(['role:admin|super-admin'])->group(function () {
        // Admin routes
    });
});
```

### API Routes (Future)
```php
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('patients', Api\PatientController::class);
    Route::apiResource('appointments', Api\AppointmentController::class);
});
```

---

## 📊 **Data Flow & Architecture**

### MVC Pattern
```
User Request
    ↓
Route (web.php)
    ↓
Controller (validates, calls service)
    ↓
Service Layer (business logic)
    ↓
Model (Eloquent ORM)
    ↓
Database (MySQL)
    ↓
Model → Service → Controller
    ↓
View (Blade template)
    ↓
Response (HTML/JSON)
```

### Example: Patient Registration Flow
```
1. User fills registration form
2. POST /patients → PatientController@store
3. FormRequest validates input
4. PatientService->createPatient($data)
5. Generate unique Patient ID
6. Create Patient model → Save to DB
7. Upload photo (if any) → Storage
8. Create emergency contact
9. Log activity
10. Redirect with success message
11. Render patient details page
```

---

## 🎨 **Frontend Architecture**

### Blade Layout System
```blade
{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    @include('layouts.partials.head')
    @stack('styles')
</head>
<body>
    @include('layouts.partials.header')
    @include('layouts.partials.sidebar')
    
    <main class="hms-main">
        @yield('content')
    </main>
    
    @include('layouts.partials.footer')
    @stack('scripts')
</body>
</html>

{{-- Usage in pages --}}
@extends('layouts.app')

@section('content')
    <h1>Dashboard</h1>
@endsection
```

### Component-Based UI
```blade
{{-- Reusable components --}}
<x-stat-card 
    title="Total Patients" 
    value="247" 
    icon="people" 
    color="primary" 
    trend="+12%" 
/>

<x-data-table 
    :columns="['Name', 'Phone', 'Status']" 
    :data="$patients" 
    :actions="['view', 'edit', 'delete']"
/>
```

---

## 🔄 **API Strategy (Future Mobile App)**

### RESTful API Endpoints
```
GET    /api/patients              # List patients
POST   /api/patients              # Create patient
GET    /api/patients/{id}         # Get patient details
PUT    /api/patients/{id}         # Update patient
DELETE /api/patients/{id}         # Delete patient

GET    /api/appointments          # List appointments
POST   /api/appointments          # Book appointment
GET    /api/doctors/{id}/slots    # Get available slots
```

### API Response Format
```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "John Doe",
        "patient_id": "P-00001"
    },
    "message": "Patient created successfully"
}
```

---

## 📦 **Package Installation Commands**

### Initial Setup
```bash
# Install Laravel
composer create-project laravel/laravel hms-backend

# Install required packages
composer require spatie/laravel-permission
composer require barryvdh/laravel-dompdf
composer require maatwebsite/excel
composer require intervention/image

# Development packages
composer require --dev laravel/telescope
composer require --dev barryvdh/laravel-debugbar

# Install frontend dependencies
npm install
npm install alpinejs chart.js sweetalert2 datatables.net flatpickr
```

---

## 🚀 **Deployment Checklist**

### Production Server Setup
- [ ] PHP 8.2+ installed
- [ ] MySQL 8.0+ configured
- [ ] Composer installed
- [ ] Node.js & NPM installed
- [ ] SSL certificate (HTTPS)
- [ ] Configure `.env` file
- [ ] Run migrations
- [ ] Seed initial data
- [ ] Optimize Laravel (`php artisan optimize`)
- [ ] Setup cron jobs for scheduler
- [ ] Configure queue workers
- [ ] Setup backup system
- [ ] Configure logging & monitoring

### Performance Optimization
- Query optimization (indexes, eager loading)
- Redis/Memcached for caching
- CDN for static assets
- Database connection pooling
- Opcache enabled
- Asset minification & compression

---

## 🧪 **Testing Strategy**

### Testing Levels
1. **Unit Tests** - Model logic, services
2. **Feature Tests** - HTTP requests, workflows
3. **Browser Tests** - Laravel Dusk (optional)

### Example Test
```php
public function test_patient_can_be_created()
{
    $response = $this->post('/patients', [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'phone' => '1234567890',
        'dob' => '1990-01-01',
    ]);
    
    $response->assertStatus(302);
    $this->assertDatabaseHas('patients', [
        'first_name' => 'John',
    ]);
}
```

---

## 📱 **Future Enhancements**

### Phase 6+ (Post-MVP)
- **Mobile Apps**: React Native / Flutter
- **Real-time Features**: Laravel WebSockets / Pusher
- **AI Integration**: Diagnosis assistance, chatbot
- **Microservices**: Separate services for billing, lab, etc.
- **Multi-tenancy**: Support multiple hospitals
- **FHIR Compliance**: Healthcare interoperability
- **Blockchain**: Secure medical records

---

## 📚 **Development Workflow**

### Git Workflow
```
main (production)
  ↓
develop (staging)
  ↓
feature/patient-registration
feature/appointment-booking
feature/billing-module
```

### Coding Standards
- PSR-12 coding standard
- Laravel best practices
- Repository pattern for complex queries
- Service layer for business logic
- Form Request validation
- Meaningful commit messages

---

**Next Steps:**
1. ✅ Tech stack defined
2. ⏭️ Initialize Laravel project
3. ⏭️ Setup database & configure MySQL
4. ⏭️ Create base migrations for Phase 1
5. ⏭️ Setup authentication system
6. ⏭️ Integrate theme with Laravel Blade
7. ⏭️ Start building Module 1.1: User Management

---

**Last Updated**: January 19, 2026
