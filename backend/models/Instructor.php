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
        
        // Process each subject's schedule with enhanced parsing
        foreach ($subjects as $subject) {
            if (!empty($subject['schedule'])) {
                // Parse the schedule string
                $parsedSchedules = $this->parseScheduleString($subject['schedule']);
                
                foreach ($parsedSchedules as $sched) {
                    $day = $sched['day'];
                    if (isset($weeklySchedule[$day])) {
                        $weeklySchedule[$day][] = [
                            'subject_code' => $subject['code'],
                            'subject_name' => $subject['name'],
                            'section' => $subject['section'],
                            'time' => $sched['raw_time'],
                            'start_time' => $sched['start_time'],
                            'end_time' => $sched['end_time'],
                            'room' => $subject['room'] ?? 'TBA'
                        ];
                    }
                }
            }
        }
        
        // Sort each day's schedule by start time
        foreach ($weeklySchedule as $day => &$daySchedule) {
            usort($daySchedule, function ($a, $b) {
                return strtotime($a['start_time']) - strtotime($b['start_time']);
            });
        }
        
        return $weeklySchedule;
    }

    /**
     * Parse schedule strings like "MW 10:00-11:30" or "Monday 10:30am to 12:00pm"
     * 
     * @param string $scheduleStr The schedule string to parse
     * @return array Array of parsed schedules with day, start_time, end_time, and raw_time
     */
    private function parseScheduleString($scheduleStr)
    {
        $results = [];
        
        // Normalize string
        $scheduleStr = trim($scheduleStr);
        
        if (empty($scheduleStr)) {
            return $results;
        }

        // Support multiple day/time segments separated by semicolons or pipes
        $segments = preg_split('/;|\\\|/', $scheduleStr);

        foreach ($segments as $segment) {
            $segment = trim($segment);
            if ($segment === '') continue;

            // Regex to separate days from time in this segment
            preg_match('/^([A-Za-z,\\s]+)\\s+(\\d{1,2}:.*)$/', $segment, $segMatches);

            if (count($segMatches) < 3) {
                // Could not parse this segment, skip
                continue;
            }

            $segDaysPart = trim($segMatches[1]);
            $segTimePart = trim($segMatches[2]);

            $segDays = [];

            // Check for full day names
            $fullDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            $foundFullDay = false;
            foreach ($fullDays as $fd) {
                if (stripos($segDaysPart, $fd) !== false) {
                    $segDays[] = $fd;
                    $foundFullDay = true;
                }
            }

            // If no full day found, try abbreviated parsing
            if (!$foundFullDay) {
                $daysMap = [
                    'M' => 'Monday',
                    'T' => 'Tuesday',
                    'W' => 'Wednesday',
                    'Th' => 'Thursday',
                    'F' => 'Friday',
                    'S' => 'Saturday',
                    'Su' => 'Sunday'
                ];

                $tempDays = str_replace('Th', 'X', $segDaysPart);
                $tempDays = str_replace('Su', 'Y', $tempDays);

                // Remove spaces and commas
                $tempDays = preg_replace('/[\\s,]/', '', $tempDays);

                $chars = str_split($tempDays);
                foreach ($chars as $char) {
                    if ($char == 'X') $segDays[] = 'Thursday';
                    elseif ($char == 'Y') $segDays[] = 'Sunday';
                    elseif (isset($daysMap[$char])) $segDays[] = $daysMap[$char];
                }
            }

            // Parse time for this segment
            $segTimePart = str_ireplace(' to ', '-', $segTimePart);
            $segTimePart = trim($segTimePart);

            // Split by hyphen allowing spaces around it
            $times = preg_split('/\\s*-\\s*/', $segTimePart);

            if (count($times) == 2) {
                $startTime = $this->formatTime($times[0]);
                $endTime = $this->formatTime($times[1]);

                foreach ($segDays as $day) {
                    $results[] = [
                        'day' => $day,
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                        'raw_time' => $segMatches[2]
                    ];
                }
            }
        }

        return $results;
    }

    /**
     * Format time string to 24-hour format (HH:mm)
     * 
     * @param string $timeStr The time string to format
     * @return string Formatted time in HH:mm format
     */
    private function formatTime($timeStr)
    {
        return date('H:i', strtotime($timeStr));
    }
}
