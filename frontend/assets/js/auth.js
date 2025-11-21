/**
 * Authentication Utility
 * CICS Attendance System
 */

const AuthAPI = {
    baseURL: '/cics-attendance-system/backend/api',

    /**
     * Login user with email/student ID and password
     */
    async login(emailOrStudentId, password, deviceFingerprint = null) {
        try {
            const requestBody = {
                email: emailOrStudentId,
                password: password,
                // Note: Backend will determine role from database
            };

            // Include device fingerprint if provided (required for students)
            if (deviceFingerprint) {
                requestBody.deviceFingerprint = deviceFingerprint;
            }

            const response = await fetch(`${this.baseURL}/auth/login`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                credentials: 'include', // Include cookies for session
                body: JSON.stringify(requestBody)
            });

            const data = await response.json();

            if (!response.ok) {
                // Handle different error scenarios
                if (response.status === 401) {
                    throw new Error('Invalid email/student ID or password');
                } else if (response.status === 403) {
                    throw new Error(data.message || 'Access denied');
                } else {
                    throw new Error(data.message || 'Login failed');
                }
            }

            // Store user data in sessionStorage
            if (data.success && data.data) {
                sessionStorage.setItem('user', JSON.stringify(data.data));
                sessionStorage.setItem('isLoggedIn', 'true');
            }

            return data;
        } catch (error) {
            throw error;
        }
    },

    /**
     * Logout current user
     */
    async logout() {
        try {
            await fetch(`${this.baseURL}/auth/logout`, {
                method: 'POST',
                credentials: 'include'
            });
        } catch (error) {
            // Logout error handled silently
        } finally {
            // Clear local session data
            sessionStorage.removeItem('user');
            sessionStorage.removeItem('isLoggedIn');
            window.location.href = '/cics-attendance-system/login.php';
        }
    },

    /**
     * Check if user is authenticated
     */
    isAuthenticated() {
        return sessionStorage.getItem('isLoggedIn') === 'true';
    },

    /**
     * Get current user data
     */
    getUser() {
        const userJson = sessionStorage.getItem('user');
        return userJson ? JSON.parse(userJson) : null;
    },

    /**
     * Redirect to appropriate dashboard based on role
     */
    redirectToDashboard(role) {
        const dashboards = {
            'student': '/cics-attendance-system/frontend/views/student/dashboard.php',
            'instructor': '/cics-attendance-system/frontend/views/instructor/dashboard.php',
            'admin': '/cics-attendance-system/frontend/views/admin/dashboard.php'
        };

        const dashboard = dashboards[role] || dashboards['student'];
        window.location.href = dashboard;
    },

    /**
     * Require authentication (for protected pages)
     */
    requireAuth(allowedRoles = []) {
        if (!this.isAuthenticated()) {
            window.location.href = '/cics-attendance-system/login.php';
            return false;
        }

        if (allowedRoles.length > 0) {
            const user = this.getUser();
            if (!user || !allowedRoles.includes(user.role)) {
                Toast.error('Access denied', 'Insufficient permissions');
                window.location.href = '/cics-attendance-system/login.php';
                return false;
            }
        }

        return true;
    }
};

// Export for use in other scripts
window.AuthAPI = AuthAPI;
