<div class="main-header">
  <div style="display: flex; align-items: center; gap: var(--spacing-md);">
    <img src="../../assets/img/ZPPUS-CICS LOGO.jpg" alt="CICS Logo" style="width: 40px; height: 40px; border-radius: var(--radius-md);">
    <h1 class="main-header-title">Admin Dashboard — Campus Attendance System</h1>
  </div>
  <div class="main-header-actions">
    <button class="btn-icon" title="Notifications" style="color: #ffffff; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2);">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" style="width: 1.5rem; height: 1.5rem;">
        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0018 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
      </svg>
    </button>
    <div style="display: flex; align-items: center; gap: var(--spacing-sm);">
      <div style="width: 2rem; height: 2rem; border-radius: var(--radius-full); background-color: var(--bg-primary); color: var(--bg-dark); display: flex; align-items: center; justify-content: center; font-weight: var(--font-weight-semibold); font-size: var(--font-size-sm);">AD</div>
      <span style="font-weight: var(--font-weight-medium); color: var(--text-inverse);">Admin</span>
    </div>
    <button class="btn-icon" title="Logout" onclick="handleLogout()" style="color: #ffffff; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2);">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" style="width: 1.5rem; height: 1.5rem;">
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