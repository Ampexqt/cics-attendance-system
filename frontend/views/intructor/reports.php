<?php
require_once __DIR__ . '/../../../auth_check.php';
// require_role('instructor');
$activePage = 'reports';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reports - CICS Attendance System</title>
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
        <h2 style="font-size: var(--font-size-2xl); font-weight: var(--font-weight-semibold); color: #1A2B47; margin-bottom: var(--spacing-lg);">Reports</h2>

        <!-- Coming Soon Card -->
        <div class="card" style="max-width: 600px; margin: 0 auto;">
          <div class="card-body" style="text-align: center; padding: var(--spacing-2xl);">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#0A7FDB" style="width: 5rem; height: 5rem; margin: 0 auto var(--spacing-lg); opacity: 0.5;">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
            </svg>
            <p style="font-size: var(--font-size-lg); color: #6b7280; margin: 0;">Reports section coming soon</p>
          </div>
        </div>
      </div>
    </main>
  </div>

  <?php include 'includes/scripts.php'; ?>
</body>

</html>

