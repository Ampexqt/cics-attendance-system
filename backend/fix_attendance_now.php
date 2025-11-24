<?php

/**
 * Emergency fix script to reset attendance time_in to current time
 * Run this once, then refresh the instructor page
 */

require_once __DIR__ . '/database/Database.php';

$db = Database::getInstance();

// Get all attendance records for today's active sessions
$records = $db->fetchAll("
    SELECT ar.id, ar.time_in, ar.student_id, s.first_name, s.last_name
    FROM attendance_records ar
    JOIN attendance_sessions ases ON ar.session_id = ases.id
    JOIN students s ON ar.student_id = s.id
    WHERE ases.status = 'active'
      AND ases.session_date = CURDATE()
");

echo "<h2>Found " . count($records) . " attendance record(s) for today's active sessions</h2>";

foreach ($records as $record) {
    $oldTimeIn = $record['time_in'];
    $minutesAgo = round((time() - strtotime($oldTimeIn)) / 60);

    echo "<p>";
    echo "Student: {$record['first_name']} {$record['last_name']}<br>";
    echo "Old time_in: {$oldTimeIn} ({$minutesAgo} minutes ago)<br>";

    if ($minutesAgo > 60) {
        // Update to current time
        $newTimeIn = date('Y-m-d H:i:s');
        $db->query("UPDATE attendance_records SET time_in = :time_in WHERE id = :id", [
            ':time_in' => $newTimeIn,
            ':id' => $record['id']
        ]);
        echo "<strong style='color: green;'>✓ FIXED! New time_in: {$newTimeIn}</strong>";
    } else {
        echo "<span style='color: blue;'>OK - Recent attendance</span>";
    }

    echo "</p>";
}

echo "<hr>";
echo "<p><strong>Done! Refresh the instructor's active sessions page now.</strong></p>";
