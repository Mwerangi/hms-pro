<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Login - HMS Pro</title>
  <meta name="description" content="Login to Hospital Management System">
  <meta name="keywords" content="hospital, management, login">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Favicons -->
  <link href="{{ asset('theme/assets/img/favicon.png') }}" rel="icon">
  <link href="{{ asset('theme/assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700&family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{ asset('theme/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('theme/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('theme/assets/vendor/aos/aos.css') }}" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="{{ asset('theme/assets/css/main.css') }}" rel="stylesheet">
  <link href="{{ asset('theme/assets/css/microfinance.css') }}" rel="stylesheet">
</head>

<body class="login-page">

  <div class="login-container">
    <div class="row g-0">
      <!-- Left Side - Login Form -->
      <div class="col-lg-5 col-md-6">
        <div class="login-form-wrapper">
          <div class="login-header">
            <div class="logo-section">
              <i class="bi bi-hospital"></i>
              <h1>HMS Pro</h1>
            </div>
            <h2>Welcome Back</h2>
            <p>Login to access your hospital dashboard</p>
          </div>

          @if ($errors->any())
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            {{ $errors->first() }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
          @endif

          <form class="login-form" method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group mb-3">
              <label for="email" class="form-label">Email Address</label>
              <div class="input-icon">
                <i class="bi bi-envelope"></i>
                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                       id="email" name="email" value="{{ old('email') }}" 
                       placeholder="admin@hms.com" required autofocus>
              </div>
              @error('email')
              <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>

            <div class="form-group mb-3">
              <label for="password" class="form-label">Password</label>
              <div class="input-icon">
                <i class="bi bi-lock"></i>
                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                       id="password" name="password" placeholder="Enter your password" required>
                <span class="toggle-password" onclick="togglePassword()">
                  <i class="bi bi-eye" id="toggleIcon"></i>
                </span>
              </div>
              @error('password')
              <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>

            <div class="form-options mb-4">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="remember" name="remember">
                <label class="form-check-label" for="remember">
                  Remember me
                </label>
              </div>
              <a href="#" class="forgot-password">Forgot Password?</a>
            </div>

            <button type="submit" class="btn btn-primary w-100 mb-3">
              <i class="bi bi-box-arrow-in-right me-2"></i>Login
            </button>

            <div class="divider">
              <span>OR</span>
            </div>

            <div class="demo-credentials">
              <p class="text-center mb-2"><strong>Demo Credentials:</strong></p>
              <div class="credential-box">
                <small><strong>Admin:</strong> admin@hms.com / admin123</small>
              </div>
              <div class="credential-box">
                <small><strong>Doctor:</strong> doctor@hms.com / doctor123</small>
              </div>
              <div class="credential-box">
                <small><strong>Receptionist:</strong> receptionist@hms.com / receptionist123</small>
              </div>
            </div>
          </form>
        </div>
      </div>

      <!-- Right Side - Info Section -->
      <div class="col-lg-7 col-md-6 d-none d-md-block">
        <div class="login-visual">
          <div class="visual-overlay">
            <div class="visual-content">
              <h3>Complete Hospital Management Solution</h3>
              <p>Streamline your healthcare operations from patient registration to discharge</p>
              <div class="feature-list">
                <div class="feature-item">
                  <i class="bi bi-check-circle-fill"></i>
                  <span>Patient Management</span>
                </div>
                <div class="feature-item">
                  <i class="bi bi-check-circle-fill"></i>
                  <span>Appointment Scheduling</span>
                </div>
                <div class="feature-item">
                  <i class="bi bi-check-circle-fill"></i>
                  <span>Electronic Medical Records</span>
                </div>
                <div class="feature-item">
                  <i class="bi bi-check-circle-fill"></i>
                  <span>Billing & Invoicing</span>
                </div>
                <div class="feature-item">
                  <i class="bi bi-check-circle-fill"></i>
                  <span>Pharmacy & Lab Integration</span>
                </div>
              </div>
              <div class="stats-preview">
                <div class="stat-box">
                  <h4>5,000+</h4>
                  <p>Active Patients</p>
                </div>
                <div class="stat-box">
                  <h4>150+</h4>
                  <p>Medical Staff</p>
                </div>
                <div class="stat-box">
                  <h4>99.2%</h4>
                  <p>Satisfaction Rate</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Vendor JS Files -->
  <script src="{{ asset('theme/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('theme/assets/vendor/aos/aos.js') }}"></script>

  <script>
    // Initialize AOS
    AOS.init({
      duration: 600,
      easing: 'ease-in-out',
      once: true
    });

    // Toggle password visibility
    function togglePassword() {
      const passwordInput = document.getElementById('password');
      const toggleIcon = document.getElementById('toggleIcon');
      
      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('bi-eye');
        toggleIcon.classList.add('bi-eye-slash');
      } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('bi-eye-slash');
        toggleIcon.classList.add('bi-eye');
      }
    }
  </script>

</body>
</html>
