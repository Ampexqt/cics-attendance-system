-- Check the actual time_in value in the database
SELECT 
    ar.id,
    ar.session_id,
    ar.student_id,
    ar.time_in,
    ar.time_out,
    ar.status,
    s.first_name,
    s.last_name,
    ases.session_date,
    ases.start_time,
    NOW() as current_time,
    TIMESTAMPDIFF(MINUTE, ar.time_in, NOW()) as minutes_since_timein
FROM attendance_records ar
JOIN students s ON ar.student_id = s.id
JOIN attendance_sessions ases ON ar.session_id = ases.id
WHERE ases.status = 'active'
  AND ases.session_date = CURDATE()
ORDER BY ar.time_in DESC;
