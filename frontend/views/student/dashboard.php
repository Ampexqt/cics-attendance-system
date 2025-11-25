<?php

/**
 * Student Dashboard
 * CICS Attendance System
 */

// Start session and check authentication
require_once __DIR__ . '/../../../auth_check.php';
require_role('student');

// Get user data from session
$userData = $_SESSION['user_data'] ?? null;
$studentName = 'Student';
$studentId = '';
$program = '';
$section = '';

if ($userData) {
  $studentName = ($userData['first_name'] ?? '') . ' ' . ($userData['last_name'] ?? '');
  $studentId = $userData['student_id'] ?? '';
  $program = $userData['program'] ?? '';
  $section = $userData['section'] ?? '';
}
?>
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
  <link rel="stylesheet" href="../../assets/css/pages/student-schedule.css">
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
          <h1 class="main-header-title">Welcome, <?php echo htmlspecialchars($studentName); ?></h1>
          <p style="color: var(--text-secondary); font-size: var(--font-size-sm);"><?php echo htmlspecialchars($program . ($section ? ' • ' . $section : '')); ?> • <span style="color: var(--accent-gold);">●</span> Registered Device Active</p>
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
              <div class="schedule-list" id="scheduleList">
                <div class="schedule-item" style="text-align: center; padding: var(--spacing-md); color: var(--text-secondary);">
                  Loading schedule...
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
                    <circle class="summary-chart-circle summary-chart-progress" cx="50" cy="50" r="36" id="summaryProgress" style="--progress: 0;"></circle>
                  </svg>
                  <div class="summary-chart-text" id="summaryPercentage">0%</div>
                </div>
                <div class="summary-stats">
                  <div class="summary-stat">
                    <span class="summary-stat-label">Absences:</span>
                    <strong id="summaryAbsences">0</strong>
                  </div>
                  <div class="summary-stat">
                    <span class="summary-stat-label">Late:</span>
                    <strong id="summaryLate">0</strong>
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
            <div class="card-body" id="recentRequestsCard">
              <div style="text-align: center; padding: var(--spacing-md); color: var(--text-secondary);">
                Loading requests...
              </div>
            </div>
          </div>

          <!-- Class Schedule Card -->
          <div class="card schedule-card">
            <div class="card-header">
              <h3 class="card-title">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 1.25rem; height: 1.25rem; display: inline-block; margin-right: var(--spacing-sm);">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-16.5 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-16.5 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                </svg>
                Weekly Class Schedule
              </h3>
            </div>
            <div class="card-body">
              <div class="schedule-grid" id="weeklyScheduleGrid">
                <div style="text-align: center; padding: var(--spacing-md); color: var(--text-secondary); grid-column: 1 / -1;">
                  Loading schedule...
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- Mobile Bottom Navigation -->
    <nav class="mobile-bottom-nav">
      <a href="dashboard.php" class="mobile-nav-item active">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
        </svg>
        <span>Home</span>
      </a>
      <a href="logs.php" class="mobile-nav-item">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
        </svg>
        <span>Logs</span>
      </a>
      <a href="requests.php" class="mobile-nav-item">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
        </svg>
        <span>Requests</span>
      </a>
      <a href="profile.php" class="mobile-nav-item">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
        </svg>
        <span>Profile</span>
      </a>
    </nav>
  </div>

  <script src="../../assets/js/global.js"></script>
  <script src="../../assets/js/auth.js"></script>
  <script>
    let studentActiveSession = null;
    
    // Session State Manager - Handles caching and persistence
    const SessionStateManager = {
      CACHE_KEY: 'student_active_session_cache',
      CACHE_EXPIRY_KEY: 'student_active_session_expiry',
      CACHE_DURATION: 30000, // 30 seconds cache
      
      getCachedState() {
        const cached = sessionStorage.getItem(this.CACHE_KEY);
        const expiry = sessionStorage.getItem(this.CACHE_EXPIRY_KEY);
        
        if (!cached || !expiry) return null;
        
        // Check if cache has expired
        if (Date.now() > parseInt(expiry, 10)) {
          this.clearCache();
          return null;
        }
        
        try {
          return JSON.parse(cached);
        } catch (e) {
          console.warn('Failed to parse cached session state:', e);
          this.clearCache();
          return null;
        }
      },
      
      setCachedState(state) {
        try {
          sessionStorage.setItem(this.CACHE_KEY, JSON.stringify(state));
          sessionStorage.setItem(this.CACHE_EXPIRY_KEY, (Date.now() + this.CACHE_DURATION).toString());
        } catch (e) {
          console.warn('Failed to cache session state:', e);
        }
      },
      
      clearCache() {
        sessionStorage.removeItem(this.CACHE_KEY);
        sessionStorage.removeItem(this.CACHE_EXPIRY_KEY);
      }
    };
    
    // Helper function to convert 24-hour time to 12-hour format with AM/PM
    function formatTimeTo12Hour(time24) {
      if (!time24) return '';
      const [hours, minutes] = time24.split(':');
      const hour = parseInt(hours, 10);
      const ampm = hour >= 12 ? 'PM' : 'AM';
      const hour12 = hour % 12 || 12;
      return `${hour12}:${minutes} ${ampm}`;
    }
    
    // Check authentication on page load
    if (!AuthAPI.isAuthenticated()) {
      window.location.href = '/cics-attendance-system/login.php';
    }

    // Load dashboard data
    async function loadDashboardData() {
      try {
        // Load attendance summary
        const summaryResponse = await fetch('/cics-attendance-system/backend/api/attendance/summary', {
          credentials: 'include'
        });

        if (summaryResponse.ok) {
          const summaryData = await summaryResponse.json();
          if (summaryData.success && summaryData.data) {
            const stats = summaryData.data;
            const total = stats.total_sessions || 0;
            const present = stats.present || 0;
            const absent = stats.absent || 0;
            const late = stats.late || 0;

            // Calculate percentage
            const percentage = total > 0 ? Math.round((present / total) * 100) : 0;
            document.getElementById('summaryPercentage').textContent = percentage + '%';
            document.getElementById('summaryProgress').style.setProperty('--progress', percentage);
            document.getElementById('summaryAbsences').textContent = absent;
            document.getElementById('summaryLate').textContent = late;
          }
        }

        // Load recent requests (if API exists)
        // For now, show empty state or link to requests page
        const requestsCard = document.getElementById('recentRequestsCard');
        requestsCard.innerHTML = `
          <div style="text-align: center; padding: var(--spacing-md); color: var(--text-secondary);">
            No recent requests
          </div>
          <a href="requests.php" class="btn btn-outline btn-block" style="margin-top: var(--spacing-md);">View All Requests</a>
        `;
      } catch (error) {
        console.error('Error loading dashboard data:', error);
      }
    }

    // Load weekly schedule
    async function loadWeeklySchedule() {
      try {
        const response = await fetch('/cics-attendance-system/backend/api/student/schedule', {
          credentials: 'include'
        });

        if (response.ok) {
          const json = await response.json();
          if (json.success && json.data) {
            renderSchedule(json.data);
          }
        }
      } catch (error) {
        console.error('Error loading schedule:', error);
        document.getElementById('weeklyScheduleGrid').innerHTML = `
          <div style="text-align: center; padding: var(--spacing-md); color: var(--error-color); grid-column: 1 / -1;">
            Failed to load schedule.
          </div>
        `;
      }
    }

    function renderSchedule(scheduleData) {
      const grid = document.getElementById('weeklyScheduleGrid');
      grid.innerHTML = '';

      const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

      days.forEach(day => {
        const daySchedule = scheduleData[day] || [];
        const dayElement = document.createElement('div');
        dayElement.className = 'schedule-day';

        let classesHtml = '';

        if (daySchedule.length > 0) {
          daySchedule.forEach(cls => {
            classesHtml += `
              <div class="class-item">
                <span class="class-time">${formatTimeTo12Hour(cls.start_time)} - ${formatTimeTo12Hour(cls.end_time)}</span>
                <div class="class-subject">${cls.subject_name}</div>
                <div class="class-details">
                  <div class="class-detail-row">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="class-detail-icon">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                      <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 6.627-5.373 12-12 12s-12-5.373-12-12 5.373-12 12-12 12 5.373 12 12z" />
                    </svg>
                    <span>${cls.room || 'TBA'}</span>
                  </div>
                  <div class="class-detail-row">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="class-detail-icon">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                    <span>${cls.instructor || 'TBA'}</span>
                  </div>
                </div>
              </div>
            `;
          });
        } else {
          classesHtml = '<div class="empty-day">No classes</div>';
        }

        dayElement.innerHTML = `
          <div class="schedule-day-header">${day}</div>
          <div class="schedule-day-body">
            ${classesHtml}
          </div>
        `;

        grid.appendChild(dayElement);
      });
    }

    // Function to load today's schedule
    async function loadTodaysSchedule() {
      const scheduleList = document.getElementById('scheduleList');

      try {
        // First, get today's day name (e.g., 'Monday', 'Tuesday')
        const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        const today = new Date();
        const todayName = days[today.getDay()];

        // Get the weekly schedule
        const response = await fetch('/cics-attendance-system/backend/api/student/schedule', {
          credentials: 'include'
        });

        if (response.ok) {
          const json = await response.json();
          if (json.success && json.data) {
            const todaySchedule = json.data[todayName] || [];

            if (todaySchedule.length > 0) {
              let html = '';
              todaySchedule.forEach(cls => {
                html += `
                  <div class="schedule-item">
                    <div class="schedule-time">
                      ${formatTimeTo12Hour(cls.start_time)} - ${formatTimeTo12Hour(cls.end_time)}
                    </div>
                    <div class="schedule-details">
                      <div class="schedule-subject">${cls.subject_name || 'No Subject'}</div>
                      <div class="schedule-meta">
                        <span class="schedule-room">${cls.room || 'TBA'}</span>
                        <span class="schedule-instructor">${cls.instructor || 'TBA'}</span>
                      </div>
                    </div>
                  </div>
                `;
              });
              scheduleList.innerHTML = html;
            } else {
              scheduleList.innerHTML = `
                <div class="schedule-item" style="text-align: center; padding: var(--spacing-md); color: var(--text-secondary);">
                  No classes scheduled for today
                </div>
              `;
            }
          }
        } else {
          throw new Error('Failed to fetch schedule');
        }
      } catch (error) {
        console.error('Error loading today\'s schedule:', error);
        scheduleList.innerHTML = `
          <div class="schedule-item" style="text-align: center; padding: var(--spacing-md); color: var(--error-color);">
            Failed to load today's schedule. Please try again later.
          </div>
        `;
      }
    }

    async function loadActiveSessionState(forceRefresh = false) {
      const statusContainer = document.querySelector('.attendance-status');
      const button = document.getElementById('attendanceBtn');

      try {
        // Try to use cached state first if not forcing refresh
        if (!forceRefresh) {
          const cachedState = SessionStateManager.getCachedState();
          if (cachedState) {
            console.log('[Session] Using cached state');
            updateUIWithSessionState(cachedState, button, statusContainer);
            studentActiveSession = cachedState;
            return;
          }
        }

        // Fetch fresh data from server
        const response = await fetch('/cics-attendance-system/backend/api/attendance/student-active-session', {
          credentials: 'include'
        });

        if (!response.ok) {
          throw new Error(`Server returned ${response.status}`);
        }

        const result = await response.json();

        if (response.ok && result.success && result.data) {
          studentActiveSession = result.data;
          
          // Cache the state for future refreshes
          SessionStateManager.setCachedState(result.data);
          
          // Update UI
          updateUIWithSessionState(result.data, button, statusContainer);
        } else {
          studentActiveSession = null;
          SessionStateManager.clearCache();
          updateUIWithNoSession(button, statusContainer);
        }
      } catch (error) {
        console.error('Error loading active session:', error);
        
        // If error and we have cached state, use it instead of showing error
        const cachedState = SessionStateManager.getCachedState();
        if (cachedState && !forceRefresh) {
          console.log('[Session] Using cached state after error');
          studentActiveSession = cachedState;
          updateUIWithSessionState(cachedState, button, statusContainer);
        } else {
          studentActiveSession = null;
          SessionStateManager.clearCache();
          updateUIWithError(button, statusContainer);
        }
      }
    }

    function updateUIWithSessionState(sessionData, button, statusContainer) {
      const subject = sessionData.subject || {};
      const windowLabel = sessionData.window?.label || 'Session in progress';
      const status = sessionData.attendance_status; // 'none', 'timed_in', 'timed_out'

      // Update Button State based on Attendance Status
      button.disabled = false;
      button.classList.remove('btn-danger', 'btn-success', 'btn-secondary');

      if (status === 'timed_out') {
        button.innerText = 'Completed';
        button.disabled = true;
        button.classList.add('btn-secondary');
      } else if (status === 'timed_in') {
        button.innerText = 'Time-Out';
        button.classList.add('btn-danger'); // Make it red for Time-Out
      } else {
        button.innerText = 'Time-In';
        button.classList.add('btn-success'); // Green for Time-In
      }

      statusContainer.innerHTML = `
        <div class="status-item success">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <span>${subject.name || 'Active Session'} (${subject.code || 'N/A'})</span>
        </div>
        <div class="status-item info">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <span>${windowLabel}</span>
        </div>
      `;
    }

    function updateUIWithNoSession(button, statusContainer) {
      button.disabled = true;
      button.innerText = 'No Active Session';
      statusContainer.innerHTML = `
        <div class="status-item info">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.288 15.038a5.25 5.25 0 017.424 0M5.106 11.856c3.807-3.808 9.98-3.808 13.788 0M1.924 8.674c5.565-5.565 14.587-5.565 20.152 0" />
          </svg>
          <span>No active session available</span>
        </div>
        <div class="status-item warning">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.008v.008H12z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12z" />
          </svg>
          <span>Wait for your instructor to start the session</span>
        </div>
      `;
    }

    function updateUIWithError(button, statusContainer) {
      button.disabled = true;
      statusContainer.innerHTML = `
        <div class="status-item warning">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.008v.008H12z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75-9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12z" />
          </svg>
          <span>Unable to load active session. Please refresh.</span>
        </div>
      `;
    }

    // Handle attendance button
    document.getElementById('attendanceBtn').addEventListener('click', async function() {
      if (!studentActiveSession || !studentActiveSession.session) {
        Toast.error('No active session detected. Please wait for your instructor to start the class.', 'No Session');
        return;
      }

      const currentStatus = studentActiveSession.attendance_status;
      if (currentStatus === 'timed_out') {
        Toast.info('You have already completed attendance for this session.', 'Completed');
        return;
      }

      const isTimeOut = currentStatus === 'timed_in';
      const actionText = isTimeOut ? 'Time-Out' : 'Time-In';

      Toast.info(`Processing ${actionText}...`, 'Please wait');

      try {
        if (!navigator.geolocation) {
          Toast.error('Geolocation is not supported by your browser', 'Error');
          return;
        }

        // Robust geolocation with high accuracy
        const gpsOptions = {
          enableHighAccuracy: true,
          timeout: 10000,
          maximumAge: 0
        };

        let watchId;
        let bestPosition = null;
        let restartAttempted = false;
        const startTime = Date.now();

        // Show loading state
        const button = document.getElementById('attendanceBtn');
        const originalBtnText = button.innerText;
        button.disabled = true;
        button.innerHTML = `<span class="spinner" style="width: 16px; height: 16px; display:inline-block;"></span> ${actionText}...`;

        // Set timeout to stop watching after maxWaitTime
        let timeoutId = setTimeout(() => {
          finish(bestPosition);
        }, 10000);

        function startWatch() {
          if (watchId) navigator.geolocation.clearWatch(watchId);

          watchId = navigator.geolocation.watchPosition(
            (position) => {
              const accuracy = position.coords.accuracy;
              const elapsed = Date.now() - startTime;

              // Keep the most accurate position
              if (!bestPosition || accuracy < bestPosition.coords.accuracy) {
                bestPosition = position;
              }

              // Restart strategy
              if (!restartAttempted && elapsed > 3000 && bestPosition && bestPosition.coords.accuracy > 1000) {
                restartAttempted = true;
                button.innerHTML = `<span class="spinner" style="width: 16px; height: 16px; display:inline-block;"></span> Retrying GPS...`;
                startWatch();
                return;
              }

              // Adaptive Strategy
              if (accuracy <= 20) {
                finish(bestPosition);
              } else if (accuracy <= 50 && elapsed > 1000) {
                finish(bestPosition);
              } else if (accuracy <= 100 && elapsed > 3000) {
                finish(bestPosition);
              } else if (accuracy <= 200 && elapsed > 5000) {
                finish(bestPosition);
              }
            },
            (error) => {
              if (error.code === error.TIMEOUT) return;

              if (!bestPosition) {
                clearTimeout(timeoutId);
                handleGeoError(error);
              }
            },
            gpsOptions
          );
        }

        function finish(position) {
          clearTimeout(timeoutId);
          if (watchId) navigator.geolocation.clearWatch(watchId);
          button.disabled = false;
          button.innerText = originalBtnText;

          if (position) {
            submitAttendance(position);
          } else {
            Toast.error('Unable to get accurate location. Please check GPS settings.', 'Location Error');
          }
        }

        function handleGeoError(error) {
          button.disabled = false;
          button.innerText = originalBtnText;

          if (error.code === error.PERMISSION_DENIED) {
            // Check if running on HTTP on mobile device
            const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
            const isHttp = window.location.protocol === 'http:';

            if (isMobile && isHttp) {
              Toast.error(
                'Location access requires HTTPS on mobile devices. Please contact your administrator or access this site via HTTPS (https://).',
                'HTTPS Required',
                10000
              );
            } else {
              Toast.error(
                'Location permission denied. Please enable location access in your browser settings and ensure GPS is turned on.',
                'Permission Denied',
                8000
              );
            }
          } else if (error.code === error.POSITION_UNAVAILABLE) {
            Toast.error(
              'Location information unavailable. Please ensure GPS/Location services are enabled on your device.',
              'GPS Unavailable',
              8000
            );
          } else if (error.code === error.TIMEOUT) {
            Toast.error(
              'Location request timed out. Please ensure you have a clear view of the sky and try again.',
              'Timeout',
              8000
            );
          } else {
            Toast.error('Unable to retrieve location. Please try again.', 'Error');
          }
        }

        // Start
        startWatch();

        async function submitAttendance(position) {
          const endpoint = isTimeOut ? 'timeout' : 'mark';

          try {
            const response = await fetch(`/cics-attendance-system/backend/api/attendance/${endpoint}`, {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
              },
              credentials: 'include',
              body: JSON.stringify({
                session_id: studentActiveSession.session.id,
                latitude: position.coords.latitude,
                longitude: position.coords.longitude,
                accuracy: position.coords.accuracy
              })
            });

            const data = await response.json();

            if (data.success) {
              Toast.success(`${actionText} successful!`, 'Success');
              
              // Clear cache and reload session state immediately
              SessionStateManager.clearCache();
              
              // Small delay to ensure database is updated
              setTimeout(() => {
                loadActiveSessionState(true);
                loadDashboardData();
              }, 500);
            } else {
              Toast.error(data.message || `Failed to ${actionText}`, 'Error');
            }
          } catch (error) {
            Toast.error(`Failed to ${actionText}. Please try again.`, 'Error');
          }
        }
      } catch (error) {
        Toast.error('An error occurred. Please try again.', 'Error');
      }
    });

    // Load data on page load
    loadDashboardData();
    loadWeeklySchedule();
    loadTodaysSchedule();
    loadActiveSessionState();

    // Auto-refresh session state every 15 seconds
    let autoRefreshInterval = setInterval(() => {
      loadActiveSessionState(false); // Use cache if available
    }, 15000);

    // Force refresh every 2 minutes to stay in sync with server
    let forceRefreshInterval = setInterval(() => {
      SessionStateManager.clearCache();
      loadActiveSessionState(true); // Force fresh data from server
    }, 120000);

    // Refresh on page visibility change
    document.addEventListener('visibilitychange', () => {
      if (document.visibilityState === 'visible') {
        console.log('[Session] Page became visible, refreshing session state');
        SessionStateManager.clearCache();
        loadActiveSessionState(true);
      }
    });

    // Cleanup intervals on page unload
    window.addEventListener('beforeunload', () => {
      clearInterval(autoRefreshInterval);
      clearInterval(forceRefreshInterval);
    });

    // Check if mobile user is on HTTP and show warning
    const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    const isHttp = window.location.protocol === 'http:';

    if (isMobile && isHttp) {
      setTimeout(() => {
        Toast.warning(
          'For location-based attendance to work on mobile devices, please access this site using HTTPS (https://). Contact your administrator for assistance.',
          'HTTPS Recommended',
          12000
        );
      }, 2000); // Show after 2 seconds to not overwhelm on page load
    }
  </script>
</body>

</html>