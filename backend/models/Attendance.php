<?php

/**
 * Attendance Model
 * CICS Attendance System
 */

require_once __DIR__ . '/../database/Database.php';

class Attendance
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function createSession($data)
    {
        $sql = "INSERT INTO attendance_sessions 
                (subject_id, instructor_id, session_date, start_time, gps_latitude, gps_longitude) 
                VALUES (:subject_id, :instructor_id, :session_date, :start_time, :gps_latitude, :gps_longitude)";

        $params = [
            ':subject_id' => $data['subject_id'],
            ':instructor_id' => $data['instructor_id'],
            ':session_date' => $data['session_date'],
            ':start_time' => $data['start_time'],
            ':gps_latitude' => $data['gps_latitude'] ?? null,
            ':gps_longitude' => $data['gps_longitude'] ?? null
        ];

        $this->db->query($sql, $params);
        return $this->db->lastInsertId();
    }

    public function endSession($sessionId)
    {
        $sql = "UPDATE attendance_sessions 
                SET end_time = TIME(NOW()), status = 'ended' 
                WHERE id = :id";
        $this->db->query($sql, [':id' => $sessionId]);
        return true;
    }

    public function getActiveSession($subjectId, $date = null)
    {
        if (!$date) {
            $date = date('Y-m-d');
        }

        $sql = "SELECT * FROM attendance_sessions 
                WHERE subject_id = :subject_id 
                AND session_date = :session_date 
                AND status = 'active' 
                ORDER BY start_time DESC 
                LIMIT 1";

        return $this->db->fetchOne($sql, [
            ':subject_id' => $subjectId,
            ':session_date' => $date
        ]);
    }

    public function getSessionById($sessionId)
    {
        $sql = "SELECT * FROM attendance_sessions WHERE id = :id LIMIT 1";
        return $this->db->fetchOne($sql, [':id' => $sessionId]);
    }

    public function markAttendance($data)
    {
        $sql = "INSERT INTO attendance_records 
                (session_id, student_id, time_in, status, gps_latitude, gps_longitude, device_fingerprint) 
                VALUES (:session_id, :student_id, :time_in, :status, :gps_latitude, :gps_longitude, :device_fingerprint)
                ON DUPLICATE KEY UPDATE 
                time_in = VALUES(time_in),
                status = VALUES(status),
                gps_latitude = VALUES(gps_latitude),
                gps_longitude = VALUES(gps_longitude)";

        $params = [
            ':session_id' => $data['session_id'],
            ':student_id' => $data['student_id'],
            ':time_in' => $data['time_in'],
            ':status' => $data['status'],
            ':gps_latitude' => $data['gps_latitude'] ?? null,
            ':gps_longitude' => $data['gps_longitude'] ?? null,
            ':device_fingerprint' => $data['device_fingerprint'] ?? null
        ];

        $this->db->query($sql, $params);
        return $this->db->lastInsertId();
    }

    public function markTimeOut($sessionId, $studentId)
    {
        $sql = "UPDATE attendance_records 
                SET time_out = NOW() 
                WHERE session_id = :session_id AND student_id = :student_id";
        $this->db->query($sql, [
            ':session_id' => $sessionId,
            ':student_id' => $studentId
        ]);
        return true;
    }

    public function getRecords($filters = [])
    {
        $sql = "SELECT ar.*, 
                       s.student_id, s.first_name, s.last_name, s.program, s.section,
                       sub.code as subject_code, sub.name as subject_name,
                       as.session_date, as.start_time
                FROM attendance_records ar
                JOIN students s ON ar.student_id = s.id
                JOIN attendance_sessions as ON ar.session_id = as.id
                JOIN subjects sub ON as.subject_id = sub.id
                WHERE 1=1";

        $params = [];

        if (!empty($filters['student_id'])) {
            $sql .= " AND ar.student_id = :student_id";
            $params[':student_id'] = $filters['student_id'];
        }

        if (!empty($filters['session_id'])) {
            $sql .= " AND ar.session_id = :session_id";
            $params[':session_id'] = $filters['session_id'];
        }

        if (!empty($filters['status'])) {
            $sql .= " AND ar.status = :status";
            $params[':status'] = $filters['status'];
        }

        if (!empty($filters['start_date'])) {
            $sql .= " AND as.session_date >= :start_date";
            $params[':start_date'] = $filters['start_date'];
        }

        if (!empty($filters['end_date'])) {
            $sql .= " AND as.session_date <= :end_date";
            $params[':end_date'] = $filters['end_date'];
        }

        if (!empty($filters['program'])) {
            $sql .= " AND s.program = :program";
            $params[':program'] = $filters['program'];
        }

        $sql .= " ORDER BY as.session_date DESC, ar.time_in DESC";

        if (!empty($filters['limit'])) {
            $sql .= " LIMIT :limit";
            $params[':limit'] = (int)$filters['limit'];
        }

        return $this->db->fetchAll($sql, $params);
    }

    public function getActiveSessionsByInstructor($instructorId)
    {
        $sql = "SELECT ats.*, 
                       sub.code as subject_code, 
                       sub.name as subject_name,
                       sub.room,
                       CONCAT(sub.program, '-', sub.section) as section
                FROM attendance_sessions ats
                JOIN subjects sub ON ats.subject_id = sub.id
                WHERE ats.instructor_id = :instructor_id
                AND ats.status = 'active'
                ORDER BY ats.session_date DESC, ats.start_time DESC";

        return $this->db->fetchAll($sql, [':instructor_id' => $instructorId]);
    }

    public function getSessionRecords($sessionId)
    {
        $sql = "SELECT ar.*, 
                       s.student_id, 
                       s.first_name, 
                       s.last_name,
                       CONCAT(s.first_name, ' ', s.last_name) as student_name
                FROM attendance_records ar
                JOIN students s ON ar.student_id = s.id
                WHERE ar.session_id = :session_id
                ORDER BY ar.time_in ASC";

        return $this->db->fetchAll($sql, [':session_id' => $sessionId]);
    }

    public function getInstructorAttendanceLogs($instructorId, $filters = [])
    {
        $sql = "SELECT ar.*, 
                       s.student_id, 
                       s.first_name, 
                       s.last_name,
                       CONCAT(s.first_name, ' ', s.last_name) as student_name,
                       sub.code as subject_code, 
                       sub.name as subject_name,
                       CONCAT(sub.program, '-', sub.section) as section,
                       ats.session_date, 
                       ats.start_time
                FROM attendance_records ar
                JOIN students s ON ar.student_id = s.id
                JOIN attendance_sessions ats ON ar.session_id = ats.id
                JOIN subjects sub ON ats.subject_id = sub.id
                WHERE ats.instructor_id = :instructor_id";

        $params = [':instructor_id' => $instructorId];

        if (!empty($filters['subject_id'])) {
            $sql .= " AND ats.subject_id = :subject_id";
            $params[':subject_id'] = $filters['subject_id'];
        }

        if (!empty($filters['section'])) {
            $sql .= " AND CONCAT(sub.program, '-', sub.section) = :section";
            $params[':section'] = $filters['section'];
        }

        if (!empty($filters['date'])) {
            $sql .= " AND ats.session_date = :date";
            $params[':date'] = $filters['date'];
        }

        if (!empty($filters['search'])) {
            $sql .= " AND (s.first_name LIKE :search OR s.last_name LIKE :search OR s.student_id LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }

        $sql .= " ORDER BY ats.session_date DESC, ar.time_in DESC";

        if (!empty($filters['limit'])) {
            $sql .= " LIMIT :limit";
            $params[':limit'] = (int)$filters['limit'];
        }

        return $this->db->fetchAll($sql, $params);
    }

    public function validateSessionOwnership($sessionId, $instructorId)
    {
        $sql = "SELECT id FROM attendance_sessions 
                WHERE id = :session_id AND instructor_id = :instructor_id
                LIMIT 1";

        $result = $this->db->fetchOne($sql, [
            ':session_id' => $sessionId,
            ':instructor_id' => $instructorId
        ]);

        return !empty($result);
    }
}
