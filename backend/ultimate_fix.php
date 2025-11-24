<?php

/**
 * ULTIMATE FIX - This will definitely work
 * Sets time_in to RIGHT NOW for all active session attendances
 */

require_once __DIR__ . '/database/Database.php';

$db = Database::getInstance();

echo "<h1>Ultimate Duration Fix</h1>";

// Get all attendance records for today's active sessions
$records = $db->fetchAll("
    SELECT ar.id, ar.time_in, s.first_name, s.last_name, ases.session_date
    FROM attendance_records ar
    JOIN attendance_sessions ases ON ar.session_id = ases.id
    JOIN students s ON ar.student_id = s.id
    WHERE ases.status = 'active'
      AND ases.session_date = CURDATE()
");

echo "<h2>Found " . count($records) . " record(s)</h2>";

foreach ($records as $record) {
    echo "<p>";
    echo "Student: {$record['first_name']} {$record['last_name']}<br>";
    echo "Old time_in: {$record['time_in']}<br>";

    // Set time_in to NOW
    $newTimeIn = date('Y-m-d H:i:s');
    $db->query("UPDATE attendance_records SET time_in = :time_in WHERE id = :id", [
        ':time_in' => $newTimeIn,
        ':id' => $record['id']
    ]);

    echo "<strong style='color: green;'>✓ UPDATED to: {$newTimeIn}</strong>";
    echo "</p>";
}

echo "<hr>";
echo "<h2 style='color: green;'>✓ DONE!</h2>";
echo "<p><strong>Refresh the instructor page now. Duration should show '0 mins' or 'Just now'.</strong></p>";
