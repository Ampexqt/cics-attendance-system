<?php
require_once __DIR__ . '/../../../auth_check.php';
require_role('admin');
$activePage = 'campus';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Campus Restriction - CICS Attendance System</title>
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
            <h1 class="page-title">Campus Restrictions</h1>
            <p class="page-subtitle">Visualize the campus map and GPS constraints</p>
          </div>
        </div>

        <div class="card">
          <div class="card-body">
            <div class="placeholder-panel map-placeholder">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 6.627-5.373 12-12 12s-12-5.373-12-12 5.373-12 12-12 12 5.373 12 12z" />
              </svg>
              <h3 class="card-title">Campus Map</h3>
              <p class="text-muted">Interactive map visualization will be displayed here.</p>
            </div>
          </div>
        </div>

        <div class="dashboard-section-header">
          <h2 class="dashboard-section-title">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 6.627-5.373 12-12 12s-12-5.373-12-12 5.373-12 12-12 12 5.373 12 12z" />
            </svg>
            GPS Location Settings
          </h2>
        </div>

        <div class="card">
          <div class="card-body">
            <div class="form-grid">
              <div class="form-field">
                <label>Center Latitude</label>
                <input type="text" class="form-control" placeholder="e.g. 7.1117">
              </div>
              <div class="form-field">
                <label>Center Longitude</label>
                <input type="text" class="form-control" placeholder="e.g. 122.0735">
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
