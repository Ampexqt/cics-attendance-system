<?php
require_once __DIR__ . '/../../../auth_check.php';
require_role('admin');
$activePage = 'reports';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Generate Reports - CICS Attendance System</title>
  <link rel="stylesheet" href="../../assets/css/base/variables.css">
  <link rel="stylesheet" href="../../assets/css/pages/admin.css">
  <link rel="stylesheet" href="../../assets/css/pages/reports.css">
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
        <div class="reports-grid">
          <!-- Report Configuration Section -->
          <div class="report-config-section">
            <h2 class="section-title">Report Configuration</h2>
            
            <form id="reportForm" class="report-form">
              <!-- Report Type -->
              <div class="form-group">
                <label for="reportType">Report Type</label>
                <select id="reportType" name="reportType" class="form-control" required>
                  <option value="attendance_summary">Attendance Summary</option>
                  <option value="student_registration">Student Registration</option>
                  <option value="class_attendance">Class Attendance</option>
                  <option value="daily_attendance">Daily Attendance</option>
                </select>
              </div>

              <!-- Date Range -->
              <div class="form-group">
                <label>Date Range</label>
                <div class="date-range-group">
                  <input type="date" id="dateFrom" name="dateFrom" class="form-control" required>
                  <input type="date" id="dateTo" name="dateTo" class="form-control" required>
                </div>
              </div>

              <!-- Program -->
              <div class="form-group">
                <label for="program">Program</label>
                <select id="program" name="program" class="form-control">
                  <option value="all">All Programs</option>
                </select>
              </div>

              <!-- Year Level -->
              <div class="form-group">
                <label for="yearLevel">Year Level</label>
                <select id="yearLevel" name="yearLevel" class="form-control">
                  <option value="all">All Years</option>
                </select>
              </div>

              <!-- Section -->
              <div class="form-group">
                <label for="section">Section</label>
                <select id="section" name="section" class="form-control">
                  <option value="all">All Sections</option>
                </select>
              </div>

              <!-- Export Format -->
              <div class="form-group">
                <label>Export Format</label>
                <div class="radio-group">
                  <label class="radio-label">
                    <input type="radio" name="format" value="xlsx" checked>
                    <span>Excel (.xlsx)</span>
                  </label>
                  <label class="radio-label">
                    <input type="radio" name="format" value="csv">
                    <span>CSV (.csv)</span>
                  </label>
                  <label class="radio-label">
                    <input type="radio" name="format" value="pdf">
                    <span>PDF (.pdf)</span>
                  </label>
                </div>
              </div>

              <!-- Generate Button -->
              <button type="submit" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
                Generate Report
              </button>
            </form>
          </div>

          <!-- Recent Reports Section -->
          <div class="recent-reports-section">
            <h2 class="section-title">Recent Reports</h2>
            
            <div id="reportsListContainer" class="reports-list">
              <!-- Loading state -->
              <div class="loading-state" id="loadingState">
                <p>Loading reports...</p>
              </div>
              
              <!-- Empty state -->
              <div class="empty-state" id="emptyState" style="display: none;">
                <p>No reports generated yet.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>

  <?php include 'includes/scripts.php'; ?>
  <script src="../../assets/js/reports.js"></script>
</body>
</html>
