-- Simple query to delete ALL attendance records for today's active sessions
-- This will force students to mark attendance fresh

DELETE FROM attendance_records 
WHERE session_id IN (
    SELECT id FROM attendance_sessions 
    WHERE status = 'active' 
    AND session_date = CURDATE()
);
