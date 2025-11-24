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

class AttendanceController {
    private $attendanceModel;
    private $studentModel;
    private $instructorModel;
    private $subjectModel;
    
    public function __construct() {
        $this->attendanceModel = new Attendance();
        $this->studentModel = new Student();
        $this->instructorModel = new Instructor();
        $this->subjectModel = new Subject();
    }
    
    public function markAttendance() {
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
            'longitude' => 'required|numeric'
        ]);
        
        if (!empty($errors)) {
            Response::validationError($errors);
        }
        
        // Check GPS location
        if (!Helper::isWithinCampus($data['latitude'], $data['longitude'])) {
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
        
        // Determine status
        $sessionDateTime = $session['session_date'] . ' ' . $session['start_time'];
        $status = Helper::getAttendanceStatus(Helper::now(), $sessionDateTime);
        
        // Mark attendance
        $attendanceId = $this->attendanceModel->markAttendance([
            'session_id' => $data['session_id'],
            'student_id' => $student['id'],
            'time_in' => Helper::now(),
            'status' => $status,
            'gps_latitude' => $data['latitude'],
            'gps_longitude' => $data['longitude'],
            'device_fingerprint' => Helper::generateDeviceFingerprint()
        ]);
        
        Response::success('Attendance marked successfully', [
            'attendance_id' => $attendanceId,
            'status' => $status,
            'time_in' => Helper::now()
        ]);
    }
    
    public function markTimeOut() {
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
    
    public function getRecords() {
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
    
    public function getSummary() {
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

    public function startSession() {
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

        $now = Helper::now();
        $sessionDate = date('Y-m-d', strtotime($now));
        $sessionTime = date('H:i:s', strtotime($now));

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
    }

    public function endSession() {
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

        Response::success('Attendance session ended successfully');
    }
}

