# Hospital Management System - Complete Settings Specification

## Overview
This document outlines all configurable settings for the HMS to allow system administrators to customize the system according to their hospital's specific requirements and environment.

## Settings Categories & Parameters

### 1. General Settings
**Purpose:** Core hospital information and basic system configuration

| Setting | Type | Default | Description |
|---------|------|---------|-------------|
| hospital_name | string | - | Hospital/Clinic Name |
| hospital_logo | file | - | Logo image for reports/invoices |
| hospital_address | text | - | Full hospital address |
| hospital_phone | string | - | Primary contact number |
| hospital_email | email | - | Official email address |
| hospital_website | url | - | Hospital website URL |
| operating_hours_start | time | 08:00 | Daily opening time |
| operating_hours_end | time | 18:00 | Daily closing time |
| timezone | select | UTC | System timezone |
| date_format | select | Y-m-d | Date display format |
| time_format | select | 24h | Time format (12h/24h) |
| language | select | en | Default system language |
| currency | select | USD | Default currency |
| currency_symbol | string | $ | Currency symbol |
| tax_enabled | boolean | false | Enable tax calculation |
| tax_rate | decimal | 0 | Default tax rate (%) |

### 2. Patient Management Settings
**Purpose:** Configure patient registration and record management

| Setting | Type | Default | Description |
|---------|------|---------|-------------|
| patient_id_prefix | string | PAT- | Patient ID prefix |
| patient_id_format | select | auto | ID format (auto/custom) |
| patient_id_start_number | integer | 1000 | Starting number for IDs |
| require_patient_photo | boolean | false | Mandatory photo upload |
| allow_duplicate_phone | boolean | false | Allow same phone for multiple patients |
| medical_record_retention_years | integer | 7 | Years to retain records |
| show_patient_age_calculation | boolean | true | Auto-calculate age from DOB |
| require_emergency_contact | boolean | true | Mandatory emergency contact |
| enable_patient_portal | boolean | false | Patient self-service portal |
| patient_consent_required | boolean | true | Require consent forms |

### 3. Appointment Settings
**Purpose:** Configure appointment booking and scheduling

| Setting | Type | Default | Description |
|---------|------|---------|-------------|
| appointment_duration | integer | 30 | Default duration (minutes) |
| appointment_slot_interval | integer | 15 | Time slot intervals (minutes) |
| max_advance_booking_days | integer | 30 | Max days in advance to book |
| min_advance_booking_hours | integer | 2 | Minimum hours before appointment |
| allow_same_day_booking | boolean | true | Allow booking for same day |
| require_appointment_deposit | boolean | false | Require deposit on booking |
| appointment_deposit_amount | decimal | 0 | Fixed deposit amount |
| auto_confirm_appointments | boolean | false | Auto-confirm or manual |
| send_appointment_reminders | boolean | true | Send reminder notifications |
| reminder_hours_before | integer | 24 | Hours before to send reminder |
| allow_patient_cancellation | boolean | true | Patients can cancel |
| cancellation_hours_before | integer | 4 | Min hours to cancel |
| max_appointments_per_day | integer | 50 | Max appointments per doctor/day |
| overbooking_allowed | boolean | false | Allow overbooking slots |
| weekend_appointments | boolean | false | Allow weekend bookings |

### 4. Consultation (OPD) Settings
**Purpose:** Configure outpatient department operations

| Setting | Type | Default | Description |
|---------|------|---------|-------------|
| consultation_fee_required | boolean | true | Must set consultation fee |
| default_consultation_fee | decimal | 0 | Default fee amount |
| auto_create_patient_file | boolean | true | Auto-create medical file |
| require_vitals_before_consultation | boolean | true | Nurse must record vitals first |
| prescription_requires_approval | boolean | false | Require senior doctor approval |
| max_prescription_items | integer | 20 | Max items per prescription |
| prescription_validity_days | integer | 7 | Days prescription is valid |
| allow_consultation_notes_edit | boolean | false | Edit consultation after closing |
| require_diagnosis_code | boolean | false | Mandatory ICD-10 code |
| enable_consultation_templates | boolean | true | Use templates for common cases |

### 5. Laboratory Settings
**Purpose:** Configure lab operations and reporting

| Setting | Type | Default | Description |
|---------|------|---------|-------------|
| lab_order_prefix | string | LAB- | Lab order prefix |
| require_sample_collection | boolean | true | Mark sample collected |
| require_result_approval | boolean | true | Lab manager must approve |
| auto_send_results | boolean | false | Auto-send to doctor |
| result_validity_days | integer | 30 | Days results are valid |
| critical_result_notification | boolean | true | Alert for critical values |
| allow_external_lab | boolean | false | Send to external labs |
| barcode_enabled | boolean | false | Use barcode for samples |
| default_sample_type | select | blood | Default sample type |
| enable_reference_ranges | boolean | true | Show normal ranges |
| lab_report_watermark | boolean | true | Add watermark to reports |

