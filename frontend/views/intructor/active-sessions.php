<?php
require_once __DIR__ . '/../../../auth_check.php';
// require_role('instructor');
$activePage = 'active-sessions';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Active Sessions - CICS Attendance System</title>
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
        <h2 style="font-size: var(--font-size-2xl); font-weight: var(--font-weight-semibold); color: #1A2B47; margin-bottom: var(--spacing-lg);">Active Sessions</h2>

        <!-- Active Attendance Sessions Table -->
        <div class="card">
          <div class="card-body">
            <table class="table">
              <thead>
                <tr>
                  <th>SUBJECT</th>
                  <th>SECTION</th>
                  <th>TIME</th>
                  <th>ROOM</th>
                  <th>STATUS</th>
                  <th>ACTION</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Data Structures</td>
                  <td>CS-3A</td>
                  <td>08:00 AM - 10:00 AM</td>
                  <td>Room 301</td>
                  <td><span class="status-badge active">Active</span></td>
                  <td><button class="btn btn-primary btn-sm">End Session</button></td>
                </tr>
                <tr>
                  <td>Web Development</td>
                  <td>IT-2B</td>
                  <td>10:00 AM - 12:00 PM</td>
                  <td>Lab 204</td>
                  <td><span class="status-badge active">Active</span></td>
                  <td><button class="btn btn-primary btn-sm">End Session</button></td>
                </tr>
                <tr>
                  <td>Database Systems</td>
                  <td>CS-3B</td>
                  <td>01:00 PM - 03:00 PM</td>
                  <td>Room 305</td>
                  <td><span class="status-badge active">Active</span></td>
                  <td><button class="btn btn-primary btn-sm">End Session</button></td>
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

