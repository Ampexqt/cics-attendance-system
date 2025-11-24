<?php
/**
 * Instructor Model
 * CICS Attendance System
 */

require_once __DIR__ . '/../database/Database.php';

class Instructor {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function create($data) {
        $sql = "INSERT INTO instructors (user_id, first_name, last_name, department, employee_id)
                VALUES (:user_id, :first_name, :last_name, :department, :employee_id)";

        $params = [
            ':user_id' => $data['user_id'],
            ':first_name' => $data['first_name'],
            ':last_name' => $data['last_name'],
            ':department' => $data['department'],
            ':employee_id' => $data['employee_id'] ?? null
        ];

        $this->db->query($sql, $params);
        return $this->db->lastInsertId();
    }

    public function findByUserId($userId) {
        $sql = "SELECT i.*, u.email, u.status as user_status
                FROM instructors i
                JOIN users u ON i.user_id = u.id
                WHERE i.user_id = :user_id
                LIMIT 1";
        return $this->db->fetchOne($sql, [':user_id' => $userId]);
    }

    public function findById($id) {
        $sql = "SELECT i.*, u.email, u.status as user_status
                FROM instructors i
                JOIN users u ON i.user_id = u.id
                WHERE i.id = :id
                LIMIT 1";
        return $this->db->fetchOne($sql, [':id' => $id]);
    }

    public function getAll($filters = []) {
        $sql = "SELECT i.*, u.email, u.status as user_status
                FROM instructors i
                JOIN users u ON i.user_id = u.id
                WHERE u.status != 'inactive'";
        $params = [];

        if (!empty($filters['department'])) {
            $sql .= " AND i.department = :department";
            $params[':department'] = $filters['department'];
        }

        if (!empty($filters['status'])) {
            $sql .= " AND u.status = :status";
            $params[':status'] = $filters['status'];
        }

        $sql .= " ORDER BY i.last_name, i.first_name";

        return $this->db->fetchAll($sql, $params);
    }

    public function update($id, $data) {
        $fields = [];
        $params = [':id' => $id];

        $allowedFields = ['first_name', 'last_name', 'department', 'employee_id'];
        
        foreach ($allowedFields as $field) {
            if (array_key_exists($field, $data)) {
                $fields[] = "$field = :$field";
                $params[":$field"] = $data[$field];
            }
        }

        if (empty($fields)) {
            return false;
        }

        $sql = "UPDATE instructors SET " . implode(', ', $fields) . " WHERE id = :id";
        $this->db->query($sql, $params);
        return true;
    }

    /**
     * Get all subjects assigned to an instructor
     * 
     * @param int $instructorId The ID of the instructor
     * @return array Array of subjects assigned to the instructor
     */
    public function getAssignedSubjects($instructorId) {
        $sql = "SELECT s.* 
                FROM subjects s
                WHERE s.instructor_id = :instructor_id
                ORDER BY s.code, s.name";
                
        return $this->db->fetchAll($sql, [':instructor_id' => $instructorId]);
    }

    /**
     * Get weekly schedule for an instructor
     * 
     * @param int $instructorId The ID of the instructor
     * @return array Weekly schedule organized by day of the week
     */
    public function getWeeklySchedule($instructorId) {
        // Get all assigned subjects with their schedules
        $subjects = $this->getAssignedSubjects($instructorId);
        
        // Initialize the weekly schedule with empty arrays for each day
        $weeklySchedule = [
            'Monday' => [],
            'Tuesday' => [],
            'Wednesday' => [],
            'Thursday' => [],
            'Friday' => [],
            'Saturday' => [],
            'Sunday' => []
        ];
        
        // Process each subject's schedule
        foreach ($subjects as $subject) {
            if (!empty($subject['schedule'])) {
                // Parse the schedule string (format: "Day Time - Time, Room")
                // Example: "Monday 08:00 AM - 10:00 AM, Room 301"
                $scheduleParts = explode(',', $subject['schedule'], 2);
                $timePart = trim($scheduleParts[0]);
                $room = isset($scheduleParts[1]) ? trim($scheduleParts[1]) : ($subject['room'] ?? 'TBA');
                
                // Extract day and time
                $day = '';
                $timeRange = '';
                $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                
                foreach ($days as $dayName) {
                    $pos = stripos($timePart, $dayName);
                    if ($pos !== false) {
                        $day = $dayName;
                        $timeRange = trim(str_replace($dayName, '', $timePart));
                        break;
                    }
                }
                
                // If day is found, add to the weekly schedule
                if ($day && $timeRange) {
                    $weeklySchedule[$day][] = [
                        'subject_code' => $subject['code'],
                        'subject_name' => $subject['name'],
                        'section' => $subject['section'],
                        'time' => $timeRange,
                        'room' => $room
                    ];
                }
            }
        }
        
        return $weeklySchedule;
    }
}


