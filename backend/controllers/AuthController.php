<?php

/**
 * Authentication Controller
 * CICS Attendance System
 */

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../models/Parent.php';
require_once __DIR__ . '/../models/Instructor.php';
require_once __DIR__ . '/../middleware/Auth.php';
require_once __DIR__ . '/../utils/Response.php';
require_once __DIR__ . '/../utils/Validator.php';
require_once __DIR__ . '/../utils/Helper.php';

class AuthController
{
    private $userModel;
    private $studentModel;
    private $parentModel;
    private $instructorModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->studentModel = new Student();
        $this->parentModel = new ParentModel();
        $this->instructorModel = new Instructor();
    }

    public function login()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        // Validate input - device fingerprint is required for students
        $errors = Validator::validate($data, [
            'email' => 'required',  // Can be email or student ID
            'password' => 'required'  // Removed min length check for login
        ]);

        if (!empty($errors)) {
            Response::validationError($errors);
        }

        // Find user by email or student ID
        $user = $this->userModel->findByEmailOrStudentId($data['email']);

        if (!$user) {
            Response::error('Email or Student ID not found', null, 401);
        }

        // Verify password first (before checking status)
        if (!Helper::verifyPassword($data['password'], $user['password'])) {
            Response::error('Incorrect password', null, 401);
        }

        // Check status and provide specific messages
        if ($user['status'] !== 'active') {
            if ($user['status'] === 'pending') {
                Response::error('Your account is pending approval. Please wait for admin approval.', null, 403);
            } elseif ($user['status'] === 'rejected') {
                Response::error('Your registration was rejected by the administrator. Please contact support for more information.', null, 403);
            } elseif ($user['status'] === 'inactive') {
                Response::error('Your account has been deactivated. Please contact support.', null, 403);
            } else {
                Response::error('Account is not active. Please contact support.', null, 403);
            }
        }

        // For students, enforce device restriction (1 device per account)
        if ($user['role'] === 'student') {
            // Device fingerprint is required from client
            if (empty($data['deviceFingerprint'])) {
                Response::error('Device fingerprint is required', null, 400);
            }

            // Validate fingerprint format
            if (!Helper::validateDeviceFingerprint($data['deviceFingerprint'])) {
                Response::error('Invalid device fingerprint format', null, 400);
            }

            // Check if device matches registered device
            if (!empty($user['device_fingerprint'])) {
                if ($user['device_fingerprint'] !== $data['deviceFingerprint']) {
                    Response::error('Device not registered. Please use your registered device to login.', null, 403);
                }
            } else {
                // This should not happen if registration is working correctly
                // But handle it gracefully by rejecting login
                Response::error('Account setup incomplete. Please contact administrator.', null, 403);
            }
        }

        // Get user details based on role
        $userData = [
            'id' => $user['id'], 
            'email' => $user['email'], 
            'role' => $user['role'],
            'device_fingerprint' => $user['device_fingerprint'] ?? null
        ];

        if ($user['role'] === 'student') {
            $student = $this->studentModel->findByUserId($user['id']);
            if ($student) {
                $userData = array_merge($userData, $student);
            }
        } elseif ($user['role'] === 'instructor') {
            $instructor = $this->instructorModel->findByUserId($user['id']);
            if ($instructor) {
                $userData = array_merge($userData, $instructor);
            }
        }

        // Update last login
        $this->userModel->updateLastLogin($user['id']);

        // Start session
        Auth::login($user['id'], $user['role'], $userData);

        Response::success('Login successful', $userData);
    }

    public function register()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        // Validate input - now includes device fingerprint
        $errors = Validator::validate($data, [
            'email' => 'required|email',
            'password' => 'required|min:8',
            'confirmPassword' => 'required|match:password',
            'firstName' => 'required|min:2',
            'lastName' => 'required|min:2',
            'studentId' => 'required',
            'program' => 'required|in:BSCS,BSIT,BSIS',
            'section' => 'required',
            'parentFirstName' => 'required',
            'parentLastName' => 'required',
            'parentEmail' => 'required|email',
            'parentContact' => 'required',
            'relationship' => 'required|in:father,mother,guardian,other',
            'deviceFingerprint' => 'required'
        ]);

        if (!empty($errors)) {
            Response::validationError($errors);
        }

        // Validate device fingerprint format
        if (!Helper::validateDeviceFingerprint($data['deviceFingerprint'])) {
            Response::error('Invalid device fingerprint. Please try again or use a different browser.', null, 400);
        }

        // Check if email already exists
        $existingUser = $this->userModel->findByEmail($data['email']);
        if ($existingUser) {
            Response::error('Email already registered', null, 409);
        }

        // Check if student ID already exists
        $existingStudent = $this->studentModel->findByStudentId($data['studentId']);
        if ($existingStudent) {
            Response::error('Student ID already registered', null, 409);
        }

        // Extract year level from section (e.g., "3A" -> 3)
        $yearLevel = (int)substr($data['section'], 0, 1);

        // Create user with device fingerprint
        $userId = $this->userModel->create([
            'email' => $data['email'],
            'password' => Helper::hashPassword($data['password']),
            'role' => 'student',
            'status' => 'pending', // Requires admin approval
            'device_fingerprint' => $data['deviceFingerprint'] // Save device fingerprint
        ]);

        // Create student record
        $studentId = $this->studentModel->create([
            'user_id' => $userId,
            'student_id' => $data['studentId'],
            'first_name' => $data['firstName'],
            'last_name' => $data['lastName'],
            'program' => $data['program'],
            'year_level' => $yearLevel,
            'section' => $data['section']
        ]);

        // Create parent record
        $this->parentModel->create([
            'student_id' => $studentId,
            'first_name' => $data['parentFirstName'],
            'last_name' => $data['parentLastName'],
            'email' => $data['parentEmail'],
            'contact_number' => $data['parentContact'],
            'relationship' => $data['relationship']
        ]);

        Response::success('Registration submitted successfully. Your device has been registered. Please wait for admin approval.', [
            'user_id' => $userId,
            'status' => 'pending'
        ], 201);
    }

    public function logout()
    {
        Auth::logout();
        Response::success('Logged out successfully');
    }

    public function me()
    {
        Auth::requireAuth();

        $user = Auth::user();
        $userId = Auth::userId();
        $role = Auth::role();

        // Get additional data based on role
        if ($role === 'student') {
            $student = $this->studentModel->findByUserId($userId);
            $user = array_merge($user, $student ?? []);
        }

        Response::success('User data retrieved', $user);
    }
}
