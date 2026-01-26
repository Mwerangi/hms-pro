# HMS System Features & Permissions Analysis

## Complete System Feature Inventory

### 1. **Patient Management Module**
- View all patients (list, search, filter)
- Create new patient records
- Edit patient information
- View patient details
- Toggle patient active status
- View patient medical history
- View patient appointments
- View patient bills and charges

**Controllers**: `PatientController`
**Routes**: `/patients/*`

---

### 2. **Appointment Management Module**
- View appointments (with date/status filtering)
- Create scheduled appointments
- Create walk-in appointments
- Edit appointment details
- Cancel appointments
- Check-in patients
- Start consultation from appointment
- Complete consultation
- Reopen appointments
- Assign/reassign doctors
- View doctor-specific appointments

**Controllers**: `AppointmentController`
**Routes**: `/appointments/*`, `/doctor/appointments`

---

### 3. **Nursing Station Module**
- View nursing dashboard
- View waiting patients
- Record patient vitals (BP, pulse, temp, weight, height, SpO2, respiratory rate)
- Check-in patients
- Queue management
- Emergency patient registration

**Controllers**: `NursingStationController`
**Routes**: `/nursing/*`

---

### 4. **Consultation/OPD Module**
- View consultations
- Create consultation from appointment
- Record consultation details (chief complaint, diagnosis, examination)
- View patient history during consultation
- Edit consultation records
- Complete consultation
- Resume consultation
- Admit patient from consultation
- Add prescriptions
- Order lab tests
- Record vitals during consultation

**Controllers**: `ConsultationController`
**Routes**: `/consultations/*`

---

### 5. **Laboratory Module**
**Lab Tests:**
- View lab dashboard
- View all lab orders
- View specific lab order details
- Collect samples
- Process lab tests
- Enter lab results
- Mark results as reported
- Cancel lab orders
- Download lab results (PDF)
- View pending/in-progress/completed tests

**Radiology/Imaging:**
- View radiology dashboard
- Process imaging orders
- Upload imaging files
- Enter radiology reports
- Download imaging files

**Controllers**: `LabOrderController`
**Routes**: `/lab/*`, `/radiology/*`, `/lab-orders/*`

---

### 6. **Pharmacy Module**
- View pharmacy dashboard
- View pending prescriptions
- View prescription details
- Dispense medications
- Mark prescriptions as emergency
- Cancel prescriptions
- Track prescription status (pending, dispensed, cancelled)
- Partial dispensing support

**Controllers**: `PharmacyController`
**Routes**: `/pharmacy/*`

---

### 7. **IPD (In-Patient Department) Module**
**Ward Management:**
- View all wards
- Create new wards
- Edit ward details
- Toggle ward status (active/inactive)
- Assign nurses to wards
- View ward occupancy

**Bed Management:**
- View all beds
- Create new beds
- Edit bed details
- Toggle bed status
- Mark bed for cleaning
- Mark bed as available
- View bed availability by ward

**Admission Management:**
- View all admissions
- Create new admission
- View admission details
- Edit admission details
- Transfer patient to different ward/bed
- Discharge patient
- View admission history

**Controllers**: `IpdController`, `WardController`, `BedController`, `AdmissionController`
**Routes**: `/ipd/*`, `/wards/*`, `/beds/*`, `/admissions/*`

---

### 8. **Billing & Accounting Module**
**Services:**
- View service catalog
- Create new services
- Edit service details
- Toggle service status
- Categorize services (consultation, lab, radiology, pharmacy, etc.)

**Patient Charges:**
- View patient pending charges
- Auto-generate charges from services
- Manual charge entry

**Bills:**
- View all bills
- Create bills from appointments
- Create bills from patient charges
- View bill details
- Add payments to bills
- Print receipts
- Filter bills (by status, type, date)
- Generate bills from pending charges

**Accounting Dashboard:**
- View revenue statistics
- View pending charges
- View payment collection
- Financial reports

