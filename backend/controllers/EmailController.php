<?php

/**
 * Email Controller
 * Handles email notification API endpoints
 * CICS Attendance System
 */

require_once __DIR__ . '/../services/EmailService.php';
require_once __DIR__ . '/../middleware/Auth.php';
require_once __DIR__ . '/../utils/Response.php';

class EmailController
{
    private $emailService;

    public function __construct()
    {
        $this->emailService = new EmailService();
    }

    /**
     * Get notification data for a session
     * Returns parent and attendance information for all students in the session
     */
    public function getSessionNotifications()
    {
        Auth::requireRole('instructor');

        // Get session ID from URL parameter
        $sessionId = $_GET['session_id'] ?? null;

        if (!$sessionId) {
            Response::error('Session ID is required', null, 400);
        }

        try {
            // Get session details
            $session = $this->emailService->getSessionDetails($sessionId);

            if (!$session) {
                Response::error('Session not found', null, 404);
            }

            // Get all parents for students in this session (including absentees)
            $parents = $this->emailService->getParentsForSession($session);

            if (empty($parents)) {
                Response::success('No parents found for this session', []);
            }

            // Prepare email data for each parent
            $notifications = [];

            foreach ($parents as $parent) {
                // Get attendance record for this student
                $attendance = $this->emailService->getAttendanceDetails(
                    $sessionId,
                    $parent['student_id']
                );

                // Prepare email template data
                $emailData = $this->emailService->prepareEmailData(
                    $parent,
                    $session,
                    $attendance
                );

                // Create notification log entry
                $notificationId = $this->emailService->logNotification([
                    'parent_id' => $parent['parent_id'],
                    'student_id' => $parent['student_id'],
                    'type' => $attendance ?
                        ($attendance['status'] === 'absent' ? 'absence_alert' : 'daily_summary') :
                        'absence_alert',
                    'subject' => $this->generateEmailSubject($emailData),
                    'content' => json_encode($emailData),
                    'status' => 'pending'
                ]);

                $emailData['notification_id'] = $notificationId;
                $notifications[] = $emailData;
            }

            Response::success('Notification data retrieved', $notifications);
        } catch (Exception $e) {
            error_log("getSessionNotifications Error: " . $e->getMessage());
            Response::error('Failed to retrieve notification data: ' . $e->getMessage(), null, 500);
        }
    }

    /**
     * Log notification status update
     */
    public function logNotification()
    {
        Auth::requireRole('instructor');

        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['notification_id']) || !isset($data['status'])) {
            Response::error('Notification ID and status are required', null, 400);
        }

        try {
            $this->emailService->updateNotificationStatus(
                $data['notification_id'],
                $data['status'],
                $data['sent_at'] ?? null
            );

            Response::success('Notification status updated');
        } catch (Exception $e) {
            error_log("logNotification Error: " . $e->getMessage());
            Response::error('Failed to update notification status', null, 500);
        }
    }

    /**
     * Get notification statistics
     */
    public function getNotificationStats()
    {
        Auth::requireAuth();

        $filters = [
            'student_id' => $_GET['student_id'] ?? null,
            'parent_id' => $_GET['parent_id'] ?? null,
            'status' => $_GET['status'] ?? null,
            'date_from' => $_GET['date_from'] ?? null,
            'date_to' => $_GET['date_to'] ?? null
        ];

        // Remove null values
        $filters = array_filter($filters, function ($value) {
            return $value !== null;
        });

        try {
            $stats = $this->emailService->getNotificationStats($filters);
            Response::success('Notification statistics retrieved', $stats);
        } catch (Exception $e) {
            error_log("getNotificationStats Error: " . $e->getMessage());
            Response::error('Failed to retrieve statistics', null, 500);
        }
    }

    /**
     * Get recent notifications
     */
    public function getRecentNotifications()
    {
        Auth::requireAuth();

        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 50;
        $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;

        try {
            $notifications = $this->emailService->getRecentNotifications($limit, $offset);
            Response::success('Recent notifications retrieved', $notifications);
        } catch (Exception $e) {
            error_log("getRecentNotifications Error: " . $e->getMessage());
            Response::error('Failed to retrieve notifications', null, 500);
        }
    }

    /**
     * Generate email subject line
     * 
     * @param array $emailData
     * @return string
     */
    private function generateEmailSubject($emailData)
    {
        if ($emailData['is_absent']) {
            return "⚠️ ABSENCE ALERT: {$emailData['student_name']} - {$emailData['subject_name']} - {$emailData['session_date']}";
        }

        if ($emailData['is_late']) {
            return "⚠️ {$emailData['student_name']} was Late - {$emailData['subject_name']} - {$emailData['session_date']}";
        }

        return "{$emailData['student_name']} Attended {$emailData['subject_name']} - {$emailData['session_date']}";
    }
}
