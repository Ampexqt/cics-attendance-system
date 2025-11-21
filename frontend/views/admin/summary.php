<?php
require_once __DIR__ . '/../../../auth_check.php';
require_role('admin');
$activePage = 'summary';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Attendance Summary - CICS Attendance System</title>
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
            <h1 class="page-title">Attendance Summary</h1>
            <p class="page-subtitle">Filter and export attendance logs</p>
          </div>
        </div>

        <div class="card">
          <div class="card-body">
            <!-- Filters -->
            <div class="filters-grid">
              <div class="form-field">
                <label>Date Range</label>
                <select class="form-control">
                  <option>This Week</option>
                  <option>This Month</option>
                  <option>This Year</option>
                </select>
              </div>
              <div class="form-field">
                <label>Subject</label>
                <select class="form-control">
                  <option>All Subjects</option>
                  <option>CS101</option>
                  <option>IT205</option>
                </select>
              </div>
              <div class="form-field">
                <label>Section</label>
                <select class="form-control">
                  <option>All Sections</option>
                  <option>BSCS 3A</option>
                  <option>BSIT 2B</option>
                </select>
              </div>
              <div class="form-field">
                <label>&nbsp;</label>
                <button type="button" class="btn btn-primary btn-block">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z" />
                  </svg>
                  Apply Filters
                </button>
              </div>
            </div>

            <div class="card-header">
              <h3 class="card-title">Attendance Records</h3>
            </div>

            <div class="table-actions table-actions-below">
              <div class="search-container table-search">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="search-icon">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
                <input type="text" class="search-input" placeholder="Search students...">
              </div>
              <button class="btn btn-warning">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                Export
              </button>
            </div>

            <div class="table-container">
              <table class="table">
                <thead>
                  <tr>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Subject</th>
                    <th>Section</th>
                    <th>Date</th>
                    <th>Time In</th>
                    <th>Time Out</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>2023-0001</td>
                    <td>Juan Dela Cruz</td>
                    <td>CS101</td>
                    <td>BSCS 3A</td>
                    <td>2023-09-18</td>
                    <td>8:00 AM</td>
                    <td>11:00 AM</td>
                    <td><span class="badge badge-success">Present</span></td>
                  </tr>
                  <tr>
                    <td>2023-0002</td>
                    <td>Maria Santos</td>
                    <td>IT205</td>
                    <td>BSIT 2B</td>
                    <td>2023-09-18</td>
                    <td>8:05 AM</td>
                    <td>11:00 AM</td>
                    <td><span class="badge badge-success">Present</span></td>
                  </tr>
                  <tr>
                    <td>2023-0003</td>
                    <td>Pedro Gonzales</td>
                    <td>CS301</td>
                    <td>BSIS 4A</td>
                    <td>2023-09-18</td>
                    <td>8:15 AM</td>
                    <td>11:00 AM</td>
                    <td><span class="badge badge-warning">Late</span></td>
                  </tr>
                  <tr>
                    <td>2023-0004</td>
                    <td>Ana Reyes</td>
                    <td>CS101</td>
                    <td>BSCS 3A</td>
                    <td>2023-09-18</td>
                    <td>8:30 AM</td>
                    <td>11:00 AM</td>
                    <td><span class="badge badge-warning">Late</span></td>
                  </tr>
                  <tr>
                    <td>2023-0005</td>
                    <td>Carlos Mendoza</td>
                    <td>IT110</td>
                    <td>BSIT 3A</td>
                    <td>2023-09-18</td>
                    <td>-</td>
                    <td>-</td>
                    <td><span class="badge badge-danger">Absent</span></td>
                  </tr>
                </tbody>
              </table>
            </div>

            <div class="table-footer">
              <span class="text-muted">Showing 1-5 of 120 records</span>
              <div class="pagination">
                <div class="page-item">Previous</div>
                <div class="page-item active">1</div>
                <div class="page-item">2</div>
                <div class="page-item">3</div>
                <div class="page-item">Next</div>
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
