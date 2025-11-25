/**
 * Parent Email Notification Utility
 * Handles sending attendance notifications to parents via EmailJS
 * CICS Attendance System
 */

class ParentEmailNotifier {
    constructor() {
        this.config = window.EMAILJS_PARENT_CONFIG || {};
        this.initialized = false;
        this.emailQueue = [];
        this.sending = false;
    }

    /**
     * Initialize EmailJS
     */
    async init() {
        if (this.initialized) return true;

        if (!this.config.enabled) {
            console.info('[ParentEmailNotifier] Email notifications are disabled');
            return false;
        }

        // Check if EmailJS library is loaded
        if (typeof emailjs === 'undefined') {
            console.error('[ParentEmailNotifier] EmailJS library not loaded');
            return false;
        }

        // Check if configuration is valid
        if (!this.isConfigValid()) {
            console.warn('[ParentEmailNotifier] EmailJS configuration is incomplete');
            return false;
        }

        try {
            emailjs.init(this.config.publicKey);
            this.initialized = true;

            if (this.config.debug) {
                console.log('[ParentEmailNotifier] Initialized successfully');
            }

            return true;
        } catch (error) {
            console.error('[ParentEmailNotifier] Initialization failed:', error);
            return false;
        }
    }

    /**
     * Validate configuration
     */
    isConfigValid() {
        return this.config.serviceId &&
            this.config.publicKey &&
            this.config.templates &&
            this.config.templates.attended &&
            this.config.templates.absent &&
            !this.config.serviceId.includes('YOUR_') &&
            !this.config.publicKey.includes('YOUR_');
    }

    /**
     * Send attendance notifications for a session
     * 
     * @param {number} sessionId - The session ID
     * @returns {Promise<Object>} Results of email sending
     */
    async sendSessionNotifications(sessionId) {
        if (!await this.init()) {
            return {
                success: false,
                message: 'EmailJS not properly configured',
                sent: 0,
                failed: 0
            };
        }

        try {
            // Fetch parent and attendance data from backend
            // Use API_BASE if defined, otherwise fallback to project path
            const apiBase = typeof API_BASE !== 'undefined' ? API_BASE : '/cics-attendance-system/backend/api';
            const headers = {};
            const token = localStorage.getItem('token');

            if (token) {
                headers['Authorization'] = `Bearer ${token}`;
            }

            const response = await fetch(`${apiBase}/email/session-notifications?session_id=${sessionId}`, {
                method: 'GET',
                headers,
                credentials: 'include' // ensure PHP session cookies are included
            });

            if (!response.ok) {
                throw new Error('Failed to fetch notification data');
            }

            const data = await response.json();

            if (!data.success || !data.data || data.data.length === 0) {
                return {
                    success: true,
                    message: 'No parents to notify',
                    sent: 0,
                    failed: 0
                };
            }

            // Send emails
            const results = await this.sendBatchEmails(data.data);

            return results;
        } catch (error) {
            console.error('[ParentEmailNotifier] Error sending session notifications:', error);
            return {
                success: false,
                message: error.message,
                sent: 0,
                failed: 0
            };
        }
    }

    /**
     * Send batch emails with rate limiting
     * 
     * @param {Array} emailDataList - Array of email data objects
     * @returns {Promise<Object>} Results
     */
    async sendBatchEmails(emailDataList) {
        let sent = 0;
        let failed = 0;
        const results = [];

        for (const emailData of emailDataList) {
            try {
                const result = await this.sendEmail(emailData);

                if (result.success) {
                    sent++;
                    results.push({ email: emailData.parent_email, status: 'sent' });
                } else {
                    failed++;
                    results.push({ email: emailData.parent_email, status: 'failed', error: result.error });
                }

                // Add delay between emails to avoid rate limiting (500ms)
                await this.delay(500);
            } catch (error) {
                failed++;
                results.push({ email: emailData.parent_email, status: 'failed', error: error.message });
            }
        }

        if (this.config.debug) {
            console.log('[ParentEmailNotifier] Batch results:', { sent, failed, results });
        }

        return {
            success: true,
            message: `Sent ${sent} emails, ${failed} failed`,
            sent,
            failed,
            details: results
        };
    }

