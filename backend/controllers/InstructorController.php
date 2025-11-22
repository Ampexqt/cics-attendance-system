<?php

/**
 * Instructor Controller
 * CICS Attendance System
 */

require_once __DIR__ . '/../models/Instructor.php';
require_once __DIR__ . '/../models/Attendance.php';
require_once __DIR__ . '/../models/Subject.php';
require_once __DIR__ . '/../models/CorrectionRequest.php';
require_once __DIR__ . '/../middleware/Auth.php';
require_once __DIR__ . '/../utils/Response.php';
require_once __DIR__ . '/../utils/Validator.php';

class InstructorController
{
    private $instructorModel;
    private $attendanceModel;
    private $subjectModel;
    private $correctionRequestModel;

    public function __construct()
    {
        $this->instructorModel = new Instructor();
        $this->attendanceModel = new Attendance();
        $this->subjectModel = new Subject();
        $this->correctionRequestModel = new CorrectionRequest();
    }

    public function getDashboardStats()
    {
        Auth::requireRole('instructor');

        $userId = Auth::userId();
        $instructor = $this->instructorModel->findByUserId($userId);

        if (!$instructor) {
            Response::error('Instructor record not found', null, 404);
        }

        $instructorId = $instructor['id'];

        // Get active sessions count
        $activeSessions = $this->attendanceModel->getActiveSessionsByInstructor($instructorId);
        $activeSessionsCount = count($activeSessions);

        // Get pending corrections count
        $pendingCorrections = $this->correctionRequestModel->getPendingByInstructor($instructorId);
        $pendingCorrectionsCount = count($pendingCorrections);

        // Get subjects assigned count
        $subjectsCount = $this->instructorModel->getSubjectsCount($instructorId);

        // Get sections handling count
        $sectionsCount = $this->instructorModel->getSectionsCount($instructorId);

        // Get today's classes count
        $todayClassesCount = $this->instructorModel->getTodayClassesCount($instructorId);

        Response::success('Dashboard statistics retrieved', [
            'active_sessions' => $activeSessionsCount,
            'pending_corrections' => $pendingCorrectionsCount,
            'subjects_assigned' => $subjectsCount,
            'sections_handling' => $sectionsCount,
            'today_classes' => $todayClassesCount
        ]);
    }

    public function getActiveSessions()
    {
        Auth::requireRole('instructor');

        $userId = Auth::userId();
        $instructor = $this->instructorModel->findByUserId($userId);

        if (!$instructor) {
            Response::error('Instructor record not found', null, 404);
        }

        $sessions = $this->attendanceModel->getActiveSessionsByInstructor($instructor['id']);

        Response::success('Active sessions retrieved', $sessions);
    }

    public function getAttendanceLogs()
    {
        Auth::requireRole('instructor');

        $userId = Auth::userId();
        $instructor = $this->instructorModel->findByUserId($userId);

        if (!$instructor) {
            Response::error('Instructor record not found', null, 404);
        }

        $filters = $_GET;
        $logs = $this->attendanceModel->getInstructorAttendanceLogs($instructor['id'], $filters);

        Response::success('Attendance logs retrieved', $logs);
    }

    public function getCorrectionRequests()
    {
        Auth::requireRole('instructor');

        $userId = Auth::userId();
        $instructor = $this->instructorModel->findByUserId($userId);

        if (!$instructor) {
            Response::error('Instructor record not found', null, 404);
        }

        $requests = $this->correctionRequestModel->getPendingByInstructor($instructor['id']);

        Response::success('Correction requests retrieved', $requests);
    }

    public function startSession()
    {
        Auth::requireRole('instructor');

        $data = json_decode(file_get_contents('php://input'), true);
        $userId = Auth::userId();
        $instructor = $this->instructorModel->findByUserId($userId);

        if (!$instructor) {
            Response::error('Instructor record not found', null, 404);
        }

        // Validate input
        $errors = Validator::validate($data, [
            'subject_id' => 'required|numeric'
        ]);

        if (!empty($errors)) {
            Response::validationError($errors);
        }

        // Verify the subject belongs to this instructor
        $subject = $this->subjectModel->findById($data['subject_id']);
        if (!$subject || $subject['instructor_id'] != $instructor['id']) {
            Response::error('Subject not found or does not belong to you', null, 403);
        }

        // Check if there's already an active session for this subject today
        $existingSession = $this->attendanceModel->getActiveSession($data['subject_id']);
        if ($existingSession) {
            Response::error('An active session already exists for this subject', null, 400);
        }

        // Create the session
        $sessionId = $this->attendanceModel->createSession([
            'subject_id' => $data['subject_id'],
            'instructor_id' => $instructor['id'],
            'session_date' => date('Y-m-d'),
            'start_time' => date('H:i:s'),
            'gps_latitude' => $data['latitude'] ?? null,
            'gps_longitude' => $data['longitude'] ?? null
        ]);

        Response::success('Session started successfully', [
            'session_id' => $sessionId
        ]);
    }

    public function endSession()
    {
        Auth::requireRole('instructor');

        $data = json_decode(file_get_contents('php://input'), true);
        $userId = Auth::userId();
        $instructor = $this->instructorModel->findByUserId($userId);

        if (!$instructor) {
            Response::error('Instructor record not found', null, 404);
        }

        // Validate input
        $errors = Validator::validate($data, [
            'session_id' => 'required|numeric'
        ]);

        if (!empty($errors)) {
            Response::validationError($errors);
        }

        // Verify the session belongs to this instructor
        if (!$this->attendanceModel->validateSessionOwnership($data['session_id'], $instructor['id'])) {
            Response::error('Session not found or does not belong to you', null, 403);
        }

        // End the session
        $this->attendanceModel->endSession($data['session_id']);

        Response::success('Session ended successfully');
    }

    public function getSubjects()
    {
        Auth::requireRole('instructor');

        $userId = Auth::userId();
        $instructor = $this->instructorModel->findByUserId($userId);

        if (!$instructor) {
            Response::error('Instructor record not found', null, 404);
        }

        $subjects = $this->subjectModel->getAll(['instructor_id' => $instructor['id']]);

        Response::success('Subjects retrieved', $subjects);
    }

    public function getSections()
    {
        Auth::requireRole('instructor');

        $userId = Auth::userId();
        $instructor = $this->instructorModel->findByUserId($userId);

        if (!$instructor) {
            Response::error('Instructor record not found', null, 404);
        }

        // Get unique sections from subjects
        $subjects = $this->subjectModel->getAll(['instructor_id' => $instructor['id']]);
        $sections = [];

        foreach ($subjects as $subject) {
            $section = $subject['program'] . '-' . $subject['section'];
            if (!in_array($section, $sections)) {
                $sections[] = $section;
            }
        }

        Response::success('Sections retrieved', $sections);
    }
}
