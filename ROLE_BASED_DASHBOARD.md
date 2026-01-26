# Role-Based Dashboard System

## Overview

The HMS dashboard now displays **role-specific widgets** based on the logged-in user's role. Each role sees relevant statistics and quick actions tailored to their responsibilities.

## Implementation

### ✅ What's Been Implemented

**1. Role-Specific Widgets Created:**
- `resources/views/dashboard/widgets/doctor.blade.php`
- `resources/views/dashboard/widgets/nurse.blade.php`
- `resources/views/dashboard/widgets/pharmacist.blade.php`
- `resources/views/dashboard/widgets/receptionist.blade.php`
- `resources/views/dashboard/widgets/lab-technician.blade.php`
- `resources/views/dashboard/widgets/admin.blade.php`

**2. Dashboard Controller Enhanced:**
- Added role-specific data loading methods
- Each role gets only the data they need
- Optimized database queries

**3. Main Dashboard Updated:**
- Uses @role directives to show appropriate widgets
- Single `/dashboard` route for all users
- Content changes based on user role

## Dashboard Features by Role

### 👨‍⚕️ Doctor Dashboard
**Stats Shown:**
- Today's Appointments
- Pending Consultations
- Completed Today
- My Patients (total)

**Quick Actions:**
- My Appointments
- Consultations
- Patients
- Lab Results

### 👩‍⚕️ Nurse Dashboard
**Stats Shown:**
- Patients to Check-in
- Awaiting Vitals
- Admitted Patients (IPD)
- Active Patients

**Quick Actions:**
- Check-in Patients
- Record Vitals
- IPD Dashboard
- View Patients

**Additional:**
- Today's Tasks checklist

### 💊 Pharmacist Dashboard
**Stats Shown:**
- Pending Prescriptions
- Dispensed Today
- Low Stock Alerts
- Total Medicines

**Quick Actions:**
- Pharmacy Dashboard
- View Prescriptions
- Manage Inventory
- Dispense Medicines

**Additional:**
- Low Stock Alerts list

### 📋 Receptionist Dashboard
**Stats Shown:**
- Today's Appointments
- Checked-in Today
- Total Patients
- Pending Bills

**Quick Actions:**
- Register Patient
- Book Appointment
- Check-in Patient
- Billing

**Additional:**
- Today's Schedule Overview (with progress bars)

### 🔬 Lab Technician Dashboard
**Stats Shown:**
- Pending Tests
- In Progress
- Completed Today
- Total Tests

**Quick Actions:**
- Lab Dashboard
- View Orders
- Collect Sample
- Enter Results

**Additional:**
- Priority Tests breakdown (Urgent/High/Normal)

### 👔 Admin Dashboard
**Stats Shown:**
- Total Users
- Total Patients
- Today's Appointments
- System Status

**Quick Actions:**
- Manage Users
- Manage Patients
- Settings
- View Reports

**Additional:**
- Department Overview
- Recent Activity

## Technical Details

### Route Structure
```
GET /dashboard → Shows role-specific dashboard
```

Single route, content changes based on user role.

### Controller Methods

**DashboardController.php:**
```php
index()                        // Main dashboard method
loadDoctorData()              // Doctor-specific data
loadNurseData()               // Nurse-specific data
loadPharmacistData()          // Pharmacist-specific data
loadReceptionistData()        // Receptionist-specific data
loadLabTechnicianData()       // Lab tech-specific data
loadAdminData()               // Admin-specific data
```

### Blade Directives Used

```blade
@role('Doctor')
  @include('dashboard.widgets.doctor')
@endrole

@hasanyrole('Admin|Super Admin')
  @include('dashboard.widgets.admin')
@endhasanyrole

@can('pharmacy.view-dashboard')
  <!-- Permission-based content -->
@endcan
```

## Data Loading Strategy

### Efficient Queries
- Only loads data relevant to user's role
- Uses eager loading for relationships
- Counts instead of full queries where possible

### Example (Nurse Data):
```php
private function loadNurseData($user, &$stats)
{
    if (!$user->hasRole('Nurse')) {
        return; // Skip if not a nurse
    }

    $stats['scheduled_appointments'] = Appointment::whereDate(...)
        ->where('status', 'scheduled')
        ->count();
    
    // ... more nurse-specific data
}
```

## Styling

### Consistent Design
- All widgets follow the same design language
- Purple accent color (#667eea)
- Responsive grid layouts
- Hover effects and transitions
- Dark mode support

### Key CSS Classes
- `.stats-grid` - 4-column responsive grid
- `.stat-card` - Individual stat card
- `.quick-actions-grid` - Quick action buttons grid
- `.section-card` - Content sections
- `.section-title` - Section headings

## Benefits of This Approach

✅ **Single Entry Point**
- All users go to `/dashboard`
- Consistent URL structure
- Easier to maintain

✅ **Role-Specific Experience**
- Each role sees relevant information
- No clutter from irrelevant data
- Focused workflow

✅ **Permission-Based**
- Uses existing Spatie permissions
- Already integrated with your auth system
- Fine-grained control with @can directives

✅ **Performant**
- Only loads necessary data
- No unnecessary database queries
- Efficient caching potential

✅ **Maintainable**
- Widgets are separate files
- Easy to update individual roles
- Clear separation of concerns

✅ **Extensible**
- Easy to add new roles
- Simple to customize widgets
- Can add more stats/actions easily

## Adding a New Role

To add a new role dashboard:

**1. Create Widget File:**
```bash
resources/views/dashboard/widgets/new-role.blade.php
```

**2. Add Data Loading Method:**
```php
// In DashboardController.php
private function loadNewRoleData($user, &$stats)
{
    if (!$user->hasRole('New Role')) {
        return;
    }

    $stats['some_metric'] = Model::count();
    // ... load role-specific data
}
```

**3. Call in Main Method:**
```php
$this->loadNewRoleData($user, $stats);
```

**4. Add to Dashboard View:**
```blade
@role('New Role')
  @include('dashboard.widgets.new-role')
@endrole
```

## Testing

### Test Each Role Dashboard

1. **Create test users for each role**
2. **Login as each user**
3. **Verify:**
   - Correct stats are shown
   - Quick actions work
   - No errors in console
   - Data is accurate

### Example Test Flow

```bash
# Doctor
Login as doctor → Check stats match appointments

# Nurse
Login as nurse → Verify patient counts

# Pharmacist
Login as pharmacist → Check prescription counts

# etc...
```

## Future Enhancements

### Possible Additions

1. **Charts & Graphs**
   - Add visual charts to widgets
   - Trend lines for metrics
   - Pie charts for distributions

2. **Real-Time Updates**
   - WebSocket integration
   - Auto-refresh stats
   - Live notifications

3. **Customizable Widgets**
   - Let users choose which widgets to show
   - Drag-and-drop widget arrangement
   - Save user preferences

4. **More Granular Permissions**
   - Per-widget permissions
   - Custom widget combinations
   - Department-specific views

## Summary

You now have a **professional role-based dashboard** that:
- Shows relevant information to each user role
- Uses a single route (`/dashboard`)
- Integrates with your existing permission system
- Is easy to maintain and extend
- Provides a tailored experience for each user type

Each user sees exactly what they need - nothing more, nothing less!
