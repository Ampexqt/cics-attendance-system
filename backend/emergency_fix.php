<?php

/**
 * EMERGENCY FIX - Run this ONCE to fix the duration issue
 * This updates stale time_in dates to today while keeping the time
 */

require_once __DIR__ . '/database/Database.php';

$db = Database::getInstance();

echo "<h1>Fixing Time-In Dates</h1>";

// Update stale dates
$result = $db->query("
    UPDATE attendance_records ar
    JOIN attendance_sessions ases ON ar.session_id = ases.id
    SET ar.time_in = CONCAT(CURDATE(), ' ', TIME(ar.time_in))
    WHERE ases.status = 'active'
      AND ases.session_date = CURDATE()
      AND DATE(ar.time_in) != CURDATE()
");

echo "<p style='color: green; font-size: 20px;'><strong>✓ FIXED!</strong></p>";
echo "<p>Refresh the instructor's active sessions page now.</p>";
echo "<p>The duration should now show correctly (counting up from when student marked attendance).</p>";
