<?php

/**
 * Test endpoint to debug student active session
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../database/Database.php';
require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../models/Attendance.php';
require_once __DIR__ . '/../models/Subject.php';
require_once __DIR__ . '/../middleware/Auth.php';

header('Content-Type: application/json');

try {
    Auth::startSession();

    if (!Auth::check()) {
        echo json_encode(['error' => 'Not authenticated']);
        exit;
    }

    $userId = Auth::userId();
    $studentModel = new Student();
    $student = $studentModel->findByUserId($userId);

    if (!$student) {
        echo json_encode(['error' => 'Student not found', 'user_id' => $userId]);
        exit;
    }

    $result = [
        'student' => [
            'id' => $student['id'],
            'student_id' => $student['student_id'],
            'name' => $student['first_name'] . ' ' . $student['last_name'],
            'program' => $student['program'],
            'year_level' => $student['year_level'],
            'section' => $student['section']
        ]
    ];

    // Check for active sessions
    $attendanceModel = new Attendance();
    $db = Database::getInstance();

    // Get all active sessions today
    $allSessions = $db->fetchAll("
        SELECT ases.*, subj.program, subj.year_level, subj.section, subj.name as subject_name, subj.code
        FROM attendance_sessions ases
        LEFT JOIN subjects subj ON ases.subject_id = subj.id
        WHERE ases.status = 'active' AND ases.session_date = CURDATE()
    ");

    $result['all_active_sessions'] = $allSessions;

    // Try to find matching session
    if (!empty($student['program']) && !empty($student['year_level']) && !empty($student['section'])) {
        $session = $attendanceModel->getActiveSessionForClass(
            (string)$student['program'],
            (int)$student['year_level'],
            (string)$student['section']
        );

        $result['matching_session'] = $session;

        if ($session) {
            $subjectModel = new Subject();
            $subject = $subjectModel->findById($session['subject_id']);
            $result['subject_details'] = $subject;
        }
    } else {
        $result['error'] = 'Student enrollment details incomplete';
    }

    echo json_encode($result, JSON_PRETTY_PRINT);
} catch (Exception $e) {
    echo json_encode([
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ], JSON_PRETTY_PRINT);
}
