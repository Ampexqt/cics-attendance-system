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

