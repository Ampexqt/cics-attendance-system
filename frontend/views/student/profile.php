<?php
/**
 * Student Profile Page
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
$email = '';

if ($userData) {
    $studentName = ($userData['first_name'] ?? '') . ' ' . ($userData['last_name'] ?? '');
    $studentId = $userData['student_id'] ?? '';
    $program = $userData['program'] ?? '';
    $section = $userData['section'] ?? '';
    $email = $userData['email'] ?? '';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - CICS Attendance System</title>
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
                <a href="profile.php" class="sidebar-nav-item active">
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
                    <h1 class="main-header-title">Profile</h1>
                </div>
            </div>

            <div class="main-body">
                <!-- Profile Header Section -->
                <div class="profile-header">
                    <div class="profile-avatar">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                    </div>
                    <div class="profile-info">
                        <h2 class="profile-name"><?php echo htmlspecialchars($studentName); ?></h2>
                        <p class="profile-subtitle"><?php echo htmlspecialchars($program . ($section ? ' • ' . $section : '')); ?><?php echo $studentId ? ' • ID: ' . htmlspecialchars($studentId) : ''; ?></p>
                        <div class="profile-badges">
                            <span class="profile-badge badge-active">Registered Device: Active</span>
                            <span class="profile-badge badge-role">Student</span>
                        </div>
                    </div>
                </div>

                <!-- Account Details Section -->
                <div class="profile-section">
                    <h3 class="profile-section-title">Account Details</h3>
                    <div class="profile-details-list">
                        <div class="profile-detail-item">
                            <svg class="profile-detail-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                            </svg>
                            <div class="profile-detail-content">
                                <span class="profile-detail-label">Email</span>
                                <span class="profile-detail-value"><?php echo htmlspecialchars($email); ?></span>
                            </div>
                        </div>
                        <div class="profile-detail-item">
                            <svg class="profile-detail-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25" />
                            </svg>
                            <div class="profile-detail-content">
                                <span class="profile-detail-label">Device fingerprint</span>
                                <span class="profile-detail-value" id="deviceFingerprint">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions Section -->
                <div class="profile-section">
                    <h3 class="profile-section-title">Actions</h3>
                    <div class="profile-actions-list">
                        <button class="profile-action-item" id="requestDeviceChangeBtn">
                            <svg class="profile-action-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25" />
                            </svg>
                            <span class="profile-action-label">Request Device Change</span>
                        </button>
                        <a href="#" class="profile-action-item" id="viewPrivacyPolicyBtn">
                            <svg class="profile-action-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                            </svg>
                            <span class="profile-action-label">View Privacy Policy</span>
                        </a>
                        <button class="profile-action-item profile-action-logout" id="logoutBtn">
                            <svg class="profile-action-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                            </svg>
                            <span class="profile-action-label">Logout</span>
                        </button>
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
            <a href="profile.php" class="mobile-nav-item active">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                </svg>
                <span>Profile</span>
            </a>
        </nav>

        <!-- Request Device Change Modal -->
        <div class="modal-backdrop" id="deviceChangeModal">
            <div class="modal">
                <div class="modal-header">
                    <h2 class="modal-title">Request Device Change</h2>
                    <button class="modal-close" id="closeDeviceChangeModal">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="modal-description">
                        You are requesting to change your registered device. This action will require admin approval. 
                        Your current device fingerprint will be unregistered and you'll need to register a new device.
                    </p>
                    <div class="modal-info-box">
                        <div class="modal-info-item">
                            <span class="modal-info-label">Current Device fingerprint:</span>
                            <span class="modal-info-value" id="currentDeviceFingerprint">Loading...</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reason" class="form-label">Reason for Device Change</label>
                        <textarea id="reason" name="reason" class="form-textarea" rows="4" placeholder="Please explain why you need to change your device..." required></textarea>
                        <p class="form-help">This information will help administrators process your request.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-ghost" id="cancelDeviceChangeBtn">Cancel</button>
                    <button type="button" class="btn btn-primary" id="submitDeviceChangeBtn">Submit Request</button>
                </div>
            </div>
        </div>

        <!-- Logout Confirmation Modal -->
        <div class="modal-backdrop" id="logoutModal">
            <div class="modal modal-sm">
                <div class="modal-header">
                    <h2 class="modal-title">Confirm Logout</h2>
                    <button class="modal-close" id="closeLogoutModal">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="modal-description">
                        Are you sure you want to logout? You will need to login again to access your account.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-ghost" id="cancelLogoutBtn">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmLogoutBtn">Logout</button>
                </div>
            </div>
        </div>
    </div>

    <script src="../../assets/js/global.js"></script>
    <script src="../../assets/js/auth.js"></script>
    <script src="../../assets/js/fingerprint.js"></script>
    <script src="../../assets/js/profile.js"></script>
    <script>
        // Check authentication on page load
        if (!AuthAPI.isAuthenticated()) {
            window.location.href = '/cics-attendance-system/login.php';
        }
    </script>
</body>

</html>

