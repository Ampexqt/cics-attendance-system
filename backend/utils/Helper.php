<?php

/**
 * Helper Functions
 * CICS Attendance System
 */

class Helper
{
    /**
     * Hash password
     */
    public static function hashPassword($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * Verify password
     */
    public static function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }

    /**
     * Validate device fingerprint format
     */
    public static function validateDeviceFingerprint($fingerprint)
    {
        // Check if fingerprint exists and is valid format
        if (empty($fingerprint)) {
            return false;
        }

        // Must be alphanumeric and between 16-64 characters
        if (!preg_match('/^[a-f0-9]{16,64}$/i', $fingerprint)) {
            return false;
        }

        return true;
    }

    /**
     * Generate device fingerprint from server-side data (DEPRECATED - use client-side)
     * This is kept for backward compatibility but should not be used for new implementations
     * Device fingerprints should be generated client-side and sent to server
     */
    public static function generateDeviceFingerprint()
    {
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $acceptLanguage = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '';
        $acceptEncoding = $_SERVER['HTTP_ACCEPT_ENCODING'] ?? '';

        // NOTE: Removed time() to make fingerprint stable per device
        $fingerprint = md5($userAgent . $acceptLanguage . $acceptEncoding);
        return $fingerprint;
    }

    /**
     * Calculate distance between two GPS coordinates (Haversine formula)
     */
    public static function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // meters

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c; // distance in meters
    }

    /**
     * Check if GPS coordinates are within campus radius
     */
    public static function isWithinCampus($latitude, $longitude)
    {
        $config = require __DIR__ . '/../config/app.php';
        $campusLat = $config['campus']['latitude'];
        $campusLon = $config['campus']['longitude'];
        $radius = $config['campus']['radius'];

        $distance = self::calculateDistance($latitude, $longitude, $campusLat, $campusLon);
        return $distance <= $radius;
    }

    /**
     * Determine attendance status based on time
     */
    public static function getAttendanceStatus($timeIn, $sessionStartTime)
    {
        $config = require __DIR__ . '/../config/app.php';
        $lateThreshold = $config['attendance']['late_threshold'];
        $absentThreshold = $config['attendance']['absent_threshold'];

        $timeInObj = new DateTime($timeIn);
        $sessionStartObj = new DateTime($sessionStartTime);
        $diff = $timeInObj->diff($sessionStartObj);
        $minutesLate = ($diff->h * 60) + $diff->i;

        if ($minutesLate <= 0) {
            return 'present';
        } elseif ($minutesLate <= $lateThreshold) {
            return 'late';
        } elseif ($minutesLate <= $absentThreshold) {
            return 'late';
        } else {
            return 'absent';
        }
    }

    /**
     * Format date for display
     */
    public static function formatDate($date, $format = 'Y-m-d H:i:s')
    {
        if (empty($date)) return null;
        $dateObj = new DateTime($date);
        return $dateObj->format($format);
    }

    /**
     * Get current timestamp
     */
    public static function now()
    {
        return date('Y-m-d H:i:s');
    }

    /**
     * Generate random string
     */
    public static function randomString($length = 10)
    {
        return bin2hex(random_bytes($length / 2));
    }
}
