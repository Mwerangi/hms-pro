<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Consultation;
use App\Models\Prescription;
use App\Models\Medicine;
use App\Models\LabOrder;
use App\Models\Admission;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
    /**
     * Display the dashboard based on user role
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get user's first role
        $role = $user->roles->first();
        
        // Dashboard statistics
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'new_users_today' => User::whereDate('created_at', today())->count(),
            'new_users_week' => User::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'roles_count' => Role::count(),
            
            // Real patient and appointment data
            'total_patients' => Patient::count(),
            'active_patients' => Patient::where('is_active', true)->count(),
        ];

        // Appointments statistics - filtered by doctor role
        $appointmentsTodayQuery = Appointment::whereDate('appointment_date', today());
        $pendingAppointmentsQuery = Appointment::whereIn('status', ['scheduled', 'waiting', 'in-consultation']);
        $completedTodayQuery = Appointment::whereDate('appointment_date', today())->where('status', 'completed');

        if ($user->hasRole('Doctor')) {
            $appointmentsTodayQuery->where(function($q) use ($user) {
                $q->where('doctor_id', $user->id)->orWhereNull('doctor_id');
            });
            $pendingAppointmentsQuery->where(function($q) use ($user) {
                $q->where('doctor_id', $user->id)->orWhereNull('doctor_id');
            });
            $completedTodayQuery->where(function($q) use ($user) {
                $q->where('doctor_id', $user->id)->orWhereNull('doctor_id');
            });
        }

        $stats['appointments_today'] = $appointmentsTodayQuery->count();
        $stats['pending_appointments'] = $pendingAppointmentsQuery->count();
        $stats['completed_today'] = $completedTodayQuery->count();

        // Load role-specific data
        $this->loadDoctorData($user, $stats);
        $this->loadNurseData($user, $stats);
        $lowStockMedicines = $this->loadPharmacistData($user, $stats);
        $this->loadReceptionistData($user, $stats);
        $this->loadLabTechnicianData($user, $stats);
        $this->loadAdminData($user, $stats);

        // Get pending appointments that need attention (all dates)
        $pendingAppointments = Appointment::with(['patient', 'doctor'])
            ->whereIn('status', ['scheduled', 'waiting', 'in-consultation']);
        
        // Filter by doctor role
        if ($user->hasRole('Doctor')) {
            $pendingAppointments->where(function($q) use ($user) {
                $q->where('doctor_id', $user->id) // Assigned to this doctor
                  ->orWhereNull('doctor_id'); // OR unassigned
            });
        }
        
        $pendingAppointments = $pendingAppointments->orderBy('appointment_date', 'asc')
            ->orderBy('appointment_time', 'asc')
            ->get();

        // Get stuck appointments (waiting without consultation for more than 1 day)
        $stuckAppointments = Appointment::with(['patient', 'doctor'])
            ->where('status', 'waiting')
            ->whereDoesntHave('consultation')
            ->where('appointment_date', '<', today());
        
        // Filter by doctor role
        if ($user->hasRole('Doctor')) {
            $stuckAppointments->where(function($q) use ($user) {
                $q->where('doctor_id', $user->id)
                  ->orWhereNull('doctor_id');
            });
        }
        
        $stuckAppointments = $stuckAppointments->orderBy('appointment_date', 'desc')
            ->get();

        // Get old scheduled appointments (past date but not checked in)
        $missedAppointments = Appointment::with(['patient', 'doctor'])
            ->where('status', 'scheduled')
            ->where('appointment_date', '<', today());
        
        // Filter by doctor role
        if ($user->hasRole('Doctor')) {
            $missedAppointments->where(function($q) use ($user) {
                $q->where('doctor_id', $user->id)
                  ->orWhereNull('doctor_id');
            });
        }
        
        $missedAppointments = $missedAppointments->orderBy('appointment_date', 'desc')
            ->get();

        // Recent consultations
        $recentConsultations = Consultation::with(['patient', 'doctor', 'appointment'])
            ->latest();
        
        // Filter by doctor role
        if ($user->hasRole('Doctor')) {
            $recentConsultations->where('doctor_id', $user->id);
        }
        
        $recentConsultations = $recentConsultations->take(5)->get();
        
        // Role-based dashboard views (for future customization)
        $dashboardData = [
            'user' => $user,
            'role' => $role ? $role->name : 'user',
            'stats' => $stats,
            'pendingAppointments' => $pendingAppointments,
            'stuckAppointments' => $stuckAppointments,
            'missedAppointments' => $missedAppointments,
            'recentConsultations' => $recentConsultations,
            'lowStockMedicines' => $lowStockMedicines ?? collect([]),
        ];
        
        return view('dashboard.index', $dashboardData);
    }

    /**
     * Load doctor-specific data
     */
    private function loadDoctorData($user, &$stats)
    {
        if (!$user->hasRole('Doctor')) {
            return;
        }

        // Doctor's total patients (consultations)
        $stats['doctor_patients'] = Consultation::where('doctor_id', $user->id)
            ->distinct('patient_id')
            ->count('patient_id');
    }

    /**
     * Load nurse-specific data
     */
    private function loadNurseData($user, &$stats)
    {
        if (!$user->hasRole('Nurse')) {
            return;
        }

        // Scheduled appointments (not checked in yet)
        $stats['scheduled_appointments'] = Appointment::whereDate('appointment_date', today())
            ->where('status', 'scheduled')
            ->count();

        // Waiting appointments (checked in, awaiting vitals)
        $stats['waiting_appointments'] = Appointment::whereDate('appointment_date', today())
            ->where('status', 'waiting')
            ->count();

        // Admitted patients (IPD)
        $stats['admitted_patients'] = Admission::where('discharge_date', null)->count();

        // In consultation count
        $stats['in_consultation'] = Appointment::whereDate('appointment_date', today())
            ->where('status', 'in-consultation')
            ->count();
    }

    /**
     * Load pharmacist-specific data
     */
    private function loadPharmacistData($user, &$stats)
    {
        if (!$user->hasRole('Pharmacist') && !$user->can('pharmacy.view-dashboard')) {
            return collect([]);
        }

        // Pending prescriptions
        $stats['pending_prescriptions'] = Prescription::where('status', 'pending')->count();

        // Dispensed today
        $stats['dispensed_today'] = Prescription::whereDate('dispensed_at', today())
            ->where('status', 'dispensed')
            ->count();

        // Low stock medicines
        $stats['low_stock_items'] = Medicine::whereColumn('current_stock', '<=', 'reorder_level')->count();

        // Total medicines
        $stats['total_medicines'] = Medicine::count();

        // Return low stock medicines for widget
        return Medicine::whereColumn('current_stock', '<=', 'reorder_level')
            ->orderBy('current_stock', 'asc')
            ->get();
    }

    /**
     * Load receptionist-specific data
     */
    private function loadReceptionistData($user, &$stats)
    {
        if (!$user->hasRole('Receptionist')) {
            return;
        }

        // Checked in today
        $stats['checkedin_today'] = Appointment::whereDate('appointment_date', today())
            ->whereIn('status', ['waiting', 'in-consultation', 'completed'])
            ->count();

        // Scheduled (not checked in)
        $stats['scheduled_appointments'] = Appointment::whereDate('appointment_date', today())
            ->where('status', 'scheduled')
            ->count();

        // In consultation
        $stats['in_consultation'] = Appointment::whereDate('appointment_date', today())
            ->where('status', 'in-consultation')
            ->count();

        // Pending bills (if billing module exists)
        $stats['pending_bills'] = 0; // TODO: Implement when billing module is ready
    }

    /**
     * Load lab technician-specific data
     */
    private function loadLabTechnicianData($user, &$stats)
    {
        if (!$user->hasRole('Lab Technician') && !$user->can('lab.view-dashboard')) {
            return;
        }

        // Pending lab tests
        $stats['pending_lab_tests'] = LabOrder::where('status', 'pending')->count();

        // Processing tests
        $stats['processing_tests'] = LabOrder::where('status', 'sample-collected')->count();

        // Completed today
        $stats['completed_tests_today'] = LabOrder::whereDate('completed_at', today())
            ->where('status', 'completed')
            ->count();

        // Total tests
        $stats['total_lab_tests'] = LabOrder::count();

        // Priority tests
        $stats['urgent_tests'] = LabOrder::where('status', 'pending')
            ->where('priority', 'urgent')
            ->count();
        $stats['high_priority_tests'] = LabOrder::where('status', 'pending')
            ->where('priority', 'high')
            ->count();
        $stats['normal_priority_tests'] = LabOrder::where('status', 'pending')
            ->where('priority', 'normal')
            ->count();
    }

    /**
     * Load admin-specific data
     */
    private function loadAdminData($user, &$stats)
    {
        if (!$user->hasAnyRole(['Admin', 'Super Admin'])) {
            return;
        }

        // New patients today
        $stats['new_patients_today'] = Patient::whereDate('created_at', today())->count();

        // Admitted patients
        $stats['admitted_patients'] = Admission::where('discharge_date', null)->count();

        // Lab orders count
        $stats['lab_orders'] = LabOrder::whereDate('created_at', today())->count();
    }
}
