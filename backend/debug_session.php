<?php
// Debug script to check student and session data
require_once __DIR__ . '/database/Database.php';
require_once __DIR__ . '/models/Student.php';
require_once __DIR__ . '/models/Attendance.php';
require_once __DIR__ . '/models/Subject.php';

session_start();

echo "=== DEBUG SESSION DATA ===\n\n";

// Check session
if (isset($_SESSION['user_id'])) {
    echo "User ID: " . $_SESSION['user_id'] . "\n";
    echo "Role: " . ($_SESSION['role'] ?? 'N/A') . "\n";

    if (isset($_SESSION['user_data'])) {
        echo "\nUser Data:\n";
        print_r($_SESSION['user_data']);
    }

    // Get student record
    $studentModel = new Student();
    $student = $studentModel->findByUserId($_SESSION['user_id']);

    if ($student) {
        echo "\n=== STUDENT RECORD ===\n";
        echo "Student ID: " . $student['id'] . "\n";
        echo "Program: " . ($student['program'] ?? 'NULL') . "\n";
        echo "Year Level: " . ($student['year_level'] ?? 'NULL') . "\n";
        echo "Section: " . ($student['section'] ?? 'NULL') . "\n";

        // Try to get active session
        $attendanceModel = new Attendance();
        echo "\n=== SEARCHING FOR ACTIVE SESSION ===\n";
        echo "Looking for: Program={$student['program']}, Year={$student['year_level']}, Section={$student['section']}\n\n";

        try {
            $session = $attendanceModel->getActiveSessionForClass(
                (string)$student['program'],
                (int)$student['year_level'],
                (string)$student['section']
            );

            if ($session) {
                echo "FOUND SESSION:\n";
                print_r($session);

                // Get subject details
                $subjectModel = new Subject();
                $subject = $subjectModel->findById($session['subject_id']);
                echo "\nSUBJECT DETAILS:\n";
                print_r($subject);
            } else {
                echo "NO ACTIVE SESSION FOUND\n";

                // Check all active sessions
                echo "\n=== ALL ACTIVE SESSIONS TODAY ===\n";
                $db = Database::getInstance();
                $allSessions = $db->fetchAll("
                    SELECT ases.*, subj.program, subj.year_level, subj.section, subj.name as subject_name
                    FROM attendance_sessions ases
                    JOIN subjects subj ON ases.subject_id = subj.id
                    WHERE ases.status = 'active' AND ases.session_date = CURDATE()
                ");
                print_r($allSessions);
            }
        } catch (Exception $e) {
            echo "ERROR: " . $e->getMessage() . "\n";
            echo $e->getTraceAsString() . "\n";
        }
    } else {
        echo "No student record found for user_id: " . $_SESSION['user_id'] . "\n";
    }
} else {
    echo "No active session - user not logged in\n";
}
