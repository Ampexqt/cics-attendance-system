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
        
        // If time_in is before or equal to session start time, mark as present
        if ($timeInObj <= $sessionStartObj) {
            return 'present';
        }
        
        // Calculate how many minutes late
        $diff = $timeInObj->diff($sessionStartObj, true); // absolute difference
        $minutesLate = ($diff->h * 60) + $diff->i;

        if ($minutesLate <= $lateThreshold) {
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

    /**
     * Parse a serialized schedule string into structured entries.
     *
     * @param string|null $schedule
     * @return array<int, array<string,string>>
     */
    public static function parseScheduleString(?string $schedule): array
    {
        if (empty($schedule)) {
            return [];
        }

        $entries = array_filter(array_map('trim', explode(';', $schedule)));
        $pattern = '/^(Monday|Tuesday|Wednesday|Thursday|Friday|Saturday|Sunday)\s+(\d{1,2}:\d{2})\s*(AM|PM)\s*-\s*(\d{1,2}:\d{2})\s*(AM|PM)$/i';
        $parsed = [];

        foreach ($entries as $entry) {
            if (!preg_match($pattern, $entry, $matches)) {
                continue;
            }

            $day = ucfirst(strtolower($matches[1]));
            $start24 = self::convertScheduleTimeTo24Hour($matches[2], $matches[3]);
            $end24 = self::convertScheduleTimeTo24Hour($matches[4], $matches[5]);

            if (!$start24 || !$end24) {
                continue;
            }

            $parsed[] = [
                'day' => $day,
                'start_time_12h' => strtoupper(trim($matches[2] . ' ' . $matches[3])),
                'end_time_12h' => strtoupper(trim($matches[4] . ' ' . $matches[5])),
                'start_time' => $start24,
                'end_time' => $end24,
            ];
        }

        return $parsed;
    }

    /**
     * Get the schedule window for a specific calendar date.
     *
     * @param string|null $schedule
     * @param string $date Y-m-d formatted date
     * @return array<string,string>|null
     */
    public static function getScheduleWindowForDate(?string $schedule, string $date): ?array
    {
        $entries = self::parseScheduleString($schedule);
        if (empty($entries)) {
            return null;
        }

        $dayName = date('l', strtotime($date));
        foreach ($entries as $entry) {
            if ($entry['day'] === $dayName) {
                return $entry;
            }
        }

        return null;
    }

    /**
     * Determine if a given timestamp sits within the configured schedule window.
     *
     * @param string|null $schedule
     * @param string|null $dateTime Y-m-d H:i:s timestamp (defaults to now)
     * @param int $graceBefore Minutes allowed before the scheduled start
     * @param int $graceAfter Minutes allowed after the scheduled end
     * @return bool
     */
    public static function isWithinScheduleWindow(?string $schedule, ?string $dateTime = null, int $graceBefore = 0, int $graceAfter = 0): bool
    {
        $dateTimeString = $dateTime ?? self::now();
        $dateTimeObj = new DateTime($dateTimeString);
        $window = self::getScheduleWindowForDate($schedule, $dateTimeObj->format('Y-m-d'));

        if (!$window) {
            return false;
        }

        $start = new DateTime($dateTimeObj->format('Y-m-d') . ' ' . $window['start_time']);
        $end = new DateTime($dateTimeObj->format('Y-m-d') . ' ' . $window['end_time']);

        if ($graceBefore > 0) {
            $start->modify("-{$graceBefore} minutes");
        }

        if ($graceAfter > 0) {
            $end->modify("+{$graceAfter} minutes");
        }

        return $dateTimeObj >= $start && $dateTimeObj <= $end;
    }

    /**
     * Convert a 12-hour time token into 24-hour (HH:MM:SS) format.
     *
     * @param string $time
     * @param string $meridiem
     * @return string|null
     */
    private static function convertScheduleTimeTo24Hour(string $time, string $meridiem): ?string
    {
        $dateTime = DateTime::createFromFormat('g:i A', strtoupper(trim($time . ' ' . $meridiem)));
        return $dateTime ? $dateTime->format('H:i:s') : null;
    }
}
