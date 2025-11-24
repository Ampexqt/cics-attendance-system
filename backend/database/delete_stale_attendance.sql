-- Delete stale attendance records from today's active sessions
-- This will allow students to mark attendance fresh

-- First, check what will be deleted
SELECT 
    ar.id,
    ar.session_id,
    s.first_name,
    s.last_name,
    ar.time_in,
    TIMESTAMPDIFF(MINUTE, ar.time_in, NOW()) as minutes_ago,
    ases.session_date,
    ases.start_time
FROM attendance_records ar
JOIN students s ON ar.student_id = s.id
JOIN attendance_sessions ases ON ar.session_id = ases.id
WHERE ases.status = 'active'
  AND ases.session_date = CURDATE()
  AND TIMESTAMPDIFF(MINUTE, ar.time_in, NOW()) > 240; -- More than 4 hours old

-- Uncomment the line below to actually delete the stale records
-- DELETE ar FROM attendance_records ar
-- JOIN attendance_sessions ases ON ar.session_id = ases.id
-- WHERE ases.status = 'active'
--   AND ases.session_date = CURDATE()
--   AND TIMESTAMPDIFF(MINUTE, ar.time_in, NOW()) > 240;
