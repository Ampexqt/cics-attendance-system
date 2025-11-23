<?php
require_once __DIR__ . '/../../../auth_check.php';
require_role('admin');
$activePage = 'settings';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Settings - CICS Attendance System</title>
  <link rel="stylesheet" href="../../assets/css/base/variables.css">
  <link rel="stylesheet" href="../../assets/css/pages/admin.css">
  <link rel="stylesheet" href="../../assets/css/main.css">
</head>
<body>
  <div class="main-layout">
    <!-- Sidebar -->
    <?php include 'includes/sidebar.php'; ?>

    <!-- Main Content -->
    <main class="main-content">
      <?php include 'includes/header.php'; ?>

      <div class="main-body">
        <div class="page-heading">
          <div>
            <h1 class="page-title">Settings</h1>
            <p class="page-subtitle">Configure system preferences and defaults</p>
          </div>
        </div>

        <div class="card">
          <div class="card-body">
            <form id="settingsForm">
              <h2 class="section-title" style="margin-bottom: 1.5rem; font-size: 1.1rem; color: var(--text-secondary); display: flex; align-items: center; gap: 0.5rem; justify-content: flex-start;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 20px; height: 20px;">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Attendance Settings
              </h2>

              <div class="form-grid">
                <div class="form-group">
                  <label for="lateThreshold">Late Threshold (minutes)</label>
                  <input type="number" id="lateThreshold" name="late_threshold" class="form-control" min="1" required>
                  <small class="form-text text-muted">Students arriving after this time will be marked as late</small>
                </div>

                <div class="form-group">
                  <label for="absentThreshold">Absence Threshold (minutes)</label>
                  <input type="number" id="absentThreshold" name="absent_threshold" class="form-control" min="1" required>
                  <small class="form-text text-muted">Students arriving after this time will be marked as absent</small>
                </div>
              </div>

              <div class="form-group" style="margin-top: 1rem;">
                <label class="checkbox-label">
                  <input type="checkbox" id="allowOverride" name="allow_override">
                  Allow instructors to override attendance status
                </label>
              </div>

              <hr style="margin: 2rem 0; border: 0; border-top: 1px solid var(--border-color);">

              <h2 class="section-title" style="margin-bottom: 1.5rem; font-size: 1.1rem; color: var(--text-secondary); display: flex; align-items: center; gap: 0.5rem; justify-content: flex-start;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 20px; height: 20px;">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                </svg>
                Registration Settings
              </h2>

              <div class="form-group">
                <label class="checkbox-label">
                  <input type="checkbox" id="requireApproval" name="require_approval">
                  Require admin approval for new student registrations
                </label>
              </div>

              <div class="form-group">
                <label class="checkbox-label">
                  <input type="checkbox" id="sendEmailNotifications" name="send_email_notifications">
                  Send email notifications for new registrations
                </label>
              </div>

              <div class="form-actions" style="margin-top: 2rem; display: flex; justify-content: flex-end;">
                <button type="submit" class="btn btn-primary" id="saveSettingsBtn">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 3.75V16.5L12 14.25 7.5 16.5V3.75m9 0H18A2.25 2.25 0 0120.25 6v12A2.25 2.25 0 0118 20.25H6A2.25 2.25 0 013.75 18V6A2.25 2.25 0 016 3.75h1.5m9 0h-9" />
                  </svg>
                  Save Settings
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </main>
  </div>

  <?php include 'includes/scripts.php'; ?>
  <script>
    (function() {
      const API_BASE = '/cics-attendance-system/backend/api';
      const elements = {
        form: document.getElementById('settingsForm'),
        saveBtn: document.getElementById('saveSettingsBtn'),
        lateThreshold: document.getElementById('lateThreshold'),
        absentThreshold: document.getElementById('absentThreshold'),
        allowOverride: document.getElementById('allowOverride'),
        requireApproval: document.getElementById('requireApproval'),
        sendEmailNotifications: document.getElementById('sendEmailNotifications')
      };

      document.addEventListener('DOMContentLoaded', init);

      function init() {
        fetchSettings();
        elements.form.addEventListener('submit', handleSaveSettings);
      }

      async function fetchSettings() {
        try {
          const response = await fetch(`${API_BASE}/admin/settings/system`, { credentials: 'include' });
          const result = await response.json();

          if (!response.ok || !result.success) {
            throw new Error(result.message || 'Failed to load settings');
          }

          const settings = result.data;
          
          // Populate form
          elements.lateThreshold.value = settings.late_threshold || 15;
          elements.absentThreshold.value = settings.absent_threshold || 30;
          elements.allowOverride.checked = !!settings.allow_override;
          elements.requireApproval.checked = !!settings.require_approval;
          elements.sendEmailNotifications.checked = !!settings.send_email_notifications;

        } catch (error) {
          Toast.error('Unable to load settings. Please refresh the page.');
          console.error(error);
        }
      }

      async function handleSaveSettings(event) {
        event.preventDefault();
        
        if (!elements.form.checkValidity()) {
          elements.form.reportValidity();
          return;
        }

        setSavingState(true);

        const payload = {
          late_threshold: parseInt(elements.lateThreshold.value),
          absent_threshold: parseInt(elements.absentThreshold.value),
          allow_override: elements.allowOverride.checked,
          require_approval: elements.requireApproval.checked,
          send_email_notifications: elements.sendEmailNotifications.checked
        };

        try {
          const response = await fetch(`${API_BASE}/admin/settings/system`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            credentials: 'include',
            body: JSON.stringify(payload)
          });

          const result = await response.json();

          if (!response.ok || !result.success) {
            throw new Error(result.message || 'Failed to save settings');
          }

          Toast.success('System settings saved successfully');
        } catch (error) {
          Toast.error(error.message || 'Unable to save settings.');
        } finally {
          setSavingState(false);
        }
      }

      function setSavingState(isSaving) {
        elements.saveBtn.disabled = isSaving;
        elements.saveBtn.innerHTML = isSaving ? 'Saving...' : `
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 3.75V16.5L12 14.25 7.5 16.5V3.75m9 0H18A2.25 2.25 0 0120.25 6v12A2.25 2.25 0 0118 20.25H6A2.25 2.25 0 013.75 18V6A2.25 2.25 0 016 3.75h1.5m9 0h-9" />
          </svg>
          Save Settings`;
      }
    })();
  </script>
</body>
</html>
