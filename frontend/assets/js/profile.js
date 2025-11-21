/**
 * Profile Page JavaScript
 * Handles device fingerprint display and modal interactions
 */

document.addEventListener('DOMContentLoaded', () => {
  // Check authentication
  if (typeof AuthAPI !== 'undefined' && !AuthAPI.isAuthenticated()) {
    window.location.href = '/cics-attendance-system/login.php';
    return;
  }
  // Get device fingerprint on page load
  const deviceFingerprintElement = document.getElementById('deviceFingerprint');
  const currentDeviceFingerprintElement = document.getElementById('currentDeviceFingerprint');
  
  // Device Change Modal Elements
  const requestDeviceChangeBtn = document.getElementById('requestDeviceChangeBtn');
  const deviceChangeModal = document.getElementById('deviceChangeModal');
  const closeDeviceChangeModalBtn = document.getElementById('closeDeviceChangeModal');
  const cancelDeviceChangeBtn = document.getElementById('cancelDeviceChangeBtn');
  const submitDeviceChangeBtn = document.getElementById('submitDeviceChangeBtn');
  const reasonTextarea = document.getElementById('reason');

  // Logout Modal Elements
  const logoutBtn = document.getElementById('logoutBtn');
  const logoutModal = document.getElementById('logoutModal');
  const closeLogoutModalBtn = document.getElementById('closeLogoutModal');
  const cancelLogoutBtn = document.getElementById('cancelLogoutBtn');
  const confirmLogoutBtn = document.getElementById('confirmLogoutBtn');

  // Load and display device fingerprint
  async function loadDeviceFingerprint() {
    try {
      // Try to get device fingerprint from user data first
      const user = AuthAPI.getUser();
      let fingerprint = null;
      
      if (user && user.device_fingerprint) {
        fingerprint = user.device_fingerprint;
      } else if (typeof DeviceFingerprint !== 'undefined') {
        // Generate current device fingerprint
        fingerprint = await DeviceFingerprint.generate();
      }
      
      if (fingerprint) {
        // Format fingerprint for display (first 12 characters)
        const displayFingerprint = fingerprint.substring(0, 12).toUpperCase().replace(/(.{3})/g, '$1-').replace(/-$/, '');
        
        if (deviceFingerprintElement) {
          deviceFingerprintElement.textContent = displayFingerprint;
        }
        
        if (currentDeviceFingerprintElement) {
          currentDeviceFingerprintElement.textContent = displayFingerprint;
        }
      } else {
        // Show error if fingerprint cannot be loaded
        if (deviceFingerprintElement) {
          deviceFingerprintElement.textContent = 'Not available';
        }
        if (currentDeviceFingerprintElement) {
          currentDeviceFingerprintElement.textContent = 'Not available';
        }
      }
    } catch (error) {
      console.error('Error loading device fingerprint:', error);
      if (deviceFingerprintElement) {
        deviceFingerprintElement.textContent = 'Error loading';
      }
      if (currentDeviceFingerprintElement) {
        currentDeviceFingerprintElement.textContent = 'Error loading';
      }
    }
  }

  // Open Device Change Modal
  function openDeviceChangeModal() {
    if (deviceChangeModal) {
      deviceChangeModal.classList.add('show');
      document.body.style.overflow = 'hidden';
      // Focus on reason textarea after modal opens
      setTimeout(() => {
        if (reasonTextarea) {
          reasonTextarea.focus();
        }
      }, 100);
    }
  }

  // Close Device Change Modal
  function closeDeviceChangeModal() {
    if (deviceChangeModal) {
      deviceChangeModal.classList.remove('show');
      document.body.style.overflow = '';
      // Reset form
      if (reasonTextarea) {
        reasonTextarea.value = '';
        reasonTextarea.classList.remove('error');
      }
      // Clear any error messages
      const existingError = reasonTextarea?.closest('.form-group')?.querySelector('.form-error');
      if (existingError) {
        existingError.remove();
      }
    }
  }

  // Open Logout Modal
  function openLogoutModal() {
    if (logoutModal) {
      logoutModal.classList.add('show');
      document.body.style.overflow = 'hidden';
    }
  }

  // Close Logout Modal
  function closeLogoutModal() {
    if (logoutModal) {
      logoutModal.classList.remove('show');
      document.body.style.overflow = '';
    }
  }

  // Handle Device Change Request
  async function handleDeviceChangeSubmit() {
    const reason = reasonTextarea?.value.trim();

    // Validate reason
    if (!reason || reason.length < 10) {
      if (reasonTextarea) {
        reasonTextarea.classList.add('error');
        const formGroup = reasonTextarea.closest('.form-group');
        const existingError = formGroup?.querySelector('.form-error');
        
        if (!existingError) {
          const errorDiv = document.createElement('div');
          errorDiv.className = 'form-error';
          errorDiv.textContent = 'Please provide a reason (at least 10 characters)';
          formGroup?.appendChild(errorDiv);
        }
      }
      return;
    }

    // Clear any existing errors
    if (reasonTextarea) {
      reasonTextarea.classList.remove('error');
      const formGroup = reasonTextarea.closest('.form-group');
      const existingError = formGroup?.querySelector('.form-error');
      if (existingError) {
        existingError.remove();
      }
    }

    // Disable submit button
    if (submitDeviceChangeBtn) {
      submitDeviceChangeBtn.disabled = true;
      submitDeviceChangeBtn.textContent = 'Submitting...';
    }

    try {
      // Get current device fingerprint
      let currentFingerprint = null;
      if (typeof DeviceFingerprint !== 'undefined') {
        currentFingerprint = await DeviceFingerprint.generate();
      }

      // Call backend API to submit device change request
      const response = await fetch('/cics-attendance-system/backend/api/student/device-change', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        credentials: 'include',
        body: JSON.stringify({
          reason: reason,
          currentDeviceFingerprint: currentFingerprint
        })
      });

      const data = await response.json();

      if (data.success) {
        Toast.success(
          'Device change request submitted successfully! An administrator will review your request.',
          'Request Submitted',
          5000
        );

        // Close modal
        closeDeviceChangeModal();
      } else {
        Toast.error(data.message || 'Failed to submit request. Please try again.', 'Error');
      }
    } catch (error) {
      console.error('Error submitting device change request:', error);
      Toast.error('An error occurred while submitting your request. Please try again.', 'Error');
    } finally {
      // Reset button state
      if (submitDeviceChangeBtn) {
        submitDeviceChangeBtn.disabled = false;
        submitDeviceChangeBtn.textContent = 'Submit Request';
      }
    }
  }

  // Handle Logout
  async function handleLogout() {
    // Disable logout button
    if (confirmLogoutBtn) {
      confirmLogoutBtn.disabled = true;
      confirmLogoutBtn.textContent = 'Logging out...';
    }

    try {
      // Call backend logout API
      await AuthAPI.logout();
      
      // AuthAPI.logout() already handles redirect, but show message first
      Toast.info('You have been logged out successfully.', 'Logged Out', 2000);
    } catch (error) {
      console.error('Logout error:', error);
      // Even if API call fails, clear local session and redirect
      sessionStorage.removeItem('user');
      sessionStorage.removeItem('isLoggedIn');
      Toast.info('You have been logged out.', 'Logged Out', 2000);
      setTimeout(() => {
        window.location.href = '/cics-attendance-system/login.php';
      }, 1500);
    }
  }

  // Event Listeners for Device Change Modal
  if (requestDeviceChangeBtn) {
    requestDeviceChangeBtn.addEventListener('click', openDeviceChangeModal);
  }

  if (closeDeviceChangeModalBtn) {
    closeDeviceChangeModalBtn.addEventListener('click', closeDeviceChangeModal);
  }

  if (cancelDeviceChangeBtn) {
    cancelDeviceChangeBtn.addEventListener('click', closeDeviceChangeModal);
  }

  if (submitDeviceChangeBtn) {
    submitDeviceChangeBtn.addEventListener('click', handleDeviceChangeSubmit);
  }

  // Close modal when clicking on backdrop
  if (deviceChangeModal) {
    deviceChangeModal.addEventListener('click', (e) => {
      if (e.target === deviceChangeModal) {
        closeDeviceChangeModal();
      }
    });
  }

  // Close modal on Escape key
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      if (deviceChangeModal?.classList.contains('show')) {
        closeDeviceChangeModal();
      }
      if (logoutModal?.classList.contains('show')) {
        closeLogoutModal();
      }
    }
  });

  // Event Listeners for Logout Modal
  if (logoutBtn) {
    logoutBtn.addEventListener('click', openLogoutModal);
  }

  if (closeLogoutModalBtn) {
    closeLogoutModalBtn.addEventListener('click', closeLogoutModal);
  }

  if (cancelLogoutBtn) {
    cancelLogoutBtn.addEventListener('click', closeLogoutModal);
  }

  if (confirmLogoutBtn) {
    confirmLogoutBtn.addEventListener('click', handleLogout);
  }

  // Close modal when clicking on backdrop
  if (logoutModal) {
    logoutModal.addEventListener('click', (e) => {
      if (e.target === logoutModal) {
        closeLogoutModal();
      }
    });
  }

  // Handle Privacy Policy link
  const viewPrivacyPolicyBtn = document.getElementById('viewPrivacyPolicyBtn');
  if (viewPrivacyPolicyBtn) {
    viewPrivacyPolicyBtn.addEventListener('click', (e) => {
      e.preventDefault();
      Toast.info('Privacy Policy feature will be available soon.', 'Coming Soon');
    });
  }

  // Validate reason textarea in real-time
  if (reasonTextarea) {
    reasonTextarea.addEventListener('input', () => {
      if (reasonTextarea.classList.contains('error')) {
        const value = reasonTextarea.value.trim();
        if (value.length >= 10) {
          reasonTextarea.classList.remove('error');
          const formGroup = reasonTextarea.closest('.form-group');
          const existingError = formGroup?.querySelector('.form-error');
          if (existingError) {
            existingError.remove();
          }
        }
      }
    });

    reasonTextarea.addEventListener('blur', () => {
      const value = reasonTextarea.value.trim();
      if (!value || value.length < 10) {
        reasonTextarea.classList.add('error');
        const formGroup = reasonTextarea.closest('.form-group');
        const existingError = formGroup?.querySelector('.form-error');
        
        if (!existingError && document.activeElement !== submitDeviceChangeBtn) {
          const errorDiv = document.createElement('div');
          errorDiv.className = 'form-error';
          errorDiv.textContent = 'Please provide a reason (at least 10 characters)';
          formGroup?.appendChild(errorDiv);
        }
      }
    });
  }

  // Load device fingerprint on page load
  loadDeviceFingerprint();
});

