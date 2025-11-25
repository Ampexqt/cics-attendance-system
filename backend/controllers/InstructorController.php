<?php

/**
 * Instructor Controller
 * CICS Attendance System
 */

require_once __DIR__ . '/../models/Instructor.php';
require_once __DIR__ . '/../middleware/Auth.php';
require_once __DIR__ . '/../utils/Response.php';

class InstructorController
{
    private $instructorModel;

    public function __construct()
    {
        $this->instructorModel = new Instructor();
    }

    /**
     * Get instructor's weekly schedule
     * Returns schedule organized by day of the week with parsed times
     */
    public function getSchedule()
    {
        try {
            Auth::requireRole('instructor');

            $userId = Auth::userId();
            $instructor = $this->instructorModel->findByUserId($userId);

            if (!$instructor) {
                Response::error('Instructor record not found', null, 404);
            }

            // Get the weekly schedule from the model
            $weeklySchedule = $this->instructorModel->getWeeklySchedule($instructor['id']);

            Response::success('Instructor schedule retrieved successfully', $weeklySchedule);
        } catch (\Throwable $e) {
            error_log("getSchedule Error: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            Response::error('Server Error: ' . $e->getMessage(), null, 500);
        }
    }

    /**
     * Get instructor's assigned subjects
     * Returns all subjects assigned to the instructor with full details
     */
    public function getAssignedSubjects()
    {
        try {
            Auth::requireRole('instructor');

            $userId = Auth::userId();
            $instructor = $this->instructorModel->findByUserId($userId);

            if (!$instructor) {
                Response::error('Instructor record not found', null, 404);
            }

            $subjects = $this->instructorModel->getAssignedSubjects($instructor['id']);

            Response::success('Assigned subjects retrieved successfully', $subjects);
        } catch (\Throwable $e) {
            error_log("getAssignedSubjects Error: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            Response::error('Server Error: ' . $e->getMessage(), null, 500);
        }
    }

    /**
     * Get instructor profile
     * Returns instructor details with user information
     */
    public function getProfile()
    {
        try {
            Auth::requireRole('instructor');

            $userId = Auth::userId();
            $instructor = $this->instructorModel->findByUserId($userId);

            if (!$instructor) {
                Response::error('Instructor record not found', null, 404);
            }

            Response::success('Instructor profile retrieved successfully', $instructor);
        } catch (\Throwable $e) {
            error_log("getProfile Error: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            Response::error('Server Error: ' . $e->getMessage(), null, 500);
        }
    }
}
