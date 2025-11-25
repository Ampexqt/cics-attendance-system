<?php

/**
 * Attendance Controller
 * CICS Attendance System
 */

require_once __DIR__ . '/../models/Attendance.php';
require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../models/Instructor.php';
require_once __DIR__ . '/../models/Subject.php';
require_once __DIR__ . '/../middleware/Auth.php';
require_once __DIR__ . '/../utils/Response.php';
require_once __DIR__ . '/../utils/Validator.php';
require_once __DIR__ . '/../utils/Helper.php';

class AttendanceController
{
    private $attendanceModel;
    private $studentModel;
    private $instructorModel;
    private $subjectModel;

    public function __construct()
    {
        $this->attendanceModel = new Attendance();
        $this->studentModel = new Student();
        $this->instructorModel = new Instructor();
        $this->subjectModel = new Subject();
    }

    public function markAttendance()
    {
        Auth::requireRole('student');

        $data = json_decode(file_get_contents('php://input'), true);
        $userId = Auth::userId();
        $student = $this->studentModel->findByUserId($userId);

        if (!$student) {
            Response::error('Student record not found', null, 404);
        }

        // Validate input
        $errors = Validator::validate($data, [
            'session_id' => 'required|numeric',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'accuracy' => 'numeric' // Optional but validated if present
        ]);

        if (!empty($errors)) {
            Response::validationError($errors);
        }

        $config = require __DIR__ . '/../config/app.php';
        $now = Helper::now();

        // Check GPS location
        $isWithinCampus = Helper::isWithinCampus($data['latitude'], $data['longitude']);
        $allowOverride = $config['attendance']['allow_override'] ?? false;

        if (!$isWithinCampus && !$allowOverride) {
            Response::error('You must be on campus to mark attendance', null, 403);
        }

        // Get session details
        $session = $this->attendanceModel->getSessionById($data['session_id']);
        if (!$session) {
            Response::error('Attendance session not found', null, 404);
        }

        if ($session['status'] !== 'active') {
            Response::error('Attendance session is not active', null, 403);
        }

        $subject = $this->subjectModel->findById($session['subject_id']);
        if (!$subject) {
            Response::error('Subject linked to this session was not found', null, 404);
        }

        if (!$this->studentMatchesSubject($student, $subject)) {
            Response::forbidden('This session is not assigned to your class');
        }

        $graceBefore = $config['attendance']['session_grace_before'] ?? 0;
        $graceAfter = $config['attendance']['session_grace_after'] ?? 0;

        if (!Helper::isWithinScheduleWindow($subject['schedule'] ?? null, $now, $graceBefore, $graceAfter)) {
            Response::error('Attendance can only be logged during the scheduled class window', null, 403);
        }

        // Determine status
        $sessionDateTime = $session['session_date'] . ' ' . $session['start_time'];
        $status = Helper::getAttendanceStatus($now, $sessionDateTime);

        // Mark attendance
        $attendanceId = $this->attendanceModel->markAttendance([
            'session_id' => $data['session_id'],
            'student_id' => $student['id'],
            'time_in' => $now,
            'status' => $status,
            'gps_latitude' => $data['latitude'],
            'gps_longitude' => $data['longitude'],
            'gps_accuracy' => $data['accuracy'] ?? null,
            'device_fingerprint' => Helper::generateDeviceFingerprint()
        ]);

        Response::success('Attendance marked successfully', [
            'attendance_id' => $attendanceId,
            'status' => $status,
            'time_in' => $now
        ]);
    }

    public function markTimeOut()
    {
        Auth::requireRole('student');

        $data = json_decode(file_get_contents('php://input'), true);
        $userId = Auth::userId();
        $student = $this->studentModel->findByUserId($userId);

        if (!$student) {
            Response::error('Student record not found', null, 404);
        }

        $errors = Validator::validate($data, [
            'session_id' => 'required|numeric'
        ]);

        if (!empty($errors)) {
            Response::validationError($errors);
        }

        $this->attendanceModel->markTimeOut($data['session_id'], $student['id']);

        Response::success('Time out marked successfully');
    }

    public function getRecords()
    {
        Auth::requireAuth();

        $filters = $_GET;
        $userId = Auth::userId();
        $role = Auth::role();

        // Students can only see their own records
        if ($role === 'student') {
            $student = $this->studentModel->findByUserId($userId);
            if ($student) {
                $filters['student_id'] = $student['id'];
            }
        }

        $records = $this->attendanceModel->getRecords($filters);

        Response::success('Attendance records retrieved', $records);
    }

