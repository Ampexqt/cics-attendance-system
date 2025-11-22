<?php
/**
 * API Router
 * CICS Attendance System
 */

// Enable error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Set timezone
date_default_timezone_set('Asia/Manila');

// Autoload classes
spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . '/../controllers/',
        __DIR__ . '/../models/',
        __DIR__ . '/../middleware/',
        __DIR__ . '/../utils/',
        __DIR__ . '/../database/',
    ];
    
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Handle CORS
require_once __DIR__ . '/../middleware/CORS.php';
CORS::handle();

// Get request method and path
$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = str_replace('/cics-attendance-system/backend/api', '', $path);
$path = trim($path, '/');
$segments = explode('/', $path);

// Route handling
$controller = $segments[0] ?? 'auth';
$action = $segments[1] ?? 'index';

try {
    switch ($controller) {
        case 'auth':
            $authController = new AuthController();
            switch ($action) {
                case 'login':
                    if ($method === 'POST') {
                        $authController->login();
                    } else {
                        Response::error('Method not allowed', null, 405);
                    }
                    break;
                    
                case 'register':
                    if ($method === 'POST') {
                        $authController->register();
                    } else {
                        Response::error('Method not allowed', null, 405);
                    }
                    break;
                    
                case 'logout':
                    if ($method === 'POST') {
                        $authController->logout();
                    } else {
                        Response::error('Method not allowed', null, 405);
                    }
                    break;
                    
                case 'me':
                    if ($method === 'GET') {
                        $authController->me();
                    } else {
                        Response::error('Method not allowed', null, 405);
                    }
                    break;
                    
                default:
                    Response::notFound('Auth endpoint not found');
            }
            break;
            
        case 'attendance':
            $attendanceController = new AttendanceController();
            switch ($action) {
                case 'mark':
                    if ($method === 'POST') {
                        $attendanceController->markAttendance();
                    } else {
                        Response::error('Method not allowed', null, 405);
                    }
                    break;
                    
                case 'timeout':
                    if ($method === 'POST') {
                        $attendanceController->markTimeOut();
                    } else {
                        Response::error('Method not allowed', null, 405);
                    }
                    break;
                    
                case 'records':
                    if ($method === 'GET') {
                        $attendanceController->getRecords();
                    } else {
                        Response::error('Method not allowed', null, 405);
                    }
                    break;
                    
                case 'summary':
                    if ($method === 'GET') {
                        $attendanceController->getSummary();
                    } else {
                        Response::error('Method not allowed', null, 405);
                    }
                    break;
                    
                default:
                    Response::notFound('Attendance endpoint not found');
            }
            break;
            
        case 'admin':
            $adminController = new AdminController();
            switch ($action) {
                case 'approve':
                    if ($method === 'POST') {
                        $adminController->approveRegistration();
                    } else {
                        Response::error('Method not allowed', null, 405);
                    }
                    break;
                    
                case 'pending':
                    if ($method === 'GET') {
                        $adminController->getPendingRegistrations();
                    } else {
                        Response::error('Method not allowed', null, 405);
                    }
                    break;
                    
                case 'students':
                    if ($method === 'GET') {
                        $adminController->getAllStudents();
                    } else {
                        Response::error('Method not allowed', null, 405);
                    }
                    break;

                case 'instructors':
                    // Handle instructors routes: /admin/instructors or /admin/instructors/{id}
                    if (isset($segments[2]) && is_numeric($segments[2])) {
                        // Route with ID: /admin/instructors/{id}
                        $_GET['id'] = $segments[2];
                        if ($method === 'PUT') {
                            $adminController->updateInstructor();
                        } else {
                            Response::error('Method not allowed', null, 405);
                        }
                    } else {
                        // Route without ID: /admin/instructors
                        if ($method === 'GET') {
                            $adminController->getAllInstructors();
                        } elseif ($method === 'POST') {
                            $adminController->createInstructor();
                        } else {
                            Response::error('Method not allowed', null, 405);
                        }
                    }
                    break;

                case 'users':
                    // Handle users routes: /admin/users/{id}
                    if (isset($segments[2]) && is_numeric($segments[2])) {
                        $_GET['id'] = $segments[2];
                        if ($method === 'DELETE') {
                            $adminController->archiveUser();
                        } else {
                            Response::error('Method not allowed', null, 405);
                        }
                    } else {
                        Response::error('User ID is required', null, 400);
                    }
                    break;
                    
                case 'update-student':
                    if ($method === 'PUT') {
                        $adminController->updateStudent();
                    } else {
                        Response::error('Method not allowed', null, 405);
                    }
                    break;
                    
                case 'delete-student':
                    if ($method === 'DELETE') {
                        $adminController->deleteStudent();
                    } else {
                        Response::error('Method not allowed', null, 405);
                    }
                    break;
                    
                case 'dashboard-stats':
                    if ($method === 'GET') {
                        $adminController->getDashboardStats();
                    } else {
                        Response::error('Method not allowed', null, 405);
                    }
                    break;

                case 'subjects':
                    // Handle subjects routes: /admin/subjects or /admin/subjects/{id}
                    if (isset($segments[2]) && is_numeric($segments[2])) {
                        // Route with ID: /admin/subjects/{id}
                        $_GET['id'] = $segments[2];
                        if ($method === 'GET') {
                            $adminController->getSubject();
                        } elseif ($method === 'PUT') {
                            $adminController->updateSubject();
                        } elseif ($method === 'DELETE') {
                            $adminController->deleteSubject();
                        } else {
                            Response::error('Method not allowed', null, 405);
                        }
                    } else {
                        // Route without ID: /admin/subjects
                        if ($method === 'GET') {
                            $adminController->getAllSubjects();
                        } elseif ($method === 'POST') {
                            $adminController->createSubject();
                        } else {
                            Response::error('Method not allowed', null, 405);
                        }
                    }
                    break;
                    
                case 'settings':
                    // Handle settings routes: /admin/settings/campus
                    if (isset($segments[2]) && $segments[2] === 'campus') {
                        if ($method === 'GET') {
                            $adminController->getCampusSettings();
                        } elseif ($method === 'PUT') {
                            $adminController->updateCampusSettings();
                        } else {
                            Response::error('Method not allowed', null, 405);
                        }
                    } else {
                        Response::notFound('Settings endpoint not found');
                    }
                    break;
                    
                default:
                    Response::notFound('Admin endpoint not found');
            }
            break;
            
        default:
            Response::notFound('API endpoint not found');
    }
} catch (Exception $e) {
    error_log("API Error: " . $e->getMessage());
    Response::error('Internal server error', null, 500);
}

