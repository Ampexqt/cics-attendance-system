<?php
require_once __DIR__ . '/../../../auth_check.php';
// require_role('instructor');
$activePage = 'attendance-logs';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Attendance Logs - CICS Attendance System</title>
  <link rel="stylesheet" href="../../assets/css/base/variables.css">
  <link rel="stylesheet" href="../../assets/css/pages/instructor.css">
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
        <!-- Page Title -->
        <h2 style="font-size: var(--font-size-2xl); font-weight: var(--font-weight-semibold); color: #1A2B47; margin-bottom: var(--spacing-lg);">Attendance Logs</h2>

        <!-- Attendance Logs Card -->
        <div class="card">
          <div class="card-body">
            <!-- Filter Controls -->
            <div class="filter-controls">
              <div class="filter-group">
                <label class="filter-label">Subject</label>
                <select class="form-select" style="width: 200px;">
                  <option>All Subjects</option>
                  <option>Data Structures</option>
                  <option>Web Development</option>
                  <option>Database Systems</option>
                </select>
              </div>
              <div class="filter-group">
                <label class="filter-label">Section</label>
                <select class="form-select" style="width: 200px;">
                  <option>All Sections</option>
                  <option>CS-3A</option>
                  <option>IT-2B</option>
                  <option>CS-3B</option>
                </select>
              </div>
              <div class="filter-group">
                <label class="filter-label">Date</label>
                <input type="date" class="form-control" style="width: 200px;">
              </div>
              <div class="filter-group" style="flex: 1; min-width: 250px;">
                <label class="filter-label">Search</label>
                <div class="search-container">
                  <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                  </svg>
                  <input type="text" class="search-input" placeholder="Search students...">
                </div>
              </div>
            </div>

            <!-- Attendance Log Table -->
            <table class="table">
              <thead>
                <tr>
                  <th>STUDENT NAME</th>
                  <th>TIME-IN</th>
                  <th>STATUS</th>
                  <th>NOTES</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Alice Johnson</td>
                  <td>08:05 AM</td>
                  <td><span class="status-badge present">Present</span></td>
                  <td>-</td>
                </tr>
                <tr>
                  <td>Bob Smith</td>
                  <td>08:15 AM</td>
                  <td><span class="status-badge late">Late</span></td>
                  <td>Traffic</td>
                </tr>
                <tr>
                  <td>Carol White</td>
                  <td>-</td>
                  <td><span class="status-badge absent">Absent</span></td>
                  <td>Sick leave</td>
                </tr>
                <tr>
                  <td>David Brown</td>
                  <td>08:02 AM</td>
                  <td><span class="status-badge present">Present</span></td>
                  <td>-</td>
                </tr>
                <tr>
                  <td>Emma Davis</td>
                  <td>08:20 AM</td>
                  <td><span class="status-badge late">Late</span></td>
                  <td>Family emergency</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </main>
  </div>

  <?php include 'includes/scripts.php'; ?>
</body>

</html>

