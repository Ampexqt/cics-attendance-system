# ZPPSU CICS Student Access & Attendance System

A web-based attendance and student access system for the ZPPSU College of Information and Computing Sciences (CICS).
Students, instructors, admins and parents interact through role-based dashboards, with GPS-based attendance and automated parental notifications.

> This README is written for beginner programmers. It explains **what the project does**, **how it is structured**, and **how to run it locally** step by step.
> For a more low-level backend API reference, see the section **"CICS Attendance System - Backend API"** near the bottom of this file.

## 1. Features

- **Accurate attendance tracking**
  - Instructors start/end attendance sessions for each subject.
  - Students can time-in / time-out only when they are inside campus (GPS radius).
- **Automated parental notifications**
  - Parent/guardian info is stored per student.
  - Daily / alert emails can be sent summarizing attendance.
- **Role-based dashboards**
  - **Student** – view schedule, attendance logs, request corrections.
  - **Instructor** – manage subjects and sessions, approve corrections.
  - **Admin** – manage users, approve registrations, configure settings.
- **One-device-per-student security** using a browser/device fingerprint.
- **Exportable reports & basic analytics** for admins and instructors.

## 2. Tech Stack

- **Backend:** Plain PHP (object-oriented), custom lightweight API router.
- **Database:** MySQL / MariaDB (schema in `backend/database/schema.sql`).
- **Frontend:** PHP views + HTML/CSS + vanilla JavaScript.
- **Notifications:** Optional [EmailJS](https://www.emailjs.com/) integration for sending instructor credentials.
- **Environment:** Designed to run on local servers like **WAMP/XAMPP** (Apache + PHP + MySQL).

## 3. Folder Structure (High Level)

```text
cics-attendance-system/
├── index.php              # Public landing page
├── login.php              # Login page (students, instructors, admin)
├── register.php           # Student registration page
├── backend/               # PHP API + business logic
│   ├── api/               # Single entrypoint index.php (API router)
│   ├── config/            # app.php (app settings), database.php (DB config)
│   ├── controllers/       # AuthController, AdminController, AttendanceController
│   ├── database/          # Database.php (PDO wrapper), schema.sql (DB schema)
│   ├── middleware/        # Auth.php (sessions/roles), CORS.php
│   ├── models/            # User, Student, Parent, Instructor, Subject, Attendance, Settings
│   └── utils/             # Response, Validator, Helper, GpsHelper, etc.
└── frontend/
    ├── assets/            # CSS + JS (Toast, Modal, AuthAPI, DeviceFingerprint, etc.)
    └── views/             # PHP views for student/instructor/admin dashboards
```

## 4. Getting Started (Local Development)

### 4.1. Requirements

- PHP **7.4+**
- MySQL / MariaDB
- A local web server (e.g. **WAMP** on Windows, **XAMPP**, or any Apache + PHP setup)
- A web browser (Chrome, Firefox, Edge, etc.)

### 4.2. Installation Steps

- **1. Place the project in your web server root**
  - Example on Windows with WAMP: `C:\\wamp64\\www\\cics-attendance-system\\`
  - Your path can be different; just keep the folder name in the URL (e.g. `/cics-attendance-system`).
- **2. Start Apache and MySQL** from WAMP/XAMPP.
- **3. Create the database and tables**
  - Option A – using the MySQL CLI:
    ```bash
    mysql -u root -p < backend/database/schema.sql
    ```
  - Option B – using phpMyAdmin:
    - Create a database named `cics_attendance`.
    - Import the file `backend/database/schema.sql`.
- **4. Configure database connection** in `backend/config/database.php`:
  ```php
  return [
      'host' => 'localhost',
      'dbname' => 'cics_attendance',
      'username' => 'root',          // change if your MySQL user is different
      'password' => 'your_password', // set your MySQL password
      'charset' => 'utf8mb4',
      'options' => [
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
          PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
          PDO::ATTR_EMULATE_PREPARES => false,
      ]
  ];
  ```
- **5. Configure app settings** in `backend/config/app.php`:
  - `base_url` – your local URL, e.g. `http://localhost/cics-attendance-system`.
  - `timezone` – default is `Asia/Manila`.
  - `session` – cookie name, lifetime, and security flags.
  - `email` – SMTP settings used when sending emails directly from the backend.
  - `campus` – GPS latitude, longitude, and radius (meters).
  - `attendance` – late/absent thresholds and whether admins can override.
- **6. (Optional) Configure EmailJS** for sending instructor credentials
  - Set your EmailJS keys in `frontend/assets/js/emailjs-config.js`.
  - Create a service + template in EmailJS with variables: `{{instructor_name}}`, `{{instructor_email}}`, `{{temp_password}}`.

### 4.3. Running the App

- With WAMP/XAMPP running, open your browser and go to:
  - `http://localhost/cics-attendance-system/`  (landing page)
- From there you can open:
  - `login.php` – login for all roles.
  - `register.php` – student self-registration page.

### 4.4. Default Admin Account

After running the SQL schema, a default admin user is created:
- Email: `admin@zppsu.edu`
- Password: `admin123`  **(change this in production!)**

Log in as admin, then create instructor accounts, manage students, subjects, and settings.

## 5. How the System Works (High-Level)

- **Authentication & sessions**
  - Frontend uses `AuthAPI` (`frontend/assets/js/auth.js`) to call the backend API.
  - Backend entry point: `backend/api/index.php` (router).
  - `AuthController` handles login, registration, logout, and `me` (current user).
  - PHP sessions (`backend/middleware/Auth.php`) store the logged-in user and role.
- **Roles**
  - `admin` – full control, can approve/reject student registrations, manage users, subjects, and campus settings.
  - `instructor` – manages subjects and attendance sessions for their classes.
  - `student` – can register, log in from a single device, and mark attendance for active sessions.
- **Device fingerprint (1 device per student)**
  - Frontend generates a fingerprint using `frontend/assets/js/fingerprint.js`.
  - On login, the backend checks whether the fingerprint matches the one stored for that student.
- **Attendance flow**
  - Instructors start an attendance session for a subject.
  - Students time-in/time-out; GPS coordinates and device fingerprint can be stored.
  - Records are stored in `attendance_sessions` and `attendance_records` tables.
- **Correction requests and approvals**
  - Students can request changes if they believe an attendance record is wrong.
  - Admin/instructor reviews the request and approves/rejects it.
- **Parental notifications**
  - Parent/guardian info is stored in the `parents` table.
  - Email notifications are logged in `email_notifications` for tracking.

## 6. Backend API (For Developers)

Base URL (from the browser):
```text
/cics-attendance-system/backend/api
```

### 6.1. Authentication Endpoints

- `POST /auth/login` – User login (email or student ID + password + optional device fingerprint).
- `POST /auth/register` – Student registration.
- `POST /auth/logout` – Logout current user.
- `GET  /auth/me` – Get the currently logged-in user.

### 6.2. Attendance Endpoints

- `POST /attendance/mark` – Student marks attendance (time-in).
- `POST /attendance/timeout` – Student marks time-out.
- `GET  /attendance/records` – Get attendance records.
- `GET  /attendance/summary` – Get attendance summary (for dashboards/reports).

### 6.3. Admin Endpoints

- `POST   /admin/approve` – Approve/reject student registration.
- `GET    /admin/pending` – Get pending registrations.
- `GET    /admin/students` – Get all students.
- `PUT    /admin/update-student` – Update student.
- `DELETE /admin/delete-student` – Delete student.
- `GET    /admin/dashboard-stats` – Get dashboard statistics.
- Additional routes exist for instructors, subjects, users, and campus settings (see `backend/api/index.php`).

### 6.4. Request / Response Format

**Example request body (login):**
```json
{
  "email": "student@example.com",
  "password": "password123"
}
```

**Typical success response:**
```json
{
  "success": true,
  "message": "Login successful",
  "data": { ... }
}
```

**Typical error response:**
```json
{
  "success": false,
  "message": "Error message",
  "errors": { ... }
}
```

### 6.5. CORS

- CORS headers are handled by `backend/middleware/CORS.php`.
- For production, you should **restrict allowed origins** instead of allowing all.

## 7. Tips for Beginner Contributors

- **Start from the login flow**
  - Read `login.php`, then `frontend/assets/js/auth.js`, then `backend/api/index.php` and `backend/controllers/AuthController.php`.
  - This shows the full path: **form → JS → API route → controller → model → database**.
- **Add new API endpoints carefully**
  - Add a new method in the appropriate controller (e.g. `AdminController`).
  - Register a route for it in `backend/api/index.php`.
  - Implement database logic in a model (e.g. `Attendance`, `Student`, etc.).
- **Keep configuration out of code**
  - Put configurable values into `backend/config/app.php` or the `settings` table instead of hard-coding them.
- **Use the existing patterns**
  - Use `Response::success()` / `Response::error()` helpers for consistent JSON responses.
  - Use `Validator` for validating request data on the backend.

## 8. Security & Production Notes

- Change the **default admin password** immediately.
- Set a strong **JWT secret** and update other secrets in `backend/config/app.php`.
- Turn on **secure cookies** and HTTPS in a real deployment.
- Limit CORS origins in `backend/middleware/CORS.php`.
- Regularly back up your `cics_attendance` database.

Happy coding! If you are a beginner, read this file from top to bottom, then trace one feature (like login or attendance mark) through the code to understand how everything connects.

# CICS Attendance System - Backend API

## Setup Instructions

### 1. Database Setup

1. Create the database by running the SQL schema:
   ```bash
   mysql -u root -p < backend/database/schema.sql
   ```

2. Update database credentials in `backend/config/database.php`:
   ```php
   'host' => 'localhost',
   'dbname' => 'cics_attendance',
   'username' => 'root',
   'password' => 'your_password',
   ```

### 2. Configuration

Update `backend/config/app.php` with your settings:
- Base URL
- Email configuration (SMTP)
- Campus GPS coordinates
- Attendance thresholds

### 2.1. EmailJS Setup (Optional - for Instructor Credentials)

To enable automatic email sending when adding instructors:

1. Follow the detailed guide in `EMAILJS_SETUP_GUIDE.md`
2. Configure EmailJS credentials in `frontend/assets/js/emailjs-config.js`

**Quick Setup:**
- Create account at [https://www.emailjs.com/](https://www.emailjs.com/)
- Create an Email Service (Gmail, Outlook, etc.)
- Create an Email Template with variables: `{{instructor_name}}`, `{{instructor_email}}`, `{{temp_password}}`
- Get your Public Key from Account settings
- Update `frontend/assets/js/emailjs-config.js` with your credentials

**Note:** If EmailJS is not configured, the system will still generate passwords but won't send emails automatically. You can manually share credentials with instructors.

### 3. API Endpoints

#### Authentication
- `POST /backend/api/auth/login` - User login
- `POST /backend/api/auth/register` - Student registration
- `POST /backend/api/auth/logout` - User logout
- `GET /backend/api/auth/me` - Get current user data

#### Attendance
- `POST /backend/api/attendance/mark` - Mark attendance (student)
- `POST /backend/api/attendance/timeout` - Mark time out (student)
- `GET /backend/api/attendance/records` - Get attendance records
- `GET /backend/api/attendance/summary` - Get attendance summary

#### Admin
- `POST /backend/api/admin/approve` - Approve/reject student registration
- `GET /backend/api/admin/pending` - Get pending registrations
- `GET /backend/api/admin/students` - Get all students
- `PUT /backend/api/admin/update-student` - Update student
- `DELETE /backend/api/admin/delete-student` - Delete student
- `GET /backend/api/admin/dashboard-stats` - Get dashboard statistics

### 4. Default Admin Account

After running the schema, default admin credentials:
- Email: `admin@zppsu.edu`
- Password: `admin123` (CHANGE IN PRODUCTION!)

### 5. Request/Response Format

**Request:**
```json
{
  "email": "student@example.com",
  "password": "password123"
}
```

**Success Response:**
```json
{
  "success": true,
  "message": "Login successful",
  "data": { ... }
}
```

**Error Response:**
```json
{
  "success": false,
  "message": "Error message",
  "errors": { ... }
}
```

### 6. Authentication

Most endpoints require authentication. Include session cookie or JWT token in requests.

### 7. CORS

CORS is enabled for all origins. Adjust in `backend/middleware/CORS.php` for production.

## File Structure

```
backend/
├── api/              # API endpoints
├── config/           # Configuration files
├── controllers/      # Request handlers
├── database/         # Database connection & schema
├── middleware/       # Auth, CORS, etc.
├── models/           # Data models
└── utils/            # Helper functions
```

