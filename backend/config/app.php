<?php
/**
 * Application Configuration
 * CICS Attendance System
 */

return [
    'app_name' => 'CICS Attendance System',
    'app_version' => '1.0.0',
    'base_url' => 'http://localhost/cics-attendance-system',
    'timezone' => 'Asia/Manila',
    
    // Session Configuration
    'session' => [
        'lifetime' => 7200, // 2 hours
        'name' => 'cics_session',
        'secure' => false, // Set to true in production with HTTPS
        'httponly' => true,
    ],
    
    // JWT Configuration (if using JWT)
    'jwt' => [
        'secret' => 'your-secret-key-change-in-production',
        'algorithm' => 'HS256',
        'expiration' => 7200, // 2 hours
    ],
    
    // Email Configuration
    'email' => [
        'smtp_host' => 'smtp.gmail.com',
        'smtp_port' => 587,
        'smtp_username' => '',
        'smtp_password' => '',
        'from_email' => 'noreply@zppsu.edu',
        'from_name' => 'CICS Attendance System',
    ],
    
    // GPS/Campus Location
    'campus' => [
        'latitude' => 7.1117,
        'longitude' => 122.0735,
        'radius' => 100, // meters
    ],
    
    // Attendance Settings
    'attendance' => [
        'late_threshold' => 15, // minutes
        'absent_threshold' => 30, // minutes
        'allow_override' => true,
        'session_grace_before' => 30, // minutes instructors can start early
        'session_grace_after' => 30, // minutes instructors can extend late
    ],
];

