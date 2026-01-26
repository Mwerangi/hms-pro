<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class DefaultSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // General Settings
            ['key' => 'hospital_name', 'value' => 'General Hospital', 'category' => 'general', 'type' => 'string', 'description' => 'Hospital/Clinic Name', 'sort_order' => 1],
            ['key' => 'hospital_address', 'value' => '', 'category' => 'general', 'type' => 'text', 'description' => 'Full hospital address', 'sort_order' => 2],
            ['key' => 'hospital_phone', 'value' => '', 'category' => 'general', 'type' => 'string', 'description' => 'Primary contact number', 'sort_order' => 3],
            ['key' => 'hospital_email', 'value' => '', 'category' => 'general', 'type' => 'email', 'description' => 'Official email address', 'sort_order' => 4],
            ['key' => 'timezone', 'value' => 'UTC', 'category' => 'general', 'type' => 'select', 'description' => 'System timezone', 'sort_order' => 5],
            ['key' => 'date_format', 'value' => 'Y-m-d', 'category' => 'general', 'type' => 'select', 'description' => 'Date display format', 'sort_order' => 6],
            ['key' => 'currency', 'value' => 'USD', 'category' => 'general', 'type' => 'select', 'description' => 'Default currency', 'sort_order' => 7],
            ['key' => 'currency_symbol', 'value' => '$', 'category' => 'general', 'type' => 'string', 'description' => 'Currency symbol', 'sort_order' => 8],
            
            // Patient Management
            ['key' => 'patient_id_prefix', 'value' => 'PAT-', 'category' => 'patient', 'type' => 'string', 'description' => 'Patient ID prefix', 'sort_order' => 1],
            ['key' => 'patient_id_start_number', 'value' => '1000', 'category' => 'patient', 'type' => 'integer', 'description' => 'Starting number for patient IDs', 'sort_order' => 2],
            ['key' => 'require_patient_photo', 'value' => '0', 'category' => 'patient', 'type' => 'boolean', 'description' => 'Mandatory photo upload', 'sort_order' => 3],
            ['key' => 'require_emergency_contact', 'value' => '1', 'category' => 'patient', 'type' => 'boolean', 'description' => 'Require emergency contact', 'sort_order' => 4],
            
            // Appointment Settings
            ['key' => 'appointment_duration', 'value' => '30', 'category' => 'appointment', 'type' => 'integer', 'description' => 'Default duration in minutes', 'sort_order' => 1],
            ['key' => 'max_advance_booking_days', 'value' => '30', 'category' => 'appointment', 'type' => 'integer', 'description' => 'Max days in advance to book', 'sort_order' => 2],
            ['key' => 'allow_same_day_booking', 'value' => '1', 'category' => 'appointment', 'type' => 'boolean', 'description' => 'Allow booking for same day', 'sort_order' => 3],
            ['key' => 'send_appointment_reminders', 'value' => '1', 'category' => 'appointment', 'type' => 'boolean', 'description' => 'Send reminder notifications', 'sort_order' => 4],
            ['key' => 'reminder_hours_before', 'value' => '24', 'category' => 'appointment', 'type' => 'integer', 'description' => 'Hours before to send reminder', 'sort_order' => 5],
            
            // Billing Settings
            ['key' => 'invoice_prefix', 'value' => 'INV-', 'category' => 'billing', 'type' => 'string', 'description' => 'Invoice number prefix', 'sort_order' => 1],
            ['key' => 'invoice_start_number', 'value' => '1000', 'category' => 'billing', 'type' => 'integer', 'description' => 'Starting invoice number', 'sort_order' => 2],
            ['key' => 'tax_enabled', 'value' => '0', 'category' => 'billing', 'type' => 'boolean', 'description' => 'Enable tax calculation', 'sort_order' => 3],
            ['key' => 'tax_rate', 'value' => '0', 'category' => 'billing', 'type' => 'decimal', 'description' => 'Default tax rate (%)', 'sort_order' => 4],
            ['key' => 'allow_partial_payment', 'value' => '1', 'category' => 'billing', 'type' => 'boolean', 'description' => 'Accept partial payments', 'sort_order' => 5],
            ['key' => 'discount_allowed', 'value' => '1', 'category' => 'billing', 'type' => 'boolean', 'description' => 'Allow discounts', 'sort_order' => 6],
            
            // Pharmacy Settings
            ['key' => 'stock_alert_threshold', 'value' => '10', 'category' => 'pharmacy', 'type' => 'integer', 'description' => 'Min stock alert quantity', 'sort_order' => 1],
            ['key' => 'expiry_alert_days', 'value' => '90', 'category' => 'pharmacy', 'type' => 'integer', 'description' => 'Days before expiry alert', 'sort_order' => 2],
            ['key' => 'auto_deduct_stock', 'value' => '1', 'category' => 'pharmacy', 'type' => 'boolean', 'description' => 'Auto-deduct on dispensing', 'sort_order' => 3],
            ['key' => 'markup_percentage', 'value' => '20', 'category' => 'pharmacy', 'type' => 'decimal', 'description' => 'Default markup on cost', 'sort_order' => 4],
            
            // Laboratory Settings
            ['key' => 'lab_order_prefix', 'value' => 'LAB-', 'category' => 'laboratory', 'type' => 'string', 'description' => 'Lab order prefix', 'sort_order' => 1],
            ['key' => 'require_result_approval', 'value' => '1', 'category' => 'laboratory', 'type' => 'boolean', 'description' => 'Lab manager must approve', 'sort_order' => 2],
            ['key' => 'critical_result_notification', 'value' => '1', 'category' => 'laboratory', 'type' => 'boolean', 'description' => 'Alert for critical values', 'sort_order' => 3],
            
            // Notification Settings
            ['key' => 'email_notifications_enabled', 'value' => '1', 'category' => 'notifications', 'type' => 'boolean', 'description' => 'Enable email notifications', 'sort_order' => 1],
            ['key' => 'sms_notifications_enabled', 'value' => '0', 'category' => 'notifications', 'type' => 'boolean', 'description' => 'Enable SMS notifications', 'sort_order' => 2],
            ['key' => 'notify_appointment_booked', 'value' => '1', 'category' => 'notifications', 'type' => 'boolean', 'description' => 'Notify on booking', 'sort_order' => 3],
            ['key' => 'notify_lab_results_ready', 'value' => '1', 'category' => 'notifications', 'type' => 'boolean', 'description' => 'Notify when results ready', 'sort_order' => 4],
            
            // Security Settings
            ['key' => 'enforce_password_policy', 'value' => '1', 'category' => 'security', 'type' => 'boolean', 'description' => 'Enforce strong passwords', 'sort_order' => 1],
            ['key' => 'min_password_length', 'value' => '8', 'category' => 'security', 'type' => 'integer', 'description' => 'Minimum password length', 'sort_order' => 2],
            ['key' => 'max_login_attempts', 'value' => '5', 'category' => 'security', 'type' => 'integer', 'description' => 'Max failed login attempts', 'sort_order' => 3],
            ['key' => 'session_timeout_minutes', 'value' => '60', 'category' => 'security', 'type' => 'integer', 'description' => 'Idle session timeout', 'sort_order' => 4],
            ['key' => 'two_factor_auth_enabled', 'value' => '0', 'category' => 'security', 'type' => 'boolean', 'description' => 'Enable 2FA', 'sort_order' => 5],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }

        $this->command->info('Default settings created successfully!');
    }
}