**Controllers**: `ServiceController`, `BillController`, `AccountingDashboardController`
**Routes**: `/services/*`, `/bills/*`, `/accounting/*`

---

### 9. **User Management Module**
- View all users
- Create new users
- Edit user details
- Toggle user active status
- Assign roles to users
- Filter users by role/status

**Controllers**: `UserController`
**Routes**: `/users/*`

---

### 10. **Dashboard Module**
- View main dashboard (role-based)
- View statistics (patients, appointments, consultations)
- View pending appointments
- View stuck appointments
- View missed appointments
- View recent consultations
- Quick action links

**Controllers**: `DashboardController`
**Routes**: `/dashboard`

---

## Current Roles & Permissions

### Existing Roles:
1. **Super Admin** - Full system access
2. **Admin** - Hospital management
3. **Doctor** - Medical operations
4. **Nurse** - Nursing operations
5. **Receptionist** - Front desk operations
6. **Pharmacist** - Pharmacy operations
7. **Lab Technician** - Laboratory operations
8. **Radiologist** - Radiology operations
9. **Accountant** - Billing & finance

### Current Permission Structure:

| Module | Permissions |
|--------|-------------|
| **Patients** | view, create, edit, delete |
| **Appointments** | view, create, edit, cancel |
| **OPD** | view-opd, create-consultation, view-consultation, create-prescription |
| **IPD** | view-ipd, admit-patient, discharge-patient, transfer-patient |
| **Laboratory** | view-lab-tests, create-lab-test, enter-lab-results, approve-lab-results |
| **Pharmacy** | view-pharmacy, dispense-medicine, manage-inventory, view-stock |
| **Billing** | view-billing, create-invoice, process-payment, view-reports |
| **Users** | view-users, create-users, edit-users, delete-users |
| **Settings** | manage-settings, view-audit-logs |

---

## Enhanced Permission Matrix (Recommended)

### Patient Management
- `patients.view` - View patient list
- `patients.view-details` - View patient details
- `patients.create` - Create new patient
- `patients.edit` - Edit patient information
- `patients.delete` - Delete patient
- `patients.toggle-status` - Activate/deactivate patient

### Appointment Management
- `appointments.view-all` - View all appointments
- `appointments.view-own` - View only assigned appointments
- `appointments.create` - Create appointment
- `appointments.edit` - Edit appointment
- `appointments.cancel` - Cancel appointment
- `appointments.checkin` - Check-in patient
- `appointments.start-consultation` - Start consultation
- `appointments.complete` - Complete appointment

### Nursing Station
- `nursing.view-dashboard` - Access nursing dashboard
- `nursing.record-vitals` - Record patient vitals
- `nursing.checkin-patients` - Check-in patients
- `nursing.emergency-registration` - Register emergency patients

### OPD/Consultation
- `consultations.view-all` - View all consultations
- `consultations.view-own` - View own consultations
- `consultations.create` - Create consultation
- `consultations.edit` - Edit consultation
- `consultations.prescribe` - Write prescriptions
- `consultations.order-labs` - Order lab tests
- `consultations.admit-patient` - Admit patient to IPD

### Laboratory
- `lab.view-dashboard` - View lab dashboard
- `lab.view-orders` - View lab orders
- `lab.collect-sample` - Collect samples
- `lab.process-test` - Process lab tests
- `lab.enter-results` - Enter test results
- `lab.approve-results` - Approve and report results
- `lab.cancel-order` - Cancel lab order
- `lab.download-results` - Download results

### Radiology
- `radiology.view-dashboard` - View radiology dashboard
- `radiology.view-orders` - View imaging orders
- `radiology.process-imaging` - Process imaging
- `radiology.enter-report` - Enter radiology report
- `radiology.upload-images` - Upload imaging files
- `radiology.approve-report` - Approve and finalize report

