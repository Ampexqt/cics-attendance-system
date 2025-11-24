<?php
// backend/debug_start_session.php

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Starting debug script...\n";

try {
    // 1. Load Dependencies
    echo "Loading dependencies...\n";
    require_once __DIR__ . '/database/Database.php';
    require_once __DIR__ . '/models/Attendance.php';
    require_once __DIR__ . '/models/Instructor.php';
    require_once __DIR__ . '/models/Subject.php';
    require_once __DIR__ . '/utils/Helper.php';
    require_once __DIR__ . '/middleware/Auth.php'; // Added Auth
    require_once __DIR__ . '/config/app.php';
    echo "Dependencies loaded.\n";

    // Test Auth
    echo "Testing Auth::startSession()...\n";
    Auth::startSession();
    echo "Session started.\n";

    echo "Testing Auth::userId()...\n";
    $userId = Auth::userId();
    echo "User ID from session: " . ($userId ? $userId : 'NULL') . "\n";

    // 2. Mock Data (Replace with valid IDs from your database)
    // You might need to adjust these IDs based on your actual data
    $subjectId = 1; // Example Subject ID
    $instructorId = 1; // Example Instructor ID (associated with the subject)

    echo "Initializing models...\n";
    $attendanceModel = new Attendance();
    $instructorModel = new Instructor();
    $subjectModel = new Subject();
    echo "Models initialized.\n";

    // 3. Test Instructor Retrieval
    echo "Fetching instructor (ID: $instructorId)...\n";
    $instructor = $instructorModel->findById($instructorId);
    if (!$instructor) {
        echo "ERROR: Instructor not found.\n";
    } else {
        echo "Instructor found: " . $instructor['first_name'] . "\n";
    }

    // 4. Test Subject Retrieval
    echo "Fetching subject (ID: $subjectId)...\n";
    $subject = $subjectModel->findById($subjectId);
    if (!$subject) {
        echo "ERROR: Subject not found.\n";
    } else {
        echo "Subject found: " . $subject['code'] . "\n";
    }

    // 5. Test Helper Functions
    echo "Testing Helper::now()...\n";
    $now = Helper::now();
    echo "Current time: $now\n";

    echo "Testing Helper::getScheduleWindowForDate()...\n";
    if ($subject && !empty($subject['schedule'])) {
        $sessionDate = date('Y-m-d', strtotime($now));
        $window = Helper::getScheduleWindowForDate($subject['schedule'], $sessionDate);
        echo "Schedule Window: " . json_encode($window) . "\n";
    } else {
        echo "Skipping schedule test (no subject or schedule).\n";
    }

    // 6. Test Create Session (Dry Run - Transaction Rollback if possible, or just check SQL)
    echo "Testing createSession method existence...\n";
    if (method_exists($attendanceModel, 'createSession')) {
        echo "Method createSession exists.\n";
    } else {
        echo "ERROR: Method createSession does NOT exist.\n";
    }

    echo "Debug script completed successfully.\n";
} catch (Throwable $e) {
    echo "FATAL ERROR CAUGHT:\n";
    echo $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
