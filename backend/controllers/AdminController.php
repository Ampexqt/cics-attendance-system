<?php

/**
 * Admin Controller
 * CICS Attendance System
 */

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../models/Instructor.php';
require_once __DIR__ . '/../models/Subject.php';
require_once __DIR__ . '/../models/Attendance.php';
require_once __DIR__ . '/../database/Database.php';
require_once __DIR__ . '/../middleware/Auth.php';
require_once __DIR__ . '/../utils/Response.php';
require_once __DIR__ . '/../utils/Validator.php';

class AdminController
{
    private $userModel;
    private $studentModel;
    private $attendanceModel;
    private $instructorModel;
    private $subjectModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->studentModel = new Student();
        $this->instructorModel = new Instructor();
        $this->subjectModel = new Subject();
        $this->attendanceModel = new Attendance();
    }

    public function approveRegistration()
    {
        Auth::requireAdmin();

        $data = json_decode(file_get_contents('php://input'), true);

        $errors = Validator::validate($data, [
            'user_id' => 'required|numeric',
            'action' => 'required|in:approve,reject'
        ]);

        if (!empty($errors)) {
            Response::validationError($errors);
        }

        // Check if user exists
        $user = $this->userModel->findById($data['user_id']);
        if (!$user) {
            Response::error('User not found', null, 404);
        }

        // Log current status
        error_log("Before update - User ID: {$data['user_id']}, Current Status: {$user['status']}");

        $status = $data['action'] === 'approve' ? 'active' : 'rejected';

        // Update the status
        try {
            $result = $this->userModel->update($data['user_id'], ['status' => $status]);

            // Verify the update
            $updatedUser = $this->userModel->findById($data['user_id']);
            error_log("After update - User ID: {$data['user_id']}, New Status: {$updatedUser['status']}");

            if ($updatedUser['status'] !== $status) {
                error_log("ERROR: Status was not updated correctly!");
                Response::error('Failed to update user status', null, 500);
            }

            Response::success('Registration ' . $data['action'] . 'd successfully', [
                'user_id' => $data['user_id'],
                'new_status' => $status,
                'verified_status' => $updatedUser['status']
            ]);
        } catch (Exception $e) {
            error_log("Exception during approval: " . $e->getMessage());
            Response::error('Failed to update registration: ' . $e->getMessage(), null, 500);
        }
    }

    public function getPendingRegistrations()
    {
        Auth::requireAdmin();

        $users = $this->userModel->getAll('student', 'pending');

        // Get student details for each user
        $result = [];
        foreach ($users as $user) {
            $student = $this->studentModel->findByUserId($user['id']);
            if ($student) {
                // Explicitly preserve user_id for frontend
                $merged = array_merge($user, $student);
                $merged['user_id'] = $user['id']; // Ensure user_id is set to users.id
                $result[] = $merged;
            }
        }

        Response::success('Pending registrations retrieved', $result);
    }

    public function getAllStudents()
    {
        Auth::requireAdmin();

        $filters = $_GET;
        $students = $this->studentModel->getAll($filters);

        Response::success('Students retrieved', $students);
    }

    public function getAllInstructors()
    {
        Auth::requireAdmin();

        $filters = $_GET;
        $instructors = $this->instructorModel->getAll($filters);

        Response::success('Instructors retrieved', $instructors);
    }

    public function createInstructor()
    {
        Auth::requireAdmin();

        $data = json_decode(file_get_contents('php://input'), true) ?? [];

        $errors = Validator::validate($data, [
            'first_name' => 'required|min:2',
            'last_name' => 'required|min:2',
            'department' => 'required|min:2',
            'email' => 'required|email'
        ]);

        if (!empty($errors)) {
            Response::validationError($errors);
        }

        // Ensure email is unique
        if ($this->userModel->findByEmail($data['email'])) {
            Response::validationError(['email' => 'Email is already in use']);
        }

        $plainPassword = $this->generateTempPassword();

        $db = Database::getInstance();

        try {
            $db->beginTransaction();

            $userId = $this->userModel->create([
                'email' => $data['email'],
                'password' => password_hash($plainPassword, PASSWORD_DEFAULT),
                'role' => 'instructor',
                'status' => 'active'
            ]);

            $instructorId = $this->instructorModel->create([
                'user_id' => $userId,
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'department' => $data['department'],
                'employee_id' => $data['employee_id'] ?? null
            ]);

            $db->commit();

            $instructor = $this->instructorModel->findById($instructorId);

            Response::success('Instructor added successfully', [
                'instructor' => $instructor,
                'temp_password' => $plainPassword
            ]);
        } catch (Exception $e) {
            if ($db) {
                $db->rollBack();
            }
            Response::error('Failed to create instructor: ' . $e->getMessage(), null, 500);
        }
    }

    public function updateStudent()
    {
        Auth::requireAdmin();

        $data = json_decode(file_get_contents('php://input'), true);

        $errors = Validator::validate($data, [
            'id' => 'required|numeric'
        ]);

        if (!empty($errors)) {
            Response::validationError($errors);
        }

        $id = $data['id'];
        unset($data['id']);

        $this->studentModel->update($id, $data);

        Response::success('Student updated successfully');
    }

    public function deleteStudent()
    {
        Auth::requireAdmin();

        $data = json_decode(file_get_contents('php://input'), true);

        $errors = Validator::validate($data, [
            'id' => 'required|numeric'
        ]);

        if (!empty($errors)) {
            Response::validationError($errors);
        }

        // Delete user (cascade will delete student)
        $student = $this->studentModel->findById($data['id']);
        if ($student) {
            $db = Database::getInstance();
            $db->query("DELETE FROM users WHERE id = :id", [':id' => $student['user_id']]);
        }

        Response::success('Student deleted successfully');
    }

    public function getDashboardStats()
    {
        Auth::requireAdmin();

        $db = Database::getInstance();

        // Pending approvals
        $pendingCount = $db->fetchOne(
            "SELECT COUNT(*) as count FROM users WHERE role = 'student' AND status = 'pending'"
        )['count'];

        // Active instructors
        $instructorsCount = $db->fetchOne(
            "SELECT COUNT(*) as count FROM users WHERE role = 'instructor' AND status = 'active'"
        )['count'];

        // Total students
        $studentsCount = $db->fetchOne(
            "SELECT COUNT(*) as count FROM users WHERE role = 'student' AND status = 'active'"
        )['count'];

        // Active sessions today
        $sessionsCount = $db->fetchOne(
            "SELECT COUNT(*) as count FROM attendance_sessions WHERE session_date = CURDATE() AND status = 'active'"
        )['count'];

        // Total classes
        $classesCount = $db->fetchOne(
            "SELECT COUNT(DISTINCT subject_id) as count FROM subjects"
        )['count'];

        // Average check-in time
        $avgTime = $db->fetchOne(
            "SELECT TIME_FORMAT(SEC_TO_TIME(AVG(TIME_TO_SEC(TIME(time_in)))), '%h:%i %p') as avg_time 
             FROM attendance_records 
             WHERE DATE(time_in) = CURDATE()"
        );

        Response::success('Dashboard stats retrieved', [
            'pending_approvals' => (int)$pendingCount,
            'active_instructors' => (int)$instructorsCount,
            'students_registered' => (int)$studentsCount,
            'attendance_sessions_today' => (int)$sessionsCount,
            'total_classes' => (int)$classesCount,
            'average_checkin_time' => $avgTime['avg_time'] ?? 'N/A'
        ]);
    }

    public function getAllSubjects()
    {
        Auth::requireAdmin();

        $filters = $_GET;
        $subjects = $this->subjectModel->getAll($filters);

        Response::success('Subjects retrieved', $subjects);
    }

    public function getSubject()
    {
        Auth::requireAdmin();

        $subjectId = $_GET['id'] ?? null;
        if (!$subjectId) {
            Response::error('Subject ID is required', null, 400);
        }

        $subject = $this->subjectModel->findById($subjectId);
        if (!$subject) {
            Response::error('Subject not found', null, 404);
        }

        Response::success('Subject retrieved', $subject);
    }

    public function createSubject()
    {
        Auth::requireAdmin();

        $data = json_decode(file_get_contents('php://input'), true) ?? [];

        $errors = Validator::validate($data, [
            'code' => 'required|max:20',
            'name' => 'required|max:255',
            'instructor_id' => 'required|numeric',
            'program' => 'required|max:50',
            'year_level' => 'required|numeric|min:1|max:4',
            'section' => 'required|max:10',
            'room' => 'max:50'
        ]);

        if (!empty($errors)) {
            Response::validationError($errors);
        }

        // Check if subject code already exists
        if ($this->subjectModel->findByCode($data['code'])) {
            Response::validationError(['code' => 'Subject code already exists']);
        }

        // Verify instructor exists
        $instructor = $this->instructorModel->findById($data['instructor_id']);
        if (!$instructor) {
            Response::validationError(['instructor_id' => 'Instructor not found']);
        }

        try {
            $subjectId = $this->subjectModel->create($data);
            $subject = $this->subjectModel->findById($subjectId);

            Response::success('Subject created successfully', $subject);
        } catch (Exception $e) {
            Response::error('Failed to create subject: ' . $e->getMessage(), null, 500);
        }
    }

    public function updateSubject()
    {
        Auth::requireAdmin();

        $subjectId = $_GET['id'] ?? null;
        if (!$subjectId) {
            Response::error('Subject ID is required', null, 400);
        }

        $data = json_decode(file_get_contents('php://input'), true) ?? [];

        $errors = Validator::validate($data, [
            'code' => 'required|max:20',
            'name' => 'required|max:255',
            'instructor_id' => 'required|numeric',
            'program' => 'required|max:50',
            'year_level' => 'required|numeric|min:1|max:4',
            'section' => 'required|max:10',
            'room' => 'max:50'
        ]);

        if (!empty($errors)) {
            Response::validationError($errors);
        }

        // Check if subject exists
        $existingSubject = $this->subjectModel->findById($subjectId);
        if (!$existingSubject) {
            Response::error('Subject not found', null, 404);
        }

        // Check if subject code already exists (excluding current subject)
        $codeExists = $this->subjectModel->findByCode($data['code']);
        if ($codeExists && $codeExists['id'] != $subjectId) {
            Response::validationError(['code' => 'Subject code already exists']);
        }

        // Verify instructor exists
        $instructor = $this->instructorModel->findById($data['instructor_id']);
        if (!$instructor) {
            Response::validationError(['instructor_id' => 'Instructor not found']);
        }

        try {
            $this->subjectModel->update($subjectId, $data);
            $subject = $this->subjectModel->findById($subjectId);

            Response::success('Subject updated successfully', $subject);
        } catch (Exception $e) {
            Response::error('Failed to update subject: ' . $e->getMessage(), null, 500);
        }
    }

    public function deleteSubject()
    {
        Auth::requireAdmin();

        $subjectId = $_GET['id'] ?? null;
        if (!$subjectId) {
            Response::error('Subject ID is required', null, 400);
        }

        // Check if subject exists
        $subject = $this->subjectModel->findById($subjectId);
        if (!$subject) {
            Response::error('Subject not found', null, 404);
        }

        try {
            $this->subjectModel->delete($subjectId);
            Response::success('Subject deleted successfully');
        } catch (Exception $e) {
            Response::error('Failed to delete subject: ' . $e->getMessage(), null, 500);
        }
    }

    private function generateTempPassword($length = 12)
    {
        $bytes = random_bytes($length);
        return substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $length);
    }
}
