## Auto-End Sessions Feature

### Overview
Sessions will now automatically end when they pass their scheduled end time. No cron jobs, bat files, or server-side scheduling needed!

### How It Works
1. **JavaScript-Based**: Runs in the instructor's browser when they have the active sessions page open
2. **Checks Every Minute**: Scans all active sessions every 60 seconds
3. **Compares Times**: Checks if current time > scheduled end time
4. **Auto-Ends**: Calls the API to end the session automatically
5. **Notifies**: Shows a toast notification and refreshes the page

### Files Added
- `frontend/assets/js/auto-end-sessions.js` - The auto-end logic
- Modified `frontend/views/intructor/active-sessions.php` - Added script tag

### Example
- **Schedule**: Tuesday 03:23 AM - 03:26 AM
- **Session Started**: 03:23 AM
- **Current Time**: 03:27 AM
- **Result**: ✅ Session automatically ends at 03:27 AM (1 minute after scheduled end)

### Benefits
- ✅ No server-side cron jobs needed
- ✅ No Windows Task Scheduler needed
- ✅ Pure JavaScript solution
- ✅ Works as long as instructor has page open
- ✅ Checks every minute for accuracy

### Limitations
- Requires the instructor to have the active sessions page open
- If the page is closed, sessions won't auto-end until page is reopened
- Sessions will end within 1 minute of scheduled time (due to 60-second check interval)

### Console Logs
The feature logs to browser console:
```
[Auto-End] Feature initialized. Checking every 60 seconds.
[Auto-End] Session 123 passed scheduled end time. Ending...
[Auto-End] Session 123 ended successfully
```

### Testing
1. Create a subject with a short schedule (e.g., 3 minutes)
2. Start the session
3. Wait for the scheduled end time to pass
4. Within 1 minute, the session should automatically end
5. Page will refresh and show "Session automatically ended" toast
