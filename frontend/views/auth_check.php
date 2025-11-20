<?php
/**
 * Authentication Check Helper
 * CICS Attendance System
 */

// Start session with the same configuration as the backend
if (session_status() === PHP_SESSION_NONE) {
    $configPath = __DIR__ . '/../../backend/config/app.php';
    
    if (file_exists($configPath)) {
        $config = require $configPath;
        session_name($config['session']['name']);
        session_set_cookie_params([
            'lifetime' => $config['session']['lifetime'],
            'path' => '/',
            'domain' => '',
            'secure' => $config['session']['secure'],
            'httponly' => $config['session']['httponly']
        ]);
    }
    session_start();
}

// Function to require authentication
function require_auth() {
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        // Redirect to login page
        // Assuming this script is included from a file in frontend/views/admin/ or similar
        // We need to construct the path to login.php
        
        // Simple relative path assuming standard structure (views/admin/dashboard.php -> views/login.php)
        header('Location: ../login.php');
        exit;
    }
}

// Function to require specific role
function require_role($allowed_roles) {
    require_auth();
    
    if (!is_array($allowed_roles)) {
        $allowed_roles = [$allowed_roles];
    }
    
    if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $allowed_roles)) {
        // Access denied
        header('HTTP/1.1 403 Forbidden');
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Access Denied</title>
            <style>
                body {
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                    background-color: #f3f4f6;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                    margin: 0;
                }
                .error-card {
                    background: white;
                    padding: 2rem;
                    border-radius: 0.5rem;
                    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
                    text-align: center;
                    max-width: 400px;
                }
                h1 { color: #dc2626; margin-bottom: 1rem; }
                p { color: #4b5563; margin-bottom: 1.5rem; }
                .btn {
                    background-color: #2563eb;
                    color: white;
                    padding: 0.5rem 1rem;
                    border-radius: 0.25rem;
                    text-decoration: none;
                    font-weight: 500;
                }
                .btn:hover { background-color: #1d4ed8; }
            </style>
        </head>
        <body>
            <div class="error-card">
                <h1>Access Denied</h1>
                <p>You do not have permission to access this page.</p>
                <a href="../login.php" class="btn">Return to Login</a>
            </div>
        </body>
        </html>
        <?php
        exit;
    }
}
?>
