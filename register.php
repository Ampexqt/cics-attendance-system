<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Registration - CICS Attendance System</title>
  <!-- CSS Variables -->
  <link rel="stylesheet" href="frontend/assets/css/base/variables.css">
  <!-- Component Styles -->
  <link rel="stylesheet" href="frontend/assets/css/components/forms.css">
  <link rel="stylesheet" href="frontend/assets/css/components/buttons.css">
  <link rel="stylesheet" href="frontend/assets/css/components/modals.css">
  <link rel="stylesheet" href="frontend/assets/css/components/toast.css">
  <!-- Page Styles -->
  <link rel="stylesheet" href="frontend/assets/css/pages/auth.css">
  <link rel="stylesheet" href="frontend/assets/css/main.css">
  <!-- Device Fingerprinting -->
  <script src="frontend/assets/js/fingerprint.js"></script>
</head>

<body>
  <div class="auth-container">
    <div class="auth-card" style="max-width: 48rem;">
      <div class="auth-header">
        <div class="auth-logos">
          <img src="https://uploadthingy.s3.us-west-1.amazonaws.com/qHYtTa1uNrpFjc66NgGcuM/ZPPUS-CICS_LOGO.jpg" alt="CICS Logo" class="auth-logo">
          <img src="https://uploadthingy.s3.us-west-1.amazonaws.com/h5rtnYfu5NzN7nEjkomYz5/ZPPSU-LOGO.jpg" alt="ZPPSU Logo" class="auth-logo">
        </div>
        <h1 class="auth-title">Create Your Account</h1>
        <p class="auth-subtitle">CICS Attendance System Registration</p>
      </div>
      <div class="auth-body">
        <form class="auth-form" id="registerForm" method="POST" action="#" enctype="multipart/form-data">
          <!-- Student Information -->
          <div class="form-section">
            <h3 class="form-section-title">Student Information</h3>
            <div class="form-row">
              <div class="form-group">
                <label for="firstName" class="form-label required">First Name</label>
                <input type="text" name="firstName" id="firstName" class="form-input" placeholder="Enter your first name" required>
              </div>
              <div class="form-group">
                <label for="lastName" class="form-label required">Last Name</label>
                <input type="text" name="lastName" id="lastName" class="form-input" placeholder="Enter your last name" required>
              </div>
            </div>
            <div class="form-group">
              <label for="studentId" class="form-label required">Student ID / Number</label>
              <input type="text" name="studentId" id="studentId" class="form-input" placeholder="Enter your student ID" required>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label for="yearLevel" class="form-label required">Year Level</label>
                <select name="yearLevel" id="yearLevel" class="form-select" required>
                  <option value="">Select your year level</option>
                  <option value="1">1st Year</option>
                  <option value="2">2nd Year</option>
                  <option value="3">3rd Year</option>
                  <option value="4">4th Year</option>
                </select>
              </div>
              <div class="form-group">
                <label for="section" class="form-label required">Section</label>
                <select name="section" id="section" class="form-select" required>
                  <option value="">Select your section</option>
                  <option value="A">A</option>
                  <option value="B">B</option>
                  <option value="C">C</option>
                  <option value="D">D</option>
                  <option value="E">E</option>
                  <option value="F">F</option>
                  <option value="G">G</option>
                  <option value="H">H</option>
                </select>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label for="program" class="form-label required">Program / Course</label>
                <select name="program" id="program" class="form-select" required>
                  <option value="">Select your program</option>
                  <option value="BSCS">Bachelor of Science in Computer Science</option>
                  <option value="BSIT">Bachelor of Science in Information Technology</option>
                  <option value="BSIS">Bachelor of Science in Information Systems</option>
                </select>
              </div>
              <div class="form-group">
                <!-- Empty for layout balance -->
              </div>
            </div>
            <div class="form-group">
              <label for="email" class="form-label required">Email</label>
              <input type="email" name="email" id="email" class="form-input" placeholder="Enter your email address" required>
            </div>
          </div>

          <!-- Password Creation -->
          <div class="form-section">
            <h3 class="form-section-title">Password Creation</h3>
            <div class="form-row">
              <div class="form-group">
                <label for="password" class="form-label required">Password</label>
                <div class="input-group">
                  <input type="password" name="password" id="password" class="form-input" placeholder="Enter password (min. 8 characters)" style="text-align: left !important;" required>
                  <button type="button" class="password-toggle">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                      <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                  </button>
                </div>
              </div>
              <div class="form-group">
                <label for="confirmPassword" class="form-label required">Confirm Password</label>
                <div class="input-group">
                  <input type="password" name="confirmPassword" id="confirmPassword" class="form-input" placeholder="Confirm your password" data-confirm="password" style="text-align: left !important;" required>
                  <button type="button" class="password-toggle">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                      <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Parent/Guardian Information -->
          <div class="form-section">
            <h3 class="form-section-title">Parent / Guardian Information</h3>
            <div class="form-row">
              <div class="form-group">
                <label for="parentFirstName" class="form-label required">First Name</label>
                <input type="text" name="parentFirstName" id="parentFirstName" class="form-input" placeholder="Enter parent's first name" required>
              </div>
              <div class="form-group">
                <label for="parentLastName" class="form-label required">Last Name</label>
                <input type="text" name="parentLastName" id="parentLastName" class="form-input" placeholder="Enter parent's last name" required>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label for="parentEmail" class="form-label required">Parent/Guardian Email</label>
                <input type="email" name="parentEmail" id="parentEmail" class="form-input" placeholder="Enter parent's email address" required>
              </div>
              <div class="form-group">
                <label for="parentContact" class="form-label required">Parent/Guardian Contact Number</label>
                <input type="tel" name="parentContact" id="parentContact" class="form-input" placeholder="Enter parent's contact number" required>
              </div>
            </div>
            <div class="form-group">
              <label for="relationship" class="form-label required">Relationship to Student</label>
              <select name="relationship" id="relationship" class="form-select" required>
                <option value="">Select relationship</option>
                <option value="father">Father</option>
                <option value="mother">Mother</option>
                <option value="guardian">Guardian</option>
                <option value="other">Other</option>
              </select>
            </div>
          </div>

          <!-- Hidden field for device fingerprint -->
          <input type="hidden" name="deviceFingerprint" id="deviceFingerprint" value="">

          <button type="submit" class="btn btn-primary btn-block" id="submitBtn">Sign Up</button>
          <p class="text-center text-sm mt-3" style="color: var(--text-secondary); font-size: var(--font-size-sm);">
            <strong>Important:</strong> Your device will be registered during signup. Only this device will be allowed to login to your account for security purposes.
          </p>
          <p class="text-center text-sm mt-2" style="color: var(--text-secondary); font-size: var(--font-size-sm);">
            After submitting your registration, please wait for admin approval before you can access the system.
          </p>
        </form>
      </div>
      <div class="auth-footer">
        <p>Already have an account? <a href="login.php">Login here</a></p>
        <p class="mt-2" style="font-size: var(--font-size-xs); color: var(--text-muted);">
          © 2025 Zamboanga Peninsula Polytechnic State University<br>
          support@zppsu.edu.ph
        </p>
      </div>
    </div>
  </div>

  <!-- Success Modal -->
  <div class="modal-backdrop" id="successModal">
    <div class="modal modal-sm">
      <div class="modal-body modal-success">
        <div class="modal-success-icon">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
        <h2 class="modal-success-title">Registration Submitted!</h2>
        <p class="modal-success-message">
          Thank you for registering. Your account is pending approval from the administrator. You will be notified once your account has been approved.
        </p>
        <div style="display: flex; gap: var(--spacing-md);">
          <button type="button" class="btn btn-secondary" onclick=\"window.location.href='./index.php'\">Return Home</button>
          <button type="button" class="btn btn-primary" onclick="window.location.href='login.php'">Go to Login</button>
        </div>
      </div>
    </div>
  </div>

  <script src="frontend/assets/js/global.js"></script>
  <script>
    let deviceFingerprintValue = '';

    // Generate device fingerprint on page load
    document.addEventListener('DOMContentLoaded', async function() {
      try {
        // Show loading state
        const submitBtn = document.getElementById('submitBtn');
        const originalText = submitBtn.textContent;
        submitBtn.disabled = true;
        submitBtn.textContent = 'Initializing...';

        // Generate fingerprint
        deviceFingerprintValue = await DeviceFingerprint.generate();
        document.getElementById('deviceFingerprint').value = deviceFingerprintValue;

        // Re-enable submit button
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
      } catch (error) {
        Toast.error('Failed to initialize device fingerprint. Please refresh the page.', 'Error');
      }
    });

    // Handle form submission
    document.getElementById('registerForm').addEventListener('submit', async function(e) {
      e.preventDefault();

      if (!FormValidator.validate(this)) {
        return;
      }

      // Ensure device fingerprint is set
      if (!deviceFingerprintValue) {
        Toast.error('Device fingerprint not generated. Please refresh the page.', 'Error');
        return;
      }

      // Prepare form data
      const yearLevel = document.getElementById('yearLevel').value;
      const section = document.getElementById('section').value;
      const combinedSection = yearLevel + section; // Combine to format like "1A", "2B", etc.

      const formData = {
        email: document.getElementById('email').value,
        password: document.getElementById('password').value,
        confirmPassword: document.getElementById('confirmPassword').value,
        firstName: document.getElementById('firstName').value,
        lastName: document.getElementById('lastName').value,
        studentId: document.getElementById('studentId').value,
        program: document.getElementById('program').value,
        section: combinedSection,
        parentFirstName: document.getElementById('parentFirstName').value,
        parentLastName: document.getElementById('parentLastName').value,
        parentEmail: document.getElementById('parentEmail').value,
        parentContact: document.getElementById('parentContact').value,
        relationship: document.getElementById('relationship').value,
        deviceFingerprint: deviceFingerprintValue
      };

      try {
        const response = await fetch('/cics-attendance-system/backend/api/auth/register', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify(formData)
        });

        const data = await response.json();

        if (response.ok && data.success) {
          Toast.success(data.message || 'Registration submitted successfully!', 'Success');
          setTimeout(() => {
            Modal.open('successModal');
          }, 500);
        } else {
          Toast.error(data.message || 'Registration failed. Please try again.', 'Error');
        }
      } catch (error) {
        Toast.error('An error occurred. Please try again later.', 'Error');
      }
    });
  </script>
</body>

</html>