    /**
     * Send a single email
     * 
     * @param {Object} emailData - Email template data
     * @returns {Promise<Object>} Result
     */
    async sendEmail(emailData) {
        const templateId = this.getTemplateId(emailData);

        if (!templateId) {
            return {
                success: false,
                error: 'No template ID found for attendance status'
            };
        }

        try {
            // Prepare template parameters
            const templateParams = {
                to_email: emailData.parent_email,
                ...emailData
            };

            if (this.config.debug) {
                console.log('[ParentEmailNotifier] Sending email:', {
                    template: templateId,
                    to: emailData.parent_email,
                    student: emailData.student_name,
                    status: emailData.attendance_status
                });
            }

            // Send email via EmailJS
            const response = await emailjs.send(
                this.config.serviceId,
                templateId,
                templateParams
            );

            // Log to backend
            await this.logNotification(emailData, 'sent');

            if (this.config.debug) {
                console.log('[ParentEmailNotifier] Email sent successfully:', response);
            }

            return {
                success: true,
                response
            };
        } catch (error) {
            console.error('[ParentEmailNotifier] Failed to send email:', error);

            // Log failure to backend
            await this.logNotification(emailData, 'failed');

            return {
                success: false,
                error: error.message || 'Unknown error'
            };
        }
    }

    /**
     * Get the appropriate template ID based on attendance status
     * 
     * @param {Object} emailData - Email data
     * @returns {string|null} Template ID
     */
    getTemplateId(emailData) {
        if (this.config.sendSummaryForAll && this.config.templates.summary) {
            return this.config.templates.summary;
        }

        if (emailData.is_absent) {
            return this.config.templates.absent;
        }

        if (emailData.is_present || emailData.is_late) {
            return this.config.templates.attended;
        }

        return null;
    }

    /**
     * Log notification to backend
     * 
     * @param {Object} emailData - Email data
     * @param {string} status - 'sent' or 'failed'
     */
    async logNotification(emailData, status) {
        try {
            const apiBase = typeof API_BASE !== 'undefined' ? API_BASE : '/cics-attendance-system/backend/api';
            const headers = {
                'Content-Type': 'application/json'
            };
            const token = localStorage.getItem('token');

            if (token) {
                headers['Authorization'] = `Bearer ${token}`;
            }

            await fetch(`${apiBase}/email/log-notification`, {
                method: 'POST',
                headers,
                credentials: 'include', // include cookies for session auth
                body: JSON.stringify({
                    notification_id: emailData.notification_id,
                    status,
                    sent_at: status === 'sent' ? new Date().toISOString() : null
                })
            });
        } catch (error) {
            console.error('[ParentEmailNotifier] Failed to log notification:', error);
        }
    }

    /**
     * Utility: Delay execution
     * 
     * @param {number} ms - Milliseconds to delay
     * @returns {Promise}
     */
    delay(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }

    /**
     * Test email configuration
     * 
     * @param {string} testEmail - Email address to send test to
     * @returns {Promise<Object>} Result
     */
    async testConfiguration(testEmail) {
        if (!await this.init()) {
            return {
                success: false,
                message: 'EmailJS not properly configured'
            };
        }

        const testData = {
            parent_email: testEmail,
            parent_name: 'Test Parent',
            student_name: 'Test Student',
            student_id: 'TEST-001',
            subject_name: 'Test Subject',
            subject_code: 'TEST101',
            instructor_name: 'Test Instructor',
            session_date: new Date().toLocaleDateString(),
            session_time: '10:00 AM - 12:00 PM',
            room: 'Test Room',
            attendance_status: 'Present',
            time_in: '10:05 AM',
            time_out: '11:55 AM',
            is_present: true,
            is_late: false,
            is_absent: false
        };

        try {
            const result = await this.sendEmail(testData);
            return result;
        } catch (error) {
            return {
                success: false,
                message: error.message
            };
        }
    }
}

// Create global instance
window.parentEmailNotifier = new ParentEmailNotifier();

// Auto-initialize on page load
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        window.parentEmailNotifier.init();
    });
} else {
    window.parentEmailNotifier.init();
}
