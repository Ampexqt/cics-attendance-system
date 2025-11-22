<?php

/**
 * Correction Request Model
 * CICS Attendance System
 */

require_once __DIR__ . '/../database/Database.php';

class CorrectionRequest
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getPendingByInstructor($instructorId)
    {
        $sql = "SELECT cr.*, 
                       s.student_id, s.first_name as student_first_name, s.last_name as student_last_name,
                       CONCAT(s.first_name, ' ', s.last_name) as student_name,
                       sub.code as subject_code, sub.name as subject_name,
                       ats.session_date,
                       ar.status as current_status
                FROM correction_requests cr
                JOIN attendance_records ar ON cr.attendance_id = ar.id
                JOIN students s ON cr.student_id = s.id
                JOIN attendance_sessions ats ON ar.session_id = ats.id
                JOIN subjects sub ON ats.subject_id = sub.id
                WHERE ats.instructor_id = :instructor_id
                AND cr.status = 'pending'
                ORDER BY cr.created_at DESC";

        return $this->db->fetchAll($sql, [':instructor_id' => $instructorId]);
    }

    public function approve($requestId, $instructorId)
    {
        // First verify the request belongs to this instructor
        $sql = "SELECT cr.*, ar.session_id, ats.instructor_id
                FROM correction_requests cr
                JOIN attendance_records ar ON cr.attendance_id = ar.id
                JOIN attendance_sessions ats ON ar.session_id = ats.id
                WHERE cr.id = :request_id";

        $request = $this->db->fetchOne($sql, [':request_id' => $requestId]);

        if (!$request || $request['instructor_id'] != $instructorId) {
            return false;
        }

        // Update the correction request status
        $sql = "UPDATE correction_requests 
                SET status = 'approved', admin_id = :instructor_id, updated_at = NOW()
                WHERE id = :request_id";

        $this->db->query($sql, [
            ':request_id' => $requestId,
            ':instructor_id' => $instructorId
        ]);

        // Update the attendance record with the requested status
        $sql = "UPDATE attendance_records 
                SET status = :new_status
                WHERE id = :attendance_id";

        $this->db->query($sql, [
            ':new_status' => $request['requested_status'],
            ':attendance_id' => $request['attendance_id']
        ]);

        return true;
    }

    public function reject($requestId, $instructorId, $notes = null)
    {
        // First verify the request belongs to this instructor
        $sql = "SELECT cr.*, ar.session_id, ats.instructor_id
                FROM correction_requests cr
                JOIN attendance_records ar ON cr.attendance_id = ar.id
                JOIN attendance_sessions ats ON ar.session_id = ats.id
                WHERE cr.id = :request_id";

        $request = $this->db->fetchOne($sql, [':request_id' => $requestId]);

        if (!$request || $request['instructor_id'] != $instructorId) {
            return false;
        }

        // Update the correction request status
        $sql = "UPDATE correction_requests 
                SET status = 'rejected', 
                    admin_id = :instructor_id, 
                    admin_notes = :notes,
                    updated_at = NOW()
                WHERE id = :request_id";

        $this->db->query($sql, [
            ':request_id' => $requestId,
            ':instructor_id' => $instructorId,
            ':notes' => $notes
        ]);

        return true;
    }

    public function create($data)
    {
        $sql = "INSERT INTO correction_requests 
                (attendance_id, student_id, reason, requested_status)
                VALUES (:attendance_id, :student_id, :reason, :requested_status)";

        $params = [
            ':attendance_id' => $data['attendance_id'],
            ':student_id' => $data['student_id'],
            ':reason' => $data['reason'],
            ':requested_status' => $data['requested_status']
        ];

        $this->db->query($sql, $params);
        return $this->db->lastInsertId();
    }
}