### Pharmacy
- `pharmacy.view-dashboard` - View pharmacy dashboard
- `pharmacy.view-prescriptions` - View prescriptions
- `pharmacy.dispense` - Dispense medications
- `pharmacy.mark-emergency` - Mark as emergency
- `pharmacy.cancel-prescription` - Cancel prescription
- `pharmacy.manage-inventory` - Manage medicine inventory

### IPD Management
- `ipd.view-dashboard` - View IPD dashboard
- `ipd.view-admissions` - View admissions
- `ipd.create-admission` - Create admission
- `ipd.edit-admission` - Edit admission
- `ipd.transfer-patient` - Transfer patient
- `ipd.discharge-patient` - Discharge patient
- `wards.view` - View wards
- `wards.create` - Create ward
- `wards.edit` - Edit ward
- `wards.manage-status` - Toggle ward status
- `wards.assign-nurse` - Assign nurse to ward
- `beds.view` - View beds
- `beds.create` - Create bed
- `beds.edit` - Edit bed
- `beds.manage-status` - Manage bed status

### Billing & Accounting
- `billing.view-dashboard` - View accounting dashboard
- `billing.view-services` - View service catalog
- `billing.manage-services` - Create/edit services
- `billing.view-charges` - View patient charges
- `billing.create-charge` - Create manual charge
- `billing.view-bills` - View bills
- `billing.create-bill` - Create bill
- `billing.edit-bill` - Edit bill
- `billing.process-payment` - Process payments
- `billing.print-receipt` - Print receipts
- `billing.view-reports` - View financial reports

### User Management
- `users.view` - View users
- `users.create` - Create user
- `users.edit` - Edit user
- `users.delete` - Delete user
- `users.manage-roles` - Assign/revoke roles
- `users.manage-permissions` - Manage permissions

### System Settings
- `settings.view` - View settings
- `settings.manage-roles` - Manage roles & permissions
- `settings.view-logs` - View audit logs
- `settings.manage-system` - System configuration

---

## Recommended Role-Permission Assignment

### Super Admin
✅ **ALL PERMISSIONS**

### Admin
✅ All except:
- System-level settings (limited)
- Cannot delete users
- Cannot modify Super Admin

### Doctor
- ✅ Patients: view-details, edit (medical records)
- ✅ Appointments: view-own, start-consultation, complete
- ✅ Consultations: view-own, create, edit, prescribe, order-labs, admit-patient
- ✅ Lab: view-orders, download-results
- ✅ Pharmacy: view-prescriptions
- ✅ IPD: discharge-patient
- ✅ Billing: view-charges, view-bills

### Nurse
- ✅ Patients: view-details, edit (vitals/nursing notes)
- ✅ Appointments: view-all, checkin
- ✅ Nursing: all permissions
- ✅ Consultations: view-all (read-only)
- ✅ Lab: view-orders
- ✅ Pharmacy: view-prescriptions
- ✅ IPD: view-admissions, transfer-patient

### Receptionist
- ✅ Patients: view, create, edit
- ✅ Appointments: view-all, create, edit, cancel, checkin
- ✅ Billing: view-bills, create-bill, process-payment, print-receipt
- ✅ Limited OPD/IPD viewing

### Pharmacist
- ✅ Patients: view (basic info)
- ✅ Pharmacy: all permissions
- ✅ Billing: view-charges (for pharmacy items)

### Lab Technician
- ✅ Patients: view (basic info)
- ✅ Lab: view-dashboard, view-orders, collect-sample, process-test, enter-results

### Radiologist
- ✅ Patients: view (basic info)
- ✅ Radiology: all permissions
- ✅ Lab: approve-results (for radiology)

### Accountant
- ✅ Patients: view
- ✅ Billing: all permissions
- ✅ Reports: all financial reports
- ✅ Services: manage

---

## Next Steps

1. ✅ Expand permission list in database
2. ✅ Create Permission Seeder with all permissions
3. ✅ Create Role Management UI in System Settings
4. ✅ Add middleware to protect routes
5. ✅ Add permission checks in views (show/hide elements)
6. ✅ Create audit log for permission changes
