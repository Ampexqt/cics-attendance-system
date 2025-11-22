/**
 * Instructor Dashboard JavaScript
 * CICS Attendance System
 */

const API_BASE = '/cics-attendance-system/backend/api';

// Utility function to make API calls
async function apiCall(endpoint, method = 'GET', data = null) {
    const options = {
        method,
        headers: {
            'Content-Type': 'application/json'
        },
        credentials: 'include'
    };

    if (data && method !== 'GET') {
        options.body = JSON.stringify(data);
    }

    try {
        const response = await fetch(`${API_BASE}${endpoint}`, options);
        const result = await response.json();

        if (!result.success) {
            throw new Error(result.message || 'API request failed');
        }

        return result.data;
    } catch (error) {
        console.error('API Error:', error);
        throw error;
    }
}

// Show loading state
function showLoading(elementId) {
    const element = document.getElementById(elementId);
    if (element) {
        element.innerHTML = '<div class="loading">Loading...</div>';
    }
}

// Show error message
function showError(elementId, message) {
    const element = document.getElementById(elementId);
    if (element) {
        element.innerHTML = `<div class="error-message">${message}</div>`;
    }
}

// Load dashboard statistics
async function loadDashboardStats() {
    try {
        const stats = await apiCall('/instructor/dashboard-stats');

        // Update stat cards
        document.querySelector('[data-stat="active-sessions"]').textContent = stats.active_sessions || 0;
        document.querySelector('[data-stat="pending-corrections"]').textContent = stats.pending_corrections || 0;
        document.querySelector('[data-stat="subjects-assigned"]').textContent = stats.subjects_assigned || 0;
        document.querySelector('[data-stat="sections-handling"]').textContent = stats.sections_handling || 0;
        document.querySelector('[data-stat="today-classes"]').textContent = stats.today_classes || 0;
    } catch (error) {
        console.error('Failed to load dashboard stats:', error);
    }
}

// Load active sessions
async function loadActiveSessions() {
    const tbody = document.getElementById('active-sessions-tbody');

    try {
        const sessions = await apiCall('/instructor/active-sessions');

        if (sessions.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" style="text-align: center;">No active sessions</td></tr>';
            return;
        }

        tbody.innerHTML = sessions.map(session => `
            <tr>
                <td>${session.subject_name}</td>
                <td>${session.section}</td>
                <td>${formatTime(session.start_time)} - ${session.end_time ? formatTime(session.end_time) : 'Ongoing'}</td>
                <td>${session.room || 'N/A'}</td>
                <td><span class="status-badge active">Active</span></td>
                <td>
                    <button class="btn btn-primary btn-sm" onclick="endSession(${session.id})">
                        End Session
                    </button>
                </td>
            </tr>
        `).join('');
    } catch (error) {
        console.error('Failed to load active sessions:', error);
        tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; color: red;">Failed to load sessions</td></tr>';
    }
}

// End a session
async function endSession(sessionId) {
    if (!confirm('Are you sure you want to end this session?')) {
        return;
    }

    try {
        await apiCall('/instructor/end-session', 'POST', { session_id: sessionId });

        // Reload active sessions and stats
        await Promise.all([
            loadActiveSessions(),
            loadDashboardStats()
        ]);

        alert('Session ended successfully');
    } catch (error) {
        alert('Failed to end session: ' + error.message);
    }
}

// Load attendance logs
async function loadAttendanceLogs() {
    const tbody = document.getElementById('attendance-logs-tbody');

    try {
        // Get filter values
        const filters = {
            subject_id: document.getElementById('filter-subject')?.value || '',
            section: document.getElementById('filter-section')?.value || '',
            date: document.getElementById('filter-date')?.value || '',
            search: document.getElementById('filter-search')?.value || ''
        };

        // Build query string
        const queryParams = new URLSearchParams();
        Object.keys(filters).forEach(key => {
            if (filters[key]) {
                queryParams.append(key, filters[key]);
            }
        });

        const logs = await apiCall(`/instructor/attendance-logs?${queryParams.toString()}`);

        if (logs.length === 0) {
            tbody.innerHTML = '<tr><td colspan="4" style="text-align: center;">No attendance records found</td></tr>';
            return;
        }

        tbody.innerHTML = logs.map(log => `
            <tr>
                <td>${log.student_name}</td>
                <td>${formatDateTime(log.time_in)}</td>
                <td><span class="status-badge ${log.status}">${capitalize(log.status)}</span></td>
                <td>${log.notes || '-'}</td>
            </tr>
        `).join('');
    } catch (error) {
        console.error('Failed to load attendance logs:', error);
        tbody.innerHTML = '<tr><td colspan="4" style="text-align: center; color: red;">Failed to load logs</td></tr>';
    }
}

