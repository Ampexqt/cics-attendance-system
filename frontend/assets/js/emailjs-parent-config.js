/**
 * EmailJS Configuration for Parent Attendance Notifications
 * CICS Attendance System
 * 
 * ✅ CONFIGURED - Ready to use!
 */

window.EMAILJS_PARENT_CONFIG = {
    // Your EmailJS Service ID (from EmailJS Dashboard → Email Services)
    serviceId: 'service_2dr6r2e',

    // Your EmailJS Public Key (from EmailJS Dashboard → Account → General)
    publicKey: '2VclqPtJ0av9LLc9-',

    // Email Template IDs for different notification types
    templates: {
        // Template for when student attended (present or late)
        attended: 'template_s3xyad9',

        // Template for when student was absent
        absent: 'template_xgdr6y7',

        // Template for general session summary (using attended template)
        summary: 'template_s3xyad9'
    },

    // Feature flags
    enabled: true,                    // Set to false to disable email notifications
    sendOnlyForAbsent: false,         // Set to true to only send emails for absent students
    sendSummaryForAll: false,         // Set to true to send summary emails to all parents

    // Email sending options
    retryAttempts: 3,                 // Number of retry attempts if email fails
    retryDelay: 2000,                 // Delay between retries in milliseconds

    // Debug mode
    debug: false                      // Set to true to log non-sensitive email details
};

/**
 * Validate configuration
 * This will warn you if the configuration is not properly set up
 */
(function validateConfig() {
    const config = window.EMAILJS_PARENT_CONFIG;

    if (!config.enabled) {
        console.info('[EmailJS Parent] Email notifications are disabled');
        return;
    }

    const hasPlaceholders =
        config.serviceId.includes('YOUR_') ||
        config.publicKey.includes('YOUR_') ||
        config.templates.attended.includes('YOUR_') ||
        config.templates.absent.includes('YOUR_');

    if (hasPlaceholders) {
        console.warn(
            '[EmailJS Parent] ⚠️ EmailJS is not configured yet!\n' +
            'Please update frontend/assets/js/emailjs-parent-config.js with your EmailJS credentials.\n' +
            'See PARENT_EMAIL_SETUP_GUIDE.md for detailed instructions.'
        );
    } else {
        const maskValue = (value = '') => {
            if (value.length <= 6) return '***';
            return `${value.substring(0, 3)}***${value.substring(value.length - 3)}`;
        };

        console.log('[EmailJS Parent] ✅ Configuration loaded successfully');

        if (config.debug) {
            console.log('[EmailJS Parent] Service ID:', maskValue(config.serviceId));
            console.log('[EmailJS Parent] Public Key:', maskValue(config.publicKey));
            console.log('[EmailJS Parent] Templates:', Object.keys(config.templates || {}));
        }
    }
})();
