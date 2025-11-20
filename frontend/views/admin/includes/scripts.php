<script src="../../assets/js/global.js"></script>
<script src="../../assets/js/auth.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Find the logout button (it's the second icon button in the header actions)
    const logoutBtn = document.querySelector('.main-header-actions button[title="Logout"]');
    if (logoutBtn) {
      logoutBtn.addEventListener('click', function() {
        if (confirm('Are you sure you want to logout?')) {
          AuthAPI.logout();
        }
      });
    }
  });
</script>
