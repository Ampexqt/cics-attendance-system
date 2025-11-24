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
        
        const endTimeStr = match[2]; // e.g., "03:26 AM"
        const now = new Date();
        
        // Parse the end time
        const [time, period] = endTimeStr.split(/\s+/);
        const [hours, minutes] = time.split(':').map(Number);
        let endHours = hours;
        
        if (period.toUpperCase() === 'PM' && hours !== 12) {
          endHours += 12;
        } else if (period.toUpperCase() === 'AM' && hours === 12) {
          endHours = 0;
        }
        
        const endTime = new Date();
        endTime.setHours(endHours, minutes, 0, 0);
        
        // If current time is past the scheduled end time, auto-end the session
        if (now > endTime) {
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
