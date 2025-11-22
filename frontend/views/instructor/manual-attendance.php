<?php
require_once __DIR__ . '/../../../auth_check.php';
// require_role('instructor');
$activePage = 'manual-attendance';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manual Attendance - CICS Attendance System</title>
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
        <h2 style="font-size: var(--font-size-2xl); font-weight: var(--font-weight-semibold); color: #1A2B47; margin-bottom: var(--spacing-lg);">Manual Attendance</h2>

        <!-- Manual Attendance Card -->
        <div class="card" style="max-width: 600px; margin: 0 auto;">
          <div class="card-body">
            <div style="display: flex; flex-direction: column; gap: var(--spacing-lg);">
              <!-- Subject Selection -->
              <div class="filter-group">
                <label class="filter-label">Subject</label>
                <select class="form-control">
                  <option>Select subject</option>
                  <option>Data Structures</option>
                  <option>Web Development</option>
                  <option>Database Systems</option>
                </select>
              </div>

              <!-- Section Selection -->
              <div class="filter-group">
                <label class="filter-label">Section</label>
                <select class="form-control">
                  <option>Select section</option>
                  <option>CS-3A</option>
                  <option>IT-2B</option>
                  <option>CS-3B</option>
                </select>
              </div>

              <!-- Search Student -->
              <div class="filter-group">
                <label class="filter-label">Search Student</label>
                <input type="text" class="form-control" placeholder="Enter student name or ID...">
              </div>

              <!-- Action Buttons -->
              <div class="attendance-buttons" style="margin-top: var(--spacing-md);">
                <button class="btn btn-success" style="flex: 1; padding: var(--spacing-md); display: flex; align-items: center; justify-content: center; gap: var(--spacing-sm);">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 1.25rem; height: 1.25rem;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                  </svg>
                  Mark Present
                </button>
                <button class="btn btn-danger" style="flex: 1; padding: var(--spacing-md); display: flex; align-items: center; justify-content: center; gap: var(--spacing-sm);">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 1.25rem; height: 1.25rem;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                  Mark Absent
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>

  <?php include 'includes/scripts.php'; ?>
</body>

</html>

