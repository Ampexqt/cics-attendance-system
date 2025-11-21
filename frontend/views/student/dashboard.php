<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Dashboard - CICS Attendance System</title>
  <!-- Base Styles -->
  <link rel="stylesheet" href="../../assets/css/base/variables.css">
  <link rel="stylesheet" href="../../assets/css/base/reset.css">
  <link rel="stylesheet" href="../../assets/css/base/typography.css">
  <!-- Layout Styles -->
  <link rel="stylesheet" href="../../assets/css/layout/sidebar.css">
  <link rel="stylesheet" href="../../assets/css/layout/grid.css">
  <!-- Component Styles -->
  <link rel="stylesheet" href="../../assets/css/components/buttons.css">
  <link rel="stylesheet" href="../../assets/css/components/cards.css">
  <link rel="stylesheet" href="../../assets/css/components/forms.css">
  <!-- Page Styles -->
  <link rel="stylesheet" href="../../assets/css/pages/dashboard.css">
  <!-- Main Styles -->
  <link rel="stylesheet" href="../../assets/css/main.css">
</head>

<body>
  <div class="main-layout">
    <!-- Sidebar -->
    <aside class="sidebar">
      <div class="sidebar-header">
        <h2 class="sidebar-title">ZPPSU CICS</h2>
        <p class="sidebar-subtitle">Attendance System</p>
      </div>
      <nav class="sidebar-nav">
        <a href="dashboard.php" class="sidebar-nav-item active">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
          </svg>
          <span>Home</span>
        </a>
        <a href="logs.php" class="sidebar-nav-item">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
          </svg>
          <span>Logs</span>
        </a>
        <a href="requests.php" class="sidebar-nav-item">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
          </svg>
          <span>Requests</span>
        </a>
        <a href="profile.php" class="sidebar-nav-item">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
          </svg>
          <span>Profile</span>
        </a>
      </nav>
      <div class="sidebar-footer">
        <p>© 2023 ZPPSU CICS<br>Campus Attendance System</p>
      </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
      <div class="main-header">
        <div>
          <h1 class="main-header-title">Welcome, John Doe</h1>
          <p style="color: var(--text-secondary); font-size: var(--font-size-sm);">BSIT-ADT • <span style="color: var(--accent-gold);">●</span> Registered Device Active</p>
        </div>
        <div class="main-header-actions">
          <button class="btn btn-icon" title="Notifications">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0018 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
            </svg>
          </button>
          <button class="btn btn-icon" title="Profile">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
            </svg>
          </button>
        </div>
      </div>

      <div class="main-body">
        <div class="dashboard-grid">
          <!-- Mark Attendance Card -->
          <div class="card attendance-card">
            <div class="card-body">
              <h3 class="card-title">Mark Attendance</h3>
              <button class="attendance-button" id="attendanceBtn">
                Time-In / Time-Out
              </button>
              <div class="attendance-status">
                <div class="status-item success">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <span>Attendance Session Active</span>
                </div>
                <div class="status-item info">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.288 15.038a5.25 5.25 0 017.424 0M5.106 11.856c3.807-3.808 9.98-3.808 13.788 0M1.924 8.674c5.565-5.565 14.587-5.565 20.152 0M12.53 18.22l-.53.53-.53-.53a.75.75 0 011.06 0z" />
                  </svg>
                  <span>Campus Access Verified</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Today's Schedule Card -->
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 1.25rem; height: 1.25rem; display: inline-block; margin-right: var(--spacing-sm);">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-16.5 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-16.5 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                </svg>
                Today's Schedule
              </h3>
            </div>
            <div class="card-body">
              <div class="schedule-list">
                <div class="schedule-item">
                  <div class="schedule-time">8:00 AM - 9:30 AM</div>
                  <div class="schedule-course active">ADT 101 - Drafting Fundamentals</div>
                </div>
                <div class="schedule-item">
                  <div class="schedule-time">10:00 AM - 11:30 AM</div>
                  <div class="schedule-course">ADT 102 - Architectural CAD</div>
                </div>
                <div class="schedule-item">
                  <div class="schedule-time">1:00 PM - 2:30 PM</div>
                  <div class="schedule-course">ADT 103 - Design Theory</div>
                </div>
              </div>
            </div>
          </div>

          <!-- Attendance Summary Card -->
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 1.25rem; height: 1.25rem; display: inline-block; margin-right: var(--spacing-sm);">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Attendance Summary
              </h3>
            </div>
            <div class="card-body">
              <div class="attendance-summary">
                <div class="summary-chart">
                  <svg viewBox="0 0 100 100">
                    <circle class="summary-chart-circle summary-chart-bg" cx="50" cy="50" r="36"></circle>
                    <circle class="summary-chart-circle summary-chart-progress" cx="50" cy="50" r="36" style="--progress: 90;"></circle>
                  </svg>
                  <div class="summary-chart-text">90%</div>
                </div>
                <div class="summary-stats">
                  <div class="summary-stat">
                    <span class="summary-stat-label">Absences:</span>
                    <strong>3</strong>
                  </div>
                  <div class="summary-stat">
                    <span class="summary-stat-label">Late:</span>
                    <strong>1</strong>
                  </div>
                  <a href="logs.php" style="color: var(--primary-blue); font-size: var(--font-size-sm); margin-top: var(--spacing-sm); display: inline-block;">
                    View Full Logs →
                  </a>
                </div>
              </div>
            </div>
          </div>

          <!-- Correction Requests Card -->
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 1.25rem; height: 1.25rem; display: inline-block; margin-right: var(--spacing-sm);">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
                Correction Requests
              </h3>
            </div>
            <div class="card-body">
              <div style="display: flex; justify-content: space-between; align-items: center; padding: var(--spacing-md); background-color: var(--bg-secondary); border-radius: var(--radius-md); margin-bottom: var(--spacing-sm);">
                <div>
                  <div style="font-weight: var(--font-weight-medium); color: var(--text-primary);">System Error</div>
                  <div style="font-size: var(--font-size-sm); color: var(--text-secondary);">Oct 15, 2025</div>
                </div>
                <span class="status-badge active">Approved</span>
              </div>
              <a href="requests.php" class="btn btn-outline btn-block" style="margin-top: var(--spacing-md);">View All Requests</a>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>

  <script src="../../assets/js/global.js"></script>
  <script>
    document.getElementById('attendanceBtn').addEventListener('click', function() {
      // Simulate attendance marking
      Toast.info('Processing attendance...', 'Please wait');
      setTimeout(() => {
        Toast.success('Attendance marked successfully!', 'Success');
      }, 1000);
    });
  </script>
</body>

</html>