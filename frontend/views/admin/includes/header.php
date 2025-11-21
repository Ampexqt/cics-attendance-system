<div class="main-header">
  <div class="main-header-title-group">
    <h1 class="main-header-title">Admin Dashboard — Campus Attendance System</h1>
  </div>
  <div class="main-header-actions">
    <div class="main-header-user">
      <div class="user-chip">AD</div>
      <span class="user-name">Admin</span>
    </div>
    <button class="btn-icon btn-icon--light" title="Logout" onclick="handleLogout()">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
      </svg>
    </button>
  </div>
</div>

<script>
  function handleLogout() {
    if (confirm('Are you sure you want to logout?')) {
      AuthAPI.logout();
    }
  }
</script>