    public function getSummary()
    {
        Auth::requireAuth();

        $filters = $_GET;
        $userId = Auth::userId();
        $role = Auth::role();

        // Students can only see their own summary
        if ($role === 'student') {
            $student = $this->studentModel->findByUserId($userId);
            if ($student) {
                $summary = $this->studentModel->getAttendanceStats(
                    $student['id'],
                    $filters['start_date'] ?? null,
                    $filters['end_date'] ?? null
                );
                Response::success('Attendance summary retrieved', $summary);
            }
        }

        // Admin/Instructor can see overall summary
        $summary = $this->attendanceModel->getSummary($filters);
        Response::success('Attendance summary retrieved', $summary);
    }

    public function startSession()
    {
        try {
            Auth::requireRole('instructor');

            $data = json_decode(file_get_contents('php://input'), true) ?? [];

            $errors = Validator::validate($data, [
                'subject_id' => 'required|numeric'
            ]);

            if (!empty($errors)) {
                Response::validationError($errors);
            }

            $userId = Auth::userId();
            $instructor = $this->instructorModel->findByUserId($userId);

            if (!$instructor) {
                Response::error('Instructor record not found', null, 404);
            }

            $subject = $this->subjectModel->findById($data['subject_id']);

            if (!$subject) {
                Response::notFound('Subject not found');
            }

            if ((int)$subject['instructor_id'] !== (int)$instructor['id']) {
                Response::forbidden('You are not assigned to this subject');
            }

            $existingSession = $this->attendanceModel->getActiveSession($subject['id']);
            if ($existingSession) {
                Response::error('An active session already exists for this subject', null, 409);
            }

            if (empty($subject['schedule'])) {
                Response::error('This subject does not have a configured schedule', null, 422);
            }

            $config = require __DIR__ . '/../config/app.php';
            $graceBefore = $config['attendance']['session_grace_before'] ?? 0;
            $graceAfter = $config['attendance']['session_grace_after'] ?? 0;

            $now = Helper::now();
            $sessionDate = date('Y-m-d', strtotime($now));
            $sessionTime = date('H:i:s', strtotime($now));
            $todayWindow = Helper::getScheduleWindowForDate($subject['schedule'], $sessionDate);

            if (!$todayWindow) {
                Response::error('This subject is not scheduled for today', null, 422);
            }

            if (!Helper::isWithinScheduleWindow($subject['schedule'], $now, $graceBefore, $graceAfter)) {
                $windowLabel = sprintf(
                    '%s - %s',
                    date('h:i A', strtotime($todayWindow['start_time'])),
                    date('h:i A', strtotime($todayWindow['end_time']))
                );
                Response::error(
                    "You can only start this session during the scheduled window ({$windowLabel}).",
                    null,
                    422
                );
            }

            $sessionId = $this->attendanceModel->createSession([
                'subject_id' => $subject['id'],
                'instructor_id' => $instructor['id'],
                'session_date' => $sessionDate,
                'start_time' => $sessionTime,
                'gps_latitude' => $data['gps_latitude'] ?? null,
                'gps_longitude' => $data['gps_longitude'] ?? null
            ]);

            $session = $this->attendanceModel->getSessionById($sessionId);

            Response::success('Attendance session started successfully', $session);
        } catch (\Throwable $e) {
            error_log("startSession Error: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            Response::error('Server Error: ' . $e->getMessage(), null, 500);
        }
    }

    public function endSession()
    {
        try {
            Auth::requireRole('instructor');

            $data = json_decode(file_get_contents('php://input'), true) ?? [];

            $errors = Validator::validate($data, [
                'session_id' => 'required|numeric'
            ]);

            if (!empty($errors)) {
                Response::validationError($errors);
            }

            $userId = Auth::userId();
            $instructor = $this->instructorModel->findByUserId($userId);

            if (!$instructor) {
                Response::error('Instructor record not found', null, 404);
            }

            $session = $this->attendanceModel->getSessionById($data['session_id']);

            if (!$session) {
                Response::notFound('Attendance session not found');
            }

            if ((int)$session['instructor_id'] !== (int)$instructor['id']) {
                Response::forbidden('You are not authorized to end this session');
            }

            if ($session['status'] !== 'active') {
                Response::error('Attendance session is already ended', null, 409);
            }

            $this->attendanceModel->endSession($session['id']);

            Response::success('Attendance session ended successfully', [
                'session_id' => $session['id']
            ]);
        } catch (\Throwable $e) {
            error_log("endSession Error: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            Response::error('Server Error: ' . $e->getMessage(), null, 500);
        }
    }

    public function getStudentActiveSession()
    {
        try {
            Auth::requireRole('student');

            $userId = Auth::userId();
            $student = $this->studentModel->findByUserId($userId);

            if (!$student) {
                Response::error('Student record not found', null, 404);
            }

            // Ensure student has valid enrollment details
            if (empty($student['program']) || empty($student['year_level']) || empty($student['section'])) {
                Response::success('Student enrollment details incomplete', null);
            }

            $session = $this->attendanceModel->getActiveSessionForClass(
                (string)$student['program'],
                (int)$student['year_level'],
                (string)$student['section']
            );

            if (!$session) {
                Response::success('No active sessions at the moment', null);
            }

            $subject = $this->subjectModel->findById($session['subject_id']);

            if (!$subject) {
                // Handle orphan session gracefully
                Response::success('Active session found but subject details are missing', null);
            }

            // Calculate actual session window from session start/end times
            $sessionStartTime = $session['start_time'];
            $sessionEndTime = $session['end_time'];

            // If session hasn't ended yet, calculate expected end time (start + 2 hours)
            if (!$sessionEndTime) {
                $startDateTime = new DateTime($session['session_date'] . ' ' . $sessionStartTime);
                $endDateTime = clone $startDateTime;
                $endDateTime->modify('+2 hours');
                $sessionEndTime = $endDateTime->format('H:i:s');
            }

            $windowLabel = sprintf(
                '%s - %s',
                date('h:i A', strtotime($sessionStartTime)),
                date('h:i A', strtotime($sessionEndTime))
            );

            // Check for existing attendance record
            $attendanceRecord = $this->attendanceModel->getRecords([
                'student_id' => $userId,
                'session_id' => $session['id']
            ]);

            $attendanceStatus = 'none';
            if (!empty($attendanceRecord)) {
                $record = $attendanceRecord[0];
                if (!empty($record['time_out'])) {
                    $attendanceStatus = 'timed_out';
                } else {
                    $attendanceStatus = 'timed_in';
                }
            }

            Response::success('Active session retrieved', [
                'session' => [
                    'id' => (int) $session['id'],
                    'session_date' => $session['session_date'],
                    'start_time' => $session['start_time'],
                    'end_time' => $session['end_time'],
                    'status' => $session['status']
                ],
                'subject' => [
                    'id' => (int) $subject['id'],
                    'code' => $subject['code'],
                    'name' => $subject['name'],
                    'room' => $subject['room'],
                    'program' => $subject['program'],
                    'year_level' => $subject['year_level'],
                    'section' => $subject['section'],
                    'instructor' => $subject['instructor_name'] ?? null
                ],
                'window' => [
                    'start_time' => $sessionStartTime,
                    'end_time' => $sessionEndTime,
                    'label' => $windowLabel
                ],
                'attendance_status' => $attendanceStatus
            ]);
        } catch (\Throwable $e) {
            error_log("getStudentActiveSession Error: " . $e->getMessage());
            error_log("Student data: " . json_encode([
                'program' => $student['program'] ?? 'NULL',
                'year_level' => $student['year_level'] ?? 'NULL',
                'section' => $student['section'] ?? 'NULL'
            ]));
            error_log("Stack trace: " . $e->getTraceAsString());
            Response::error('Server Error: ' . $e->getMessage(), null, 500);
        }
    }

    /**
     * Ensure the student belongs to the program/year/section of the subject.
     *
     * @param array $student
     * @param array $subject
     * @return bool
     */
    private function studentMatchesSubject(array $student, array $subject): bool
    {
        if (
            !isset($student['program'], $subject['program']) ||
            !isset($student['year_level'], $subject['year_level']) ||
            !isset($student['section'], $subject['section'])
        ) {
            return false;
        }

        $programMatches = strcasecmp($student['program'], $subject['program']) === 0;
        $yearMatches = (int)$student['year_level'] === (int)$subject['year_level'];

        $studentSection = strtoupper(trim($student['section']));
        $subjectSection = strtoupper(trim($subject['section']));

        $sectionMatches = $studentSection === $subjectSection;

        if (!$sectionMatches && $subjectSection !== '') {
            $sectionMatches = substr($studentSection, -strlen($subjectSection)) === $subjectSection;
        }

        if (!$sectionMatches && $subjectSection !== '') {
            $sectionMatches = strpos($studentSection, $subjectSection) !== false;
        }

        return $programMatches && $yearMatches && $sectionMatches;
    }
}