### 6. Radiology Settings
**Purpose:** Configure imaging and radiology department

| Setting | Type | Default | Description |
|---------|------|---------|-------------|
| radiology_order_prefix | string | RAD- | Radiology order prefix |
| require_radiologist_approval | boolean | true | Radiologist must approve report |
| max_image_upload_size | integer | 10 | Max size in MB |
| supported_image_formats | array | DICOM,JPG,PNG | Allowed formats |
| auto_archive_images | boolean | false | Auto-archive old images |
| archive_after_months | integer | 12 | Months before archiving |
| enable_3d_viewer | boolean | false | 3D image viewer |
| external_pacs_integration | boolean | false | Integrate with PACS system |

### 7. Pharmacy Settings
**Purpose:** Configure pharmacy and medication management

| Setting | Type | Default | Description |
|---------|------|---------|-------------|
| pharmacy_enabled | boolean | true | Enable pharmacy module |
| stock_alert_threshold | integer | 10 | Min stock alert quantity |
| expiry_alert_days | integer | 90 | Days before expiry alert |
| allow_negative_stock | boolean | false | Dispense with negative stock |
| require_prescription_approval | boolean | true | Pharmacist verify prescription |
| auto_deduct_stock | boolean | true | Auto-deduct on dispensing |
| batch_tracking_enabled | boolean | true | Track by batch number |
| generic_substitution_allowed | boolean | false | Allow generic alternatives |
| drug_interaction_check | boolean | true | Check drug interactions |
| medication_barcode_scan | boolean | false | Scan barcode to dispense |
| markup_percentage | decimal | 20 | Default markup on cost |
| dispense_partial_quantity | boolean | true | Allow partial dispensing |

### 8. IPD (Inpatient) Settings
**Purpose:** Configure inpatient department and admissions

| Setting | Type | Default | Description |
|---------|------|---------|-------------|
| admission_prefix | string | ADM- | Admission ID prefix |
| require_admission_deposit | boolean | true | Mandatory admission deposit |
| minimum_deposit_amount | decimal | 1000 | Min deposit amount |
| auto_assign_bed | boolean | false | Auto-assign available bed |
| bed_transfer_approval | boolean | true | Require approval for transfer |
| visiting_hours_start | time | 10:00 | Visitor start time |
| visiting_hours_end | time | 18:00 | Visitor end time |
| max_visitors_per_patient | integer | 2 | Max concurrent visitors |
| discharge_clearance_required | boolean | true | All depts must clear |
| auto_generate_discharge_summary | boolean | true | Auto-create summary |
| ipd_daily_charges | boolean | true | Charge daily bed fees |

### 9. Billing & Payment Settings
**Purpose:** Configure billing, invoicing, and payments

| Setting | Type | Default | Description |
|---------|------|---------|-------------|
| invoice_prefix | string | INV- | Invoice number prefix |
| invoice_numbering | select | auto | Numbering format |
| invoice_start_number | integer | 1000 | Starting invoice number |
| invoice_footer_text | text | - | Footer text for invoices |
| payment_terms_days | integer | 0 | Payment due days (0=immediate) |
| allow_partial_payment | boolean | true | Accept partial payments |
| minimum_partial_payment | decimal | 0 | Min partial payment amount |
| credit_limit_enabled | boolean | false | Enable credit limits |
| default_credit_limit | decimal | 0 | Default credit amount |
| late_payment_penalty | decimal | 0 | Late fee percentage |
| discount_allowed | boolean | true | Allow discounts |
| max_discount_percentage | decimal | 20 | Maximum discount % |
| require_discount_approval | boolean | true | Approval for discounts |
| payment_methods | array | Cash,Card,Insurance | Enabled payment methods |
| auto_send_invoice | boolean | true | Email invoice to patient |

### 10. Insurance Settings
**Purpose:** Configure insurance and third-party billing

| Setting | Type | Default | Description |
|---------|------|---------|-------------|
| insurance_enabled | boolean | false | Enable insurance module |
| require_preauthorization | boolean | true | Require pre-auth |
| preauth_validity_days | integer | 30 | Days pre-auth is valid |
| copayment_enabled | boolean | true | Patient copayment required |
| default_copayment_percentage | decimal | 10 | Default copay % |
| auto_submit_claims | boolean | false | Auto-submit to insurance |
| claim_submission_days | integer | 7 | Days to submit claim |
| insurance_verification_required | boolean | true | Verify coverage before service |

### 11. Notification Settings
**Purpose:** Configure system notifications and alerts

| Setting | Type | Default | Description |
|---------|------|---------|-------------|
| email_notifications_enabled | boolean | true | Enable email notifications |
| sms_notifications_enabled | boolean | false | Enable SMS notifications |
| push_notifications_enabled | boolean | false | Enable push notifications |
| notify_appointment_booked | boolean | true | Notify on booking |
| notify_appointment_cancelled | boolean | true | Notify on cancellation |
| notify_lab_results_ready | boolean | true | Notify when results ready |
| notify_prescription_ready | boolean | true | Notify when meds ready |
| notify_bill_generated | boolean | true | Notify when bill created |
| notify_payment_received | boolean | true | Notify on payment |
| admin_email_alerts | boolean | true | Send admin alerts |
| critical_alerts_email | email | - | Email for critical alerts |

