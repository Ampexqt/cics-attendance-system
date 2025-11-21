<?php

/**
 * Admin Controller
 * CICS Attendance System
 */

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Student.php';
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

    public function __construct()
    {
        $this->userModel = new User();
        $this->studentModel = new Student();
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
}
