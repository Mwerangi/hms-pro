<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Consultation;
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
            'appointments_today' => Appointment::whereDate('appointment_date', today())->count(),
            'pending_appointments' => Appointment::whereIn('status', ['scheduled', 'waiting', 'in-consultation'])->count(),
            'completed_today' => Appointment::whereDate('appointment_date', today())->where('status', 'completed')->count(),
        ];

        // Get pending appointments that need attention (all dates)
        $pendingAppointments = Appointment::with(['patient', 'doctor'])
            ->whereIn('status', ['scheduled', 'waiting', 'in-consultation'])
            ->orderBy('appointment_date', 'asc')
            ->orderBy('appointment_time', 'asc')
            ->get();

        // Get stuck appointments (waiting without consultation for more than 1 day)
        $stuckAppointments = Appointment::with(['patient', 'doctor'])
            ->where('status', 'waiting')
            ->whereDoesntHave('consultation')
            ->where('appointment_date', '<', today())
            ->orderBy('appointment_date', 'desc')
            ->get();

        // Get old scheduled appointments (past date but not checked in)
        $missedAppointments = Appointment::with(['patient', 'doctor'])
            ->where('status', 'scheduled')
            ->where('appointment_date', '<', today())
            ->orderBy('appointment_date', 'desc')
            ->get();

        // Recent consultations
        $recentConsultations = Consultation::with(['patient', 'doctor', 'appointment'])
            ->latest()
            ->take(5)
            ->get();
        
        // Role-based dashboard views (for future customization)
        $dashboardData = [
            'user' => $user,
            'role' => $role ? $role->name : 'user',
            'stats' => $stats,
            'pendingAppointments' => $pendingAppointments,
            'stuckAppointments' => $stuckAppointments,
            'missedAppointments' => $missedAppointments,
            'recentConsultations' => $recentConsultations,
        ];
        
        return view('dashboard.index', $dashboardData);
    }
}