// Load correction requests
async function loadCorrectionRequests() {
    const tbody = document.getElementById('correction-requests-tbody');

    try {
        const requests = await apiCall('/instructor/correction-requests');

        if (requests.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" style="text-align: center;">No pending correction requests</td></tr>';
            return;
        }

        tbody.innerHTML = requests.map(request => `
            <tr>
                <td>${request.student_name}</td>
                <td>${request.subject_name}</td>
                <td>${formatDate(request.session_date)}</td>
                <td>Change to ${capitalize(request.requested_status)}</td>
                <td><span class="status-badge pending">Pending</span></td>
                <td>
                    <div class="action-buttons">
                        <button class="btn-action btn-approve" onclick="approveRequest(${request.id})">Approve</button>
                        <button class="btn-action btn-reject" onclick="rejectRequest(${request.id})">Reject</button>
                        <button class="btn btn-outline btn-sm" onclick="viewRequestDetails(${request.id})">View</button>
                    </div>
                </td>
            </tr>
        `).join('');
    } catch (error) {
        console.error('Failed to load correction requests:', error);
        tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; color: red;">Failed to load requests</td></tr>';
    }
}

// Load subjects for filter dropdown
async function loadSubjectsFilter() {
    try {
        const subjects = await apiCall('/instructor/subjects');
        const select = document.getElementById('filter-subject');

        if (select) {
            select.innerHTML = '<option value="">All Subjects</option>' +
                subjects.map(subject => `
                    <option value="${subject.id}">${subject.name}</option>
                `).join('');
        }
    } catch (error) {
        console.error('Failed to load subjects:', error);
    }
}

// Load sections for filter dropdown
async function loadSectionsFilter() {
    try {
        const sections = await apiCall('/instructor/sections');
        const select = document.getElementById('filter-section');

        if (select) {
            select.innerHTML = '<option value="">All Sections</option>' +
                sections.map(section => `
                    <option value="${section}">${section}</option>
                `).join('');
        }
    } catch (error) {
        console.error('Failed to load sections:', error);
    }
}

// Utility functions
function formatTime(time) {
    if (!time) return '';
    const [hours, minutes] = time.split(':');
    const hour = parseInt(hours);
    const ampm = hour >= 12 ? 'PM' : 'AM';
    const displayHour = hour % 12 || 12;
    return `${displayHour}:${minutes} ${ampm}`;
}

function formatDate(date) {
    if (!date) return '';
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}

function formatDateTime(datetime) {
    if (!datetime) return '';
    const date = new Date(datetime);
    return date.toLocaleString('en-US', {
        month: 'short',
        day: 'numeric',
        hour: 'numeric',
        minute: '2-digit',
        hour12: true
    });
}

function capitalize(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
}

// Initialize dashboard on page load
document.addEventListener('DOMContentLoaded', async function () {
    // Load all data
    await Promise.all([
        loadDashboardStats(),
        loadActiveSessions(),
        loadAttendanceLogs(),
        loadCorrectionRequests(),
        loadSubjectsFilter(),
        loadSectionsFilter()
    ]);

    // Set up filter event listeners
    const filterSubject = document.getElementById('filter-subject');
    const filterSection = document.getElementById('filter-section');
    const filterDate = document.getElementById('filter-date');
    const filterSearch = document.getElementById('filter-search');

    if (filterSubject) filterSubject.addEventListener('change', loadAttendanceLogs);
    if (filterSection) filterSection.addEventListener('change', loadAttendanceLogs);
    if (filterDate) filterDate.addEventListener('change', loadAttendanceLogs);
    if (filterSearch) {
        // Debounce search input
        let searchTimeout;
        filterSearch.addEventListener('input', function () {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(loadAttendanceLogs, 500);
        });
    }

    // Refresh active sessions every 30 seconds
    setInterval(async () => {
        await loadActiveSessions();
        await loadDashboardStats();
    }, 30000);
});
