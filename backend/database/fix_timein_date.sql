-- SIMPLE FIX: Update the student's time_in to match when they actually marked attendance today
-- This will make the duration show correctly (counting up from when they marked attendance)

UPDATE attendance_records ar
JOIN attendance_sessions ases ON ar.session_id = ases.id
SET ar.time_in = CONCAT(CURDATE(), ' ', TIME(ar.time_in))
WHERE ases.status = 'active'
  AND ases.session_date = CURDATE()
  AND DATE(ar.time_in) != CURDATE();

-- This updates any attendance records where the DATE is wrong (from yesterday/earlier)
-- It keeps the TIME part but changes the DATE to today
-- So if time_in was "2025-11-24 03:13:00", it becomes "2025-11-25 03:13:00"
