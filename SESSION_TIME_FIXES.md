## Session Time Display Fixes

### Issues Fixed

#### 1. **Incorrect Time Duration (Instructor Dashboard)**
**Problem**: The "Time Duration" column showed continuously increasing time (e.g., 479 mins) from when the student marked attendance to the current moment.

**Root Cause**: The `formatDurationLabel()` function was calculating `time_in` to `now()`, which kept increasing.

**Solution**: Modified the function to calculate from `time_in` to `time_out` (or current time if still in session). This now shows the actual duration the student was/is in class.

**File**: `frontend/views/intructor/active-sessions.php`
- Lines 52-68: Updated function signature and logic
- Line 229: Updated function call to pass `time_out` parameter

#### 2. **Incorrect Session Window (Student Dashboard)**
**Problem**: Student dashboard showed "01:21 AM - 03:00 AM" (the subject's recurring schedule) instead of "02:17 AM - 04:17 AM" (the actual session time).

**Root Cause**: The backend was using `Helper::getScheduleWindowForDate()` which returns the subject's weekly recurring schedule, not the actual session start/end times.

**Solution**: Changed to use the actual session's `start_time` and `end_time` from the `attendance_sessions` table. If the session hasn't ended yet, it calculates the expected end time as start_time + 2 hours.

**File**: `backend/controllers/AttendanceController.php`
- Lines 335-374: Replaced schedule-based window calculation with session-based calculation

### Expected Behavior After Fix

**Instructor Dashboard**:
- Time Duration: Shows actual time student was in class (e.g., "30 mins" if they marked attendance 30 minutes ago and haven't timed out)
- Duration stops increasing once student times out

**Student Dashboard**:
- Session Window: Shows actual session times (e.g., "02:17 AM - 04:17 AM")
- Matches the instructor's session start time

### Testing
1. Refresh the instructor's active sessions page
2. Refresh the student dashboard
3. Verify the time duration is accurate and not continuously increasing
4. Verify the session window matches the actual session start time
