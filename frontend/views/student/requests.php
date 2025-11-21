<?php
/**
 * Student Correction Requests Page
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
    <title>Correction Requests - CICS Attendance System</title>
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
                <a href="requests.php" class="sidebar-nav-item active">
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
                    <h1 class="main-header-title">Correction Requests</h1>
                </div>
            </div>

            <div class="main-body">
                <!-- Requests List -->
                <div class="requests-list" id="requestsList">
                    <div class="request-card" style="text-align: center; padding: var(--spacing-lg); color: var(--text-secondary);">
                        Loading correction requests...
                    </div>
                </div>
            </div>
        </main>

        <!-- Floating Action Button -->
        <button class="fab" id="newRequestFab" title="New Correction Request">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
        </button>

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
            <a href="requests.php" class="mobile-nav-item active">
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

        <!-- New Request Modal -->
        <div class="modal-backdrop" id="newRequestModal">
            <div class="modal" id="newRequestModalContent">
                <div class="modal-header">
                    <h2 class="modal-title">New Correction Request</h2>
                    <button class="modal-close" id="closeModal">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="newRequestForm">
                        <div class="form-group">
                            <label for="subject" class="form-label">Subject / Class</label>
                            <select id="subject" name="subject" class="form-select" required>
                                <option value="">Select a subject</option>
                                <option value="adt101">ADT 101 - Drafting Fundamentals</option>
                                <option value="adt102">ADT 102 - Architectural CAD</option>
                                <option value="adt103">ADT 103 - Design Theory</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="date" class="form-label">Date of Attendance</label>
                            <div class="input-group input-group-right">
                                <input type="date" id="date" name="date" class="form-input" required>
                                <svg class="input-group-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-16.5 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-16.5 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                </svg>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="message" class="form-label">Message to Instructor</label>
                            <textarea id="message" name="message" class="form-textarea" rows="4" placeholder="Explain the reason for your correction request..." required></textarea>
                            <p class="form-help">This message will be sent to your instructor for review</p>
                        </div>

                        <div class="form-group">
                            <label for="attachment" class="form-label">Attachment (PNG only)</label>
                            <div class="file-upload-wrapper">
                                <input type="file" id="attachment" name="attachment" class="form-file-input" accept="image/png,.png">
                                <label for="attachment" class="file-upload-label">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941l-7.81 7.81a1.5 1.5 0 002.112 2.13" />
                                    </svg>
                                    <span id="fileLabel">Choose file or drag and drop</span>
                                </label>
                                <div id="filePreview" class="file-preview"></div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-ghost" id="cancelRequest">Cancel</button>
                    <button type="submit" form="newRequestForm" class="btn btn-primary">Submit Request</button>
                </div>
            </div>
        </div>
    </div>

    <script src="../../assets/js/global.js"></script>
    <script src="../../assets/js/auth.js"></script>
    <script src="../../assets/js/requests.js"></script>
    <script>
        // Check authentication on page load
        if (!AuthAPI.isAuthenticated()) {
            window.location.href = '/cics-attendance-system/login.php';
        }

        // Load correction requests
        async function loadCorrectionRequests() {
            try {
                // Note: This endpoint may need to be created in the backend
                const response = await fetch('/cics-attendance-system/backend/api/student/requests', {
                    credentials: 'include'
                });

                const data = await response.json();
                const requestsList = document.getElementById('requestsList');

                if (data.success && data.data && data.data.length > 0) {
                    requestsList.innerHTML = '';
                    
                    data.data.forEach(request => {
                        const statusClass = request.status === 'pending' ? 'pending' : 
                                          request.status === 'approved' ? 'approved' : 'rejected';
                        
                        const requestCard = document.createElement('div');
                        requestCard.className = 'request-card';
                        requestCard.innerHTML = `
                            <div class="request-card-header">
                                <h3 class="request-card-title">${request.subject || 'N/A'}</h3>
                                <span class="request-status-badge ${statusClass}">${request.status.charAt(0).toUpperCase() + request.status.slice(1)}</span>
                            </div>
                            <div class="request-card-body">
                                <p class="request-description">${request.message || request.reason || 'No description'}</p>
                            </div>
                            <div class="request-card-footer">
                                <span class="request-date">Date: ${request.date || ''}</span>
                                <span class="request-id">ID: ${request.id || 'N/A'}</span>
                            </div>
                        `;
                        requestsList.appendChild(requestCard);
                    });
                } else {
                    requestsList.innerHTML = `
                        <div class="request-card" style="text-align: center; padding: var(--spacing-lg); color: var(--text-secondary);">
                            No correction requests found.
                        </div>
                    `;
                }
            } catch (error) {
                console.error('Error loading correction requests:', error);
                // If endpoint doesn't exist, show empty state
                document.getElementById('requestsList').innerHTML = `
                    <div class="request-card" style="text-align: center; padding: var(--spacing-lg); color: var(--text-secondary);">
                        No correction requests found.
                    </div>
                `;
            }
        }

        // Load requests on page load
        loadCorrectionRequests();
    </script>
</body>

</html>

