<?php
/**
 * Authentication Controller
 * CICS Attendance System
 */

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../models/Parent.php';
require_once __DIR__ . '/../middleware/Auth.php';
require_once __DIR__ . '/../utils/Response.php';
require_once __DIR__ . '/../utils/Validator.php';
require_once __DIR__ . '/../utils/Helper.php';

class AuthController {
    private $userModel;
    private $studentModel;
    private $parentModel;
    
    public function __construct() {
        $this->userModel = new User();
        $this->studentModel = new Student();
        $this->parentModel = new ParentModel();
    }
    
    public function login() {
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Validate input - removed role requirement, backend determines role from database
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
            Response::error('Invalid credentials', null, 401);
        }
        
        // Check status
        if ($user['status'] !== 'active') {
            Response::error('Account is not active. Please wait for admin approval.', null, 403);
        }
        
        // Verify password
        if (!Helper::verifyPassword($data['password'], $user['password'])) {
            Response::error('Invalid credentials', null, 401);
        }
        
        // Generate device fingerprint
        $deviceFingerprint = Helper::generateDeviceFingerprint();
        
        // For students, check device restriction
        if ($user['role'] === 'student') {
            if (!empty($user['device_fingerprint']) && $user['device_fingerprint'] !== $deviceFingerprint) {
                Response::error('Device not registered. Please use your registered device.', null, 403);
            }
            
            // Update device fingerprint on first login
            if (empty($user['device_fingerprint'])) {
                $this->userModel->updateDeviceFingerprint($user['id'], $deviceFingerprint);
            }
        }
        
        // Get user details based on role
        $userData = ['id' => $user['id'], 'email' => $user['email'], 'role' => $user['role']];
        
        if ($user['role'] === 'student') {
            $student = $this->studentModel->findByUserId($user['id']);
            if ($student) {
                $userData = array_merge($userData, $student);
            }
        }
        
        // Update last login
        $this->userModel->updateLastLogin($user['id']);
        
        // Start session
        Auth::login($user['id'], $user['role'], $userData);
        
        Response::success('Login successful', $userData);
    }
    
    public function register() {
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Validate input
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
            'relationship' => 'required|in:father,mother,guardian,other'
        ]);
        
        if (!empty($errors)) {
            Response::validationError($errors);
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
        
        // Create user
        $userId = $this->userModel->create([
            'email' => $data['email'],
            'password' => Helper::hashPassword($data['password']),
            'role' => 'student',
            'status' => 'pending' // Requires admin approval
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
        
        Response::success('Registration submitted successfully. Please wait for admin approval.', [
            'user_id' => $userId,
            'status' => 'pending'
        ], 201);
    }
    
    public function logout() {
        Auth::logout();
        Response::success('Logged out successfully');
    }
    
    public function me() {
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

