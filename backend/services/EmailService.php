<?php

/**
 * Email Service
 * Handles email notification logging and tracking
 * CICS Attendance System
 */

require_once __DIR__ . '/../database/Database.php';

class EmailService
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Log an email notification
     * 
     * @param array $data Email notification data
     * @return int The ID of the created notification log
     */
    public function logNotification($data)
    {
        $sql = "INSERT INTO email_notifications 
                (parent_id, student_id, type, subject, content, status, sent_at) 
                VALUES (:parent_id, :student_id, :type, :subject, :content, :status, :sent_at)";

        $params = [
            ':parent_id' => $data['parent_id'],
            ':student_id' => $data['student_id'],
            ':type' => $data['type'] ?? 'daily_summary',
            ':subject' => $data['subject'],
            ':content' => $data['content'],
            ':status' => $data['status'] ?? 'pending',
            ':sent_at' => $data['sent_at'] ?? null
        ];

        $this->db->query($sql, $params);
        return $this->db->lastInsertId();
    }

    /**
     * Update notification status
     * 
     * @param int $notificationId
     * @param string $status 'pending', 'sent', or 'failed'
     * @param string|null $sentAt
     */
    public function updateNotificationStatus($notificationId, $status, $sentAt = null)
    {
        $sql = "UPDATE email_notifications 
                SET status = :status, sent_at = :sent_at 
                WHERE id = :id";

        $params = [
            ':id' => $notificationId,
            ':status' => $status,
            ':sent_at' => $sentAt ?? ($status === 'sent' ? date('Y-m-d H:i:s') : null)
        ];

        $this->db->query($sql, $params);
    }

    /**
     * Get parent information for students in a session
     * 
     * @param int $sessionId
     * @return array Array of parent information with student details
     */
    public function getParentsForSession($session)
    {
        // Accept either a session array or an ID
        if (is_numeric($session)) {
            $sessionDetails = $this->getSessionDetails((int)$session);
        } elseif (is_array($session) && isset($session['id'])) {
            $sessionDetails = $session;
        } else {
            $sessionDetails = null;
        }

        if (!$sessionDetails) {
            return [];
        }

        $program = $sessionDetails['program'] ?? null;
        $yearLevel = $sessionDetails['year_level'] ?? null;
        $section = strtoupper(trim($sessionDetails['section'] ?? ''));

        // If subject metadata is missing, fall back to previously attended-only list
        if (!$program || !$yearLevel) {
            $sql = "SELECT DISTINCT
                        p.id as parent_id,
                        p.first_name as parent_first_name,
                        p.last_name as parent_last_name,
                        p.email as parent_email,
                        p.contact_number as parent_contact,
                        p.relationship,
                        s.id as student_id,
                        s.student_id as student_number,
                        s.first_name as student_first_name,
                        s.last_name as student_last_name,
                        s.program,
                        s.year_level,
                        s.section
                    FROM parents p
                    INNER JOIN students s ON p.student_id = s.id
                    INNER JOIN attendance_records ar ON ar.student_id = s.id
                    WHERE ar.session_id = :fallback_session_id
                    AND p.email IS NOT NULL
                    AND p.email != ''";

            return $this->db->fetchAll($sql, [':fallback_session_id' => $sessionDetails['id']]);
        }

        $sql = "SELECT DISTINCT
                    p.id as parent_id,
                    p.first_name as parent_first_name,
                    p.last_name as parent_last_name,
                    p.email as parent_email,
                    p.contact_number as parent_contact,
                    p.relationship,
                    s.id as student_id,
                    s.student_id as student_number,
                    s.first_name as student_first_name,
                    s.last_name as student_last_name,
                    s.program,
                    s.year_level,
                    s.section,
                    ar.status as attendance_status,
                    ar.time_in,
                    ar.time_out
                FROM parents p
                INNER JOIN students s ON p.student_id = s.id
                LEFT JOIN attendance_records ar 
                    ON ar.student_id = s.id
                    AND ar.session_id = :session_id
                WHERE p.email IS NOT NULL
                  AND p.email != ''
                  AND s.program = :program
                  AND s.year_level = :year_level";

        $params = [
            ':session_id' => $sessionDetails['id'],
            ':program' => $program,
            ':year_level' => $yearLevel
        ];

        if (!empty($section)) {
            $sql .= " AND (
                UPPER(TRIM(s.section)) = :section_exact
                OR UPPER(TRIM(s.section)) LIKE CONCAT('%', :section_suffix)
                OR :section_prefix LIKE CONCAT('%', UPPER(TRIM(s.section)))
            )";

            $params[':section_exact'] = $section;
            $params[':section_suffix'] = $section;
            $params[':section_prefix'] = $section;
        }

        $sql .= " ORDER BY s.last_name ASC, s.first_name ASC";

        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Get attendance details for a student in a session
     * 
     * @param int $sessionId
     * @param int $studentId
     * @return array|null Attendance record or null if not found
     */
    public function getAttendanceDetails($sessionId, $studentId)
    {
        $sql = "SELECT 
                    ar.id,
                    ar.time_in,
                    ar.time_out,
                    ar.status,
                    sess.session_date,
                    sess.start_time,
                    sess.end_time,
                    sub.code as subject_code,
                    sub.name as subject_name,
                    sub.room,
                    CONCAT(i.first_name, ' ', i.last_name) as instructor_name
                FROM attendance_records ar
                INNER JOIN attendance_sessions sess ON ar.session_id = sess.id
                INNER JOIN subjects sub ON sess.subject_id = sub.id
                INNER JOIN instructors i ON sess.instructor_id = i.id
                WHERE ar.session_id = :session_id 
                AND ar.student_id = :student_id
                LIMIT 1";

        return $this->db->fetchOne($sql, [
            ':session_id' => $sessionId,
            ':student_id' => $studentId
        ]);
    }

    /**
     * Get session details
     * 
     * @param int $sessionId
     * @return array|null Session details
     */
    public function getSessionDetails($sessionId)
    {
        $sql = "SELECT 
                    sess.id,
                    sess.session_date,
                    sess.start_time,
                    sess.end_time,
                    sess.status,
                    sub.id as subject_id,
                    sub.code as subject_code,
                    sub.name as subject_name,
                    sub.room,
                    sub.program,
                    sub.year_level,
                    sub.section,
                    CONCAT(i.first_name, ' ', i.last_name) as instructor_name
                FROM attendance_sessions sess
                INNER JOIN subjects sub ON sess.subject_id = sub.id
                INNER JOIN instructors i ON sess.instructor_id = i.id
                WHERE sess.id = :session_id
                LIMIT 1";

        return $this->db->fetchOne($sql, [':session_id' => $sessionId]);
    }

    /**
     * Prepare email data for a student's attendance
     * 
     * @param array $parent Parent information
     * @param array $session Session details
     * @param array|null $attendance Attendance record (null if absent)
     * @return array Email template parameters
     */
    public function prepareEmailData($parent, $session, $attendance = null)
    {
        $studentName = trim($parent['student_first_name'] . ' ' . $parent['student_last_name']);
        $parentName = trim($parent['parent_first_name'] . ' ' . $parent['parent_last_name']);

        // Format date and time
        $sessionDate = date('F j, Y', strtotime($session['session_date']));
        $sessionTime = date('g:i A', strtotime($session['start_time']));
        if ($session['end_time']) {
            $sessionTime .= ' - ' . date('g:i A', strtotime($session['end_time']));
        }

        // Base email data
        $emailData = [
            'parent_name' => $parentName,
            'parent_email' => $parent['parent_email'],
            'student_name' => $studentName,
            'student_id' => $parent['student_number'],
            'subject_name' => $session['subject_name'],
            'subject_code' => $session['subject_code'],
            'instructor_name' => $session['instructor_name'],
            'session_date' => $sessionDate,
            'session_time' => $sessionTime,
            'room' => $session['room'] ?? 'N/A'
        ];

        // Add attendance-specific data
        if ($attendance) {
            $emailData['attendance_status'] = ucfirst($attendance['status']);
            $emailData['time_in'] = date('g:i A', strtotime($attendance['time_in']));

            if ($attendance['time_out']) {
                $emailData['time_out'] = date('g:i A', strtotime($attendance['time_out']));
            }

            // Conditional flags for template
            $emailData['is_present'] = $attendance['status'] === 'present';
            $emailData['is_late'] = $attendance['status'] === 'late';
            $emailData['is_absent'] = false;
        } else {
            // Student was absent
            $emailData['attendance_status'] = 'Absent';
            $emailData['is_present'] = false;
            $emailData['is_late'] = false;
            $emailData['is_absent'] = true;
        }

        return $emailData;
    }

    /**
     * Get notification statistics
     * 
     * @param array $filters Optional filters (student_id, parent_id, status, date_from, date_to)
     * @return array Statistics
     */
    public function getNotificationStats($filters = [])
    {
        $where = ['1=1'];
        $params = [];

        if (!empty($filters['student_id'])) {
            $where[] = 'student_id = :student_id';
            $params[':student_id'] = $filters['student_id'];
        }

        if (!empty($filters['parent_id'])) {
            $where[] = 'parent_id = :parent_id';
            $params[':parent_id'] = $filters['parent_id'];
        }

        if (!empty($filters['status'])) {
            $where[] = 'status = :status';
            $params[':status'] = $filters['status'];
        }

        if (!empty($filters['date_from'])) {
            $where[] = 'DATE(created_at) >= :date_from';
            $params[':date_from'] = $filters['date_from'];
        }

        if (!empty($filters['date_to'])) {
            $where[] = 'DATE(created_at) <= :date_to';
            $params[':date_to'] = $filters['date_to'];
        }

        $whereClause = implode(' AND ', $where);

        $sql = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'sent' THEN 1 ELSE 0 END) as sent,
                    SUM(CASE WHEN status = 'failed' THEN 1 ELSE 0 END) as failed,
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending
                FROM email_notifications
                WHERE {$whereClause}";

        return $this->db->fetchOne($sql, $params);
    }

    /**
     * Get recent notifications
     * 
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getRecentNotifications($limit = 50, $offset = 0)
    {
        $sql = "SELECT 
                    en.*,
                    CONCAT(p.first_name, ' ', p.last_name) as parent_name,
                    p.email as parent_email,
                    CONCAT(s.first_name, ' ', s.last_name) as student_name,
                    s.student_id as student_number
                FROM email_notifications en
                INNER JOIN parents p ON en.parent_id = p.id
                INNER JOIN students s ON en.student_id = s.id
                ORDER BY en.created_at DESC
                LIMIT :limit OFFSET :offset";

        return $this->db->fetchAll($sql, [
            ':limit' => $limit,
            ':offset' => $offset
        ]);
    }
}
