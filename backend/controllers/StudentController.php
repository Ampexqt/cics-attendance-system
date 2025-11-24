<?php

/**
 * Student Controller
 * CICS Attendance System
 */

require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../models/Subject.php';
require_once __DIR__ . '/../middleware/Auth.php';
require_once __DIR__ . '/../utils/Response.php';
require_once __DIR__ . '/../utils/Helper.php';

class StudentController
{
    private $studentModel;
    private $subjectModel;

    public function __construct()
    {
        $this->studentModel = new Student();
        $this->subjectModel = new Subject();
    }

    public function getSchedule()
    {
        Auth::requireRole('student');

        $userId = Auth::userId();
        $student = $this->studentModel->findByUserId($userId);

        if (!$student) {
            error_log("Student not found for user ID: " . $userId);
            Response::error('Student record not found', null, 404);
        }

        // Get subjects for the student's program and year level
        // We will filter by section manually to handle "1B" vs "B" mismatch
        $filters = [
            'program' => $student['program'],
            'year_level' => $student['year_level']
        ];

        error_log("Fetching subjects with filters: " . json_encode($filters));

        $allSubjects = $this->subjectModel->getAll($filters);
        error_log("Found " . count($allSubjects) . " subjects for program/year.");

        $subjects = [];
        $studentSection = strtoupper(trim($student['section'])); // e.g., "1B"

        foreach ($allSubjects as $subject) {
            $subjectSection = strtoupper(trim($subject['section'])); // e.g., "B"

            // Logic to match section:
            // 1. Exact match
            // 2. Student section ends with subject section (e.g. "1B" ends with "B")
            // 3. Subject section is contained in student section

            if (
                $studentSection === $subjectSection ||
                substr($studentSection, -strlen($subjectSection)) === $subjectSection ||
                strpos($studentSection, $subjectSection) !== false
            ) {
                $subjects[] = $subject;
            }
        }

        error_log("Filtered to " . count($subjects) . " subjects for section " . $studentSection);

        // Parse schedules into a structured format for the frontend
        $weeklySchedule = [
            'Monday' => [],
            'Tuesday' => [],
            'Wednesday' => [],
            'Thursday' => [],
            'Friday' => [],
            'Saturday' => [],
            'Sunday' => []
        ];

        foreach ($subjects as $subject) {
            error_log("Processing subject: " . $subject['code'] . " - Schedule: " . $subject['schedule']);
            $parsedSchedules = $this->parseScheduleString($subject['schedule']);
            error_log("Parsed schedules: " . json_encode($parsedSchedules));

            foreach ($parsedSchedules as $sched) {
                $day = $sched['day'];
                if (isset($weeklySchedule[$day])) {
                    $weeklySchedule[$day][] = [
                        'subject_code' => $subject['code'],
                        'subject_name' => $subject['name'],
                        'instructor' => $subject['instructor_name'],
                        'room' => $subject['room'],
                        'start_time' => $sched['start_time'],
                        'end_time' => $sched['end_time'],
                        'raw_time' => $sched['raw_time']
                    ];
                }
            }
        }

        // Sort schedules by time
        foreach ($weeklySchedule as $day => &$daySchedule) {
            usort($daySchedule, function ($a, $b) {
                return strtotime($a['start_time']) - strtotime($b['start_time']);
            });
        }

        Response::success('Schedule retrieved successfully', $weeklySchedule);
    }

    /**
     * Helper to parse schedule strings like "MW 10:00-11:30" or "Monday 10:30am to 12:00pm"
     */
    private function parseScheduleString($scheduleStr)
    {
        $results = [];

        // Normalize string
        $scheduleStr = trim($scheduleStr);

        // Common patterns:
        // MW 10:00 AM - 11:30 AM
        // TTh 1:00 PM - 2:30 PM
        // Monday 10:30am to 12:00pm

        // Extract days and time
        // Regex to separate days from time
        preg_match('/^([A-Za-z,\s]+)\s+(\d{1,2}:.*)$/', $scheduleStr, $matches);

        if (count($matches) >= 3) {
            $daysPart = trim($matches[1]);
            $timePart = trim($matches[2]);

            $days = [];

            // Check for full day names
            $fullDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            $foundFullDay = false;
            foreach ($fullDays as $fd) {
                if (stripos($daysPart, $fd) !== false) {
                    $days[] = $fd;
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

                $tempDays = str_replace('Th', 'X', $daysPart); // X represents Thursday
                $tempDays = str_replace('Su', 'Y', $tempDays); // Y represents Sunday

                // Remove spaces and commas
                $tempDays = preg_replace('/[\s,]/', '', $tempDays);

                $chars = str_split($tempDays);
                foreach ($chars as $char) {
                    if ($char == 'X') $days[] = 'Thursday';
                    elseif ($char == 'Y') $days[] = 'Sunday';
                    elseif (isset($daysMap[$char])) $days[] = $daysMap[$char];
                }
            }

            // Parse time
            // Handle " to " and " - " separators
            $timePart = str_ireplace(' to ', '-', $timePart);
            $timePart = str_replace(' ', '', $timePart); // Remove spaces
            $times = explode('-', $timePart);

            if (count($times) == 2) {
                $startTime = $this->formatTime($times[0]);
                $endTime = $this->formatTime($times[1]);

                foreach ($days as $day) {
                    $results[] = [
                        'day' => $day,
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                        'raw_time' => $matches[2] // Keep original time string
                    ];
                }
            }
        }

        return $results;
    }

    private function formatTime($timeStr)
    {
        // Clean up time string (remove am/pm spaces if any remaining)
        return date('H:i', strtotime($timeStr));
    }
}
