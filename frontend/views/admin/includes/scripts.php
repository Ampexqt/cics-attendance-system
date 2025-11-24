<!-- Logout Confirmation Modal -->
<div class="modal-backdrop" id="adminLogoutModal">
  <div class="modal modal-sm" id="adminLogoutModalContent">
    <div class="modal-header">
      <h2 class="modal-title">Confirm Logout</h2>
      <button class="modal-close" id="closeAdminLogoutModal">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>
    <div class="modal-body">
      <p style="color: var(--text-primary); margin: 0;">
        Are you sure you want to logout? You will need to login again to access your account.
      </p>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-ghost" id="cancelAdminLogoutBtn">Cancel</button>
      <button type="button" class="btn btn-danger" id="confirmAdminLogoutBtn">Logout</button>
    </div>
  </div>
</div>

<script src="../../assets/js/global.js"></script>
<script src="../../assets/js/auth.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Initialize Modal system
    Modal.init();

    // Get modal elements
    const logoutModal = document.getElementById('adminLogoutModal');
    const logoutBtn = document.getElementById('adminLogoutBtn');
    const closeModalBtn = document.getElementById('closeAdminLogoutModal');
    const cancelBtn = document.getElementById('cancelAdminLogoutBtn');
    const confirmBtn = document.getElementById('confirmAdminLogoutBtn');

    // Open logout modal
    function openLogoutModal() {
      Modal.open('adminLogoutModal');
    }

    // Close logout modal
    function closeLogoutModal() {
      Modal.close('adminLogoutModal');
    }

    // Handle logout confirmation
    async function handleLogout() {
      if (confirmBtn) {
        confirmBtn.disabled = true;
        confirmBtn.textContent = 'Logging out...';
      }

      try {
        await AuthAPI.logout();
      } catch (error) {
        console.error('Logout error:', error);
        // Even if API call fails, clear local session and redirect
        sessionStorage.removeItem('user');
        sessionStorage.removeItem('isLoggedIn');
        window.location.href = '/cics-attendance-system/login.php';
      }
    }

    // Event listeners
    if (logoutBtn) {
      logoutBtn.addEventListener('click', function(e) {
        e.preventDefault();
        openLogoutModal();
      });
    }

    if (closeModalBtn) {
      closeModalBtn.addEventListener('click', closeLogoutModal);
    }

    if (cancelBtn) {
      cancelBtn.addEventListener('click', closeLogoutModal);
    }

    if (confirmBtn) {
      confirmBtn.addEventListener('click', handleLogout);
    }
  });
</script>
