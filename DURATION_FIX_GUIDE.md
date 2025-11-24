## Duration Display Issue - Root Cause & Solution

### Problem
The "TIME DURATION" shows **476 mins** instead of the expected **~5 mins**.

### Root Cause
The student's `time_in` in the database is from **~8 hours ago** (476 minutes), likely from previous testing. The database stores the full datetime (e.g., "2025-11-24 18:54:00"), but the display only shows the TIME part ("02:54 AM"), making it appear current.

### Why This Happens
1. Student marked attendance during testing hours ago
2. The attendance record was never deleted
3. When the instructor starts a NEW session today, the OLD attendance record is still there
4. The duration calculates from the OLD time_in to NOW = 476 minutes

### Immediate Solution
**Delete the stale attendance record** from the database:

```sql
-- Run this in phpMyAdmin
DELETE ar FROM attendance_records ar
JOIN attendance_sessions ases ON ar.session_id = ases.id
WHERE ases.status = 'active'
  AND ases.session_date = CURDATE()
  AND TIMESTAMPDIFF(MINUTE, ar.time_in, NOW()) > 240; -- More than 4 hours old
```

After deleting, have the student mark attendance again - it will show "Just now" or "1 mins" correctly.

### Long-term Prevention
The attendance system should automatically:
1. **Clear old records** when starting a new session
2. **Validate time_in** is within the session window
3. **Prevent duplicate** attendance records for the same session

### Files to Check
- `backend/database/delete_stale_attendance.sql` - SQL script to clean up
- `backend/database/check_timein.sql` - SQL to verify the issue

### Steps to Fix
1. Open phpMyAdmin
2. Select `cics_attendance` database
3. Run the DELETE query from `delete_stale_attendance.sql`
4. Refresh the instructor's active sessions page
5. Have the student mark attendance again
6. Duration should now show correctly (0-5 mins)
