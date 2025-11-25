/**
 * Auto-End Sessions Feature
 * Automatically ends sessions that have passed their scheduled end time
 * No cron jobs or bat files needed - runs in the browser
 */

(function() {
  'use strict';
  
  // Check if we're on the active sessions page
  if (!window.location.pathname.includes('active-sessions.php')) {
    return;
  }
  
  function checkAndAutoEndSessions() {
    const activeSessions = document.querySelectorAll('.active-session-card.is-live');
    
    activeSessions.forEach(async (card) => {
      try {
        // Find the session window text (e.g., "Tuesday 03:23 AM - 03:26 AM")
        const summaryDivs = card.querySelectorAll('.active-session-summary > div');
        let sessionWindowText = '';
        
        summaryDivs.forEach(div => {
          const label = div.querySelector('span');
          if (label && label.textContent.includes('Session Window')) {
            const value = div.querySelector('p');
            if (value) {
              sessionWindowText = value.textContent.trim();
            }
          }
        });
        
        if (!sessionWindowText) return;
        
        // Extract end time from "Tuesday 03:23 AM - 03:26 AM" format
        const match = sessionWindowText.match(/(\d{1,2}:\d{2}\s*(?:AM|PM))\s*-\s*(\d{1,2}:\d{2}\s*(?:AM|PM))/i);
        
        if (!match) return;
        
        const startTimeStr = match[1]; // e.g., "03:23 AM"
        const endTimeStr = match[2]; // e.g., "03:26 AM"
        const now = new Date();

        // Helper to parse a hh:mm AM/PM string into a Date object for today
        function parseTimeToDate(timeStr) {
          const parts = timeStr.trim().split(/\s+/);
          const [timePart, periodPart] = parts.length === 2 ? parts : [parts[0], ''];
          const [h, m] = timePart.split(':').map(Number);
          let hh = h;
          const period = (periodPart || '').toUpperCase();
          if (period === 'PM' && hh !== 12) hh += 12;
          if (period === 'AM' && hh === 12) hh = 0;
          const d = new Date();
          d.setHours(hh, m, 0, 0);
          return d;
        }

        const startTime = parseTimeToDate(startTimeStr);
        let endTime = parseTimeToDate(endTimeStr);

        // If endTime is before or equal to startTime, assume it wraps to the next day
        if (endTime.getTime() <= startTime.getTime()) {
          endTime.setDate(endTime.getDate() + 1);
        }

        // If endTime is still before now but it shouldn't be (race condition), allow a short grace period
        const GRACE_SECONDS = 30; // don't auto-end within this many seconds of now to avoid immediate endings
        const effectiveEndTime = new Date(endTime.getTime() + GRACE_SECONDS * 1000);

        // If current time is past the scheduled end time (with grace), auto-end the session
        if (now > effectiveEndTime) {
          const endButton = card.querySelector('.end-session-btn');
          if (endButton) {
            const sessionId = endButton.dataset.sessionId;
            console.log(`[Auto-End] Session ${sessionId} passed scheduled end time. Ending...`);
            
            const response = await fetch(`${window.API_BASE}/attendance/end-session`, {
              method: 'POST',
              headers: { 'Content-Type': 'application/json' },
              credentials: 'include',
              body: JSON.stringify({ session_id: sessionId })
            });
            
            const result = await response.json();
            if (response.ok && result.success) {
              console.log(`[Auto-End] Session ${sessionId} ended successfully`);
              if (window.Toast) {
                window.Toast.success('Session automatically ended (past scheduled time)');
              }
              setTimeout(() => window.location.reload(), 1500);
            }
          }
        }
      } catch (error) {
        console.error('[Auto-End] Error:', error);
      }
    });
  }
  
  // Wait for page to fully load
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
  
  function init() {
    // Check immediately after 5 seconds (give time for page to render)
    setTimeout(checkAndAutoEndSessions, 5000);
    
    // Then check every minute
    setInterval(checkAndAutoEndSessions, 60000);
    
    console.log('[Auto-End] Feature initialized. Checking every 60 seconds.');
  }
})();