### 12. Email Configuration
**Purpose:** Configure email service settings

| Setting | Type | Default | Description |
|---------|------|---------|-------------|
| smtp_host | string | - | SMTP server host |
| smtp_port | integer | 587 | SMTP port |
| smtp_username | string | - | SMTP username |
| smtp_password | password | - | SMTP password |
| smtp_encryption | select | tls | Encryption (tls/ssl) |
| from_email | email | - | From email address |
| from_name | string | - | From name |
| reply_to_email | email | - | Reply-to address |

### 13. SMS Configuration
**Purpose:** Configure SMS gateway settings

| Setting | Type | Default | Description |
|---------|------|---------|-------------|
| sms_gateway | select | - | SMS provider (Twilio/etc) |
| sms_api_key | string | - | API key |
| sms_api_secret | password | - | API secret |
| sms_sender_id | string | - | Sender ID/number |
| sms_test_mode | boolean | true | Test mode enabled |

### 14. Security Settings
**Purpose:** Configure security and access control

| Setting | Type | Default | Description |
|---------|------|---------|-------------|
| enforce_password_policy | boolean | true | Enforce strong passwords |
| min_password_length | integer | 8 | Minimum password length |
| require_password_uppercase | boolean | true | Require uppercase letter |
| require_password_number | boolean | true | Require number |
| require_password_special | boolean | true | Require special character |
| password_expiry_days | integer | 90 | Days before password expires |
| max_login_attempts | integer | 5 | Max failed login attempts |
| lockout_duration_minutes | integer | 30 | Account lockout duration |
| session_timeout_minutes | integer | 60 | Idle session timeout |
| two_factor_auth_enabled | boolean | false | Enable 2FA |
| ip_whitelist_enabled | boolean | false | Enable IP whitelist |
| audit_log_enabled | boolean | true | Log user activities |

### 15. Backup & Maintenance
**Purpose:** Configure system backup and maintenance

| Setting | Type | Default | Description |
|---------|------|---------|-------------|
| auto_backup_enabled | boolean | false | Enable auto backup |
| backup_frequency | select | daily | Backup frequency |
| backup_time | time | 02:00 | Time to run backup |
| backup_retention_days | integer | 30 | Days to keep backups |
| maintenance_mode | boolean | false | System maintenance mode |
| maintenance_message | text | - | Message during maintenance |

### 16. Reporting Settings
**Purpose:** Configure reports and analytics

| Setting | Type | Default | Description |
|---------|------|---------|-------------|
| default_report_format | select | pdf | Default format (PDF/Excel) |
| include_logo_in_reports | boolean | true | Add hospital logo |
| report_footer_text | text | - | Footer text for reports |
| enable_advanced_analytics | boolean | false | Advanced analytics |
| data_export_enabled | boolean | true | Allow data export |
| export_formats | array | PDF,Excel,CSV | Allowed export formats |

### 17. Integration Settings
**Purpose:** Third-party integrations

| Setting | Type | Default | Description |
|---------|------|---------|-------------|
| payment_gateway_enabled | boolean | false | Enable payment gateway |
| payment_gateway_provider | select | - | Provider (Stripe/PayPal/etc) |
| payment_gateway_key | string | - | API key |
| payment_gateway_secret | password | - | API secret |
| payment_gateway_mode | select | test | Mode (test/live) |
| whatsapp_integration | boolean | false | Enable WhatsApp notifications |
| calendar_sync_enabled | boolean | false | Sync with Google Calendar |

### 18. Roles & Permissions
**Purpose:** Manage user roles and access control

| Setting | Type | Description |
|---------|------|-------------|
| role_management | link | Manage system roles and permissions |
| view_all_roles | link | View all available roles |
| create_custom_role | link | Create new custom role |
| assign_permissions | link | Assign permissions to roles |
| manage_user_roles | link | Assign roles to users |

## Implementation Strategy

### Database Design
- Create `settings` table with key-value pairs
- Category field for grouping
- Data type field for validation
- Encrypted field for sensitive data (passwords, API keys)

### UI Design
- Sidebar navigation with category icons
- Tab-based interface for sub-categories
- Real-time validation and save feedback
- Secure password fields with show/hide toggle
- File upload for logo and images
- Color picker for theme customization

### Security Considerations
- Only Super Admin and Admin roles can access settings
- Audit log for all setting changes
- Encrypt sensitive settings (passwords, API keys)
- Validate all inputs server-side
- Require confirmation for critical changes

### Default Values
- Provide sensible defaults for all settings
- Allow reset to default functionality
- Import/Export settings for backup

## Total Settings Count
- **18 Categories**
- **180+ Individual Settings**
- **Comprehensive Configuration Coverage**
