<?php
/**
 * Student Attendance Logs Page
 * CICS Attendance System
 */

// Start session and check authentication
require_once __DIR__ . '/../../../auth_check.php';
require_role('student');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Logs - CICS Attendance System</title>
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
                <a href="dashboard.php" class="sidebar-nav-item">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                    <span>Home</span>
                </a>
                <a href="logs.php" class="sidebar-nav-item active">
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
                    <h1 class="main-header-title">Attendance Logs</h1>
                </div>
            </div>

            <div class="main-body">
                <!-- Filters Section -->
                <div class="logs-filters">
                    <div class="filter-group">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 1.25rem; height: 1.25rem;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-16.5 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-16.5 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                        </svg>
                        <span class="filter-label">Date Range</span>
                        <a href="#" class="filter-value">This Week</a>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 1rem; height: 1rem;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </div>

                    <div class="filter-group">
                        <span class="filter-label">Subject</span>
                        <a href="#" class="filter-value">All Subjects</a>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 1rem; height: 1rem;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </div>

                    <div class="filter-group">
                        <span class="filter-label">Section</span>
                        <a href="#" class="filter-value">All Sections</a>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 1rem; height: 1rem;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </div>
                </div>

                <!-- Logs List -->
                <div class="logs-list" id="logsList">
                    <div class="log-item" style="text-align: center; padding: var(--spacing-lg); color: var(--text-secondary);">
                        Loading attendance logs...
                    </div>
                </div>
            </div>
        </main>

        <!-- Mobile Bottom Navigation -->
        <nav class="mobile-bottom-nav">
            <a href="dashboard.php" class="mobile-nav-item">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
                <span>Home</span>
            </a>
            <a href="logs.php" class="mobile-nav-item active">
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
        // Check authentication on page load
        if (!AuthAPI.isAuthenticated()) {
            window.location.href = '/cics-attendance-system/login.php';
        }

        // Load attendance logs
        async function loadAttendanceLogs() {
            try {
                const response = await fetch('/cics-attendance-system/backend/api/attendance/records', {
                    credentials: 'include'
                });

                const data = await response.json();
                const logsList = document.getElementById('logsList');

                if (data.success && data.data && data.data.length > 0) {
                    logsList.innerHTML = '';
                    
                    data.data.forEach(log => {
                        const statusClass = log.status === 'present' ? 'present' : 
                                          log.status === 'late' ? 'late' : 'absent';
                        const statusIcon = log.status === 'present' ? 
                            '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />' :
                            log.status === 'late' ?
                            '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />' :
                            '<path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />';
                        
                        const timeInfo = log.time_in && log.time_out ? 
                            `Time-in: ${log.time_in} • Time-out: ${log.time_out}` :
                            log.time_in ? `Time-in: ${log.time_in}` :
                            'No attendance record';

                        const logItem = document.createElement('div');
                        logItem.className = 'log-item';
                        logItem.innerHTML = `
                            <div class="log-item-header">
                                <h3 class="log-item-title">${log.subject || 'N/A'}</h3>
                                <span class="status-badge ${statusClass}">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        ${statusIcon}
                                    </svg>
                                    ${log.status.charAt(0).toUpperCase() + log.status.slice(1)}
                                </span>
                            </div>
                            <div class="log-item-details">
                                <span class="log-time">${timeInfo}</span>
                            </div>
                            <div class="log-item-footer">
                                <span class="log-instructor">Instructor: ${log.instructor || 'N/A'}</span>
                                <span class="log-date">${log.date || ''}</span>
                            </div>
                        `;
                        logsList.appendChild(logItem);
                    });
                } else {
                    logsList.innerHTML = `
                        <div class="log-item" style="text-align: center; padding: var(--spacing-lg); color: var(--text-secondary);">
                            No attendance records found.
                        </div>
                    `;
                }
            } catch (error) {
                console.error('Error loading attendance logs:', error);
                document.getElementById('logsList').innerHTML = `
                    <div class="log-item" style="text-align: center; padding: var(--spacing-lg); color: var(--text-danger);">
                        Error loading attendance logs. Please try again later.
                    </div>
                `;
            }
        }

        // Load logs on page load
        loadAttendanceLogs();
    </script>
</body>

</html>