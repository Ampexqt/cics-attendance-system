-- Debug queries to check student and session data

-- 1. Check all active sessions today
SELECT 
    ases.id as session_id,
    ases.session_date,
    ases.start_time,
    ases.status,
    subj.code,
    subj.name,
    subj.program,
    subj.year_level,
    subj.section,
    CONCAT(i.first_name, ' ', i.last_name) as instructor
FROM attendance_sessions ases
LEFT JOIN subjects subj ON ases.subject_id = subj.id
LEFT JOIN instructors i ON subj.instructor_id = i.id
WHERE ases.status = 'active' 
  AND ases.session_date = CURDATE()
ORDER BY ases.start_time DESC;

-- 2. Check student enrollment details
SELECT 
    s.id,
    s.student_id,
    CONCAT(s.first_name, ' ', s.last_name) as name,
    s.program,
    s.year_level,
    s.section,
    u.email,
    u.status
FROM students s
JOIN users u ON s.user_id = u.id
WHERE u.status = 'active'
ORDER BY s.last_name, s.first_name;

-- 3. Find potential matches between students and active sessions
SELECT 
    s.student_id,
    CONCAT(s.first_name, ' ', s.last_name) as student_name,
    s.program as student_program,
    s.year_level as student_year,
    s.section as student_section,
    subj.code,
    subj.name as subject_name,
    subj.program as subject_program,
    subj.year_level as subject_year,
    subj.section as subject_section,
    CASE 
        WHEN s.program = subj.program 
         AND s.year_level = subj.year_level 
         AND (
             UPPER(TRIM(subj.section)) = UPPER(TRIM(s.section))
             OR UPPER(TRIM(subj.section)) LIKE CONCAT('%', UPPER(TRIM(s.section)))
             OR UPPER(TRIM(s.section)) LIKE CONCAT('%', UPPER(TRIM(subj.section)))
         )
        THEN 'MATCH'
        ELSE 'NO MATCH'
    END as match_status
FROM students s
CROSS JOIN (
    SELECT subj.* 
    FROM attendance_sessions ases
    JOIN subjects subj ON ases.subject_id = subj.id
    WHERE ases.status = 'active' AND ases.session_date = CURDATE()
) subj
JOIN users u ON s.user_id = u.id
WHERE u.status = 'active'
ORDER BY match_status DESC, s.last_name;

-- 4. Check for orphan sessions (sessions without valid subjects)
SELECT 
    ases.id,
    ases.subject_id,
    ases.session_date,
    ases.start_time,
    ases.status,
    CASE WHEN subj.id IS NULL THEN 'ORPHAN' ELSE 'OK' END as subject_status
FROM attendance_sessions ases
LEFT JOIN subjects subj ON ases.subject_id = subj.id
WHERE ases.status = 'active' 
  AND ases.session_date = CURDATE();
