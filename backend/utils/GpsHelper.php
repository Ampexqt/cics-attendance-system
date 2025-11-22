<?php

/**
 * GPS Helper Utilities
 * CICS Attendance System
 */

class GpsHelper
{
    /**
     * Calculate distance between two GPS coordinates using Haversine formula
     * 
     * @param float $lat1 Latitude of first point
     * @param float $lon1 Longitude of first point
     * @param float $lat2 Latitude of second point
     * @param float $lon2 Longitude of second point
     * @return float Distance in meters
     */
    public static function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // Earth's radius in meters

        // Convert degrees to radians
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $lat1Rad = deg2rad($lat1);
        $lat2Rad = deg2rad($lat2);

        // Haversine formula
        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos($lat1Rad) * cos($lat2Rad) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius * $c;

        return round($distance, 2); // Return distance in meters, rounded to 2 decimal places
    }

    /**
     * Check if a GPS coordinate is within a specified radius of a center point
     * 
     * @param float $centerLat Center latitude
     * @param float $centerLon Center longitude
     * @param float $pointLat Point latitude to check
     * @param float $pointLon Point longitude to check
     * @param float $radius Radius in meters
     * @return bool True if point is within radius
     */
    public static function isWithinRadius($centerLat, $centerLon, $pointLat, $pointLon, $radius)
    {
        $distance = self::calculateDistance($centerLat, $centerLon, $pointLat, $pointLon);
        return $distance <= $radius;
    }

    /**
     * Validate GPS coordinates
     * 
     * @param float $latitude
     * @param float $longitude
     * @return bool True if valid
     */
    public static function validateCoordinates($latitude, $longitude)
    {
        return (
            is_numeric($latitude) &&
            is_numeric($longitude) &&
            $latitude >= -90 &&
            $latitude <= 90 &&
            $longitude >= -180 &&
            $longitude <= 180
        );
    }

    /**
     * Format distance for display
     * 
     * @param float $meters Distance in meters
     * @return string Formatted distance string
     */
    public static function formatDistance($meters)
    {
        if ($meters < 1000) {
            return round($meters) . ' meters';
        } else {
            return round($meters / 1000, 2) . ' km';
        }
    }
}
