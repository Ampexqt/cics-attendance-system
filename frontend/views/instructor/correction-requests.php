<?php
require_once __DIR__ . '/../../../auth_check.php';
// require_role('instructor');
$activePage = 'correction-requests';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Correction Requests - CICS Attendance System</title>
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
        <h2 style="font-size: var(--font-size-2xl); font-weight: var(--font-weight-semibold); color: #1A2B47; margin-bottom: var(--spacing-lg);">Correction Requests</h2>

        <!-- Correction Requests List -->
        <div style="display: flex; flex-direction: column; gap: var(--spacing-md);">
          <!-- Request Card 1 -->
          <div class="card">
            <div class="card-body">
              <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: var(--spacing-md);">
                <div style="flex: 1;">
                  <h3 style="font-size: var(--font-size-lg); font-weight: var(--font-weight-semibold); color: #1A2B47; margin-bottom: var(--spacing-xs);">Alice Johnson</h3>
                  <p style="font-size: var(--font-size-sm); color: #6b7280; margin-bottom: var(--spacing-xs);">Data Structures</p>
                  <p style="font-size: var(--font-size-sm); color: #6b7280;">Late arrival not recorded</p>
                </div>
                <span style="font-size: var(--font-size-sm); color: #6b7280;">2024-01-15</span>
              </div>
              <div class="action-buttons">
                <button class="btn btn-success btn-sm" style="display: flex; align-items: center; gap: var(--spacing-xs);">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 1rem; height: 1rem;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                  </svg>
                  Approve
                </button>
                <button class="btn btn-danger btn-sm" style="display: flex; align-items: center; gap: var(--spacing-xs);">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 1rem; height: 1rem;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                  Reject
                </button>
                <button class="btn btn-outline btn-sm" style="display: flex; align-items: center; gap: var(--spacing-xs);">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 1rem; height: 1rem;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />
                  </svg>
                  Comment
                </button>
              </div>
            </div>
          </div>

          <!-- Request Card 2 -->
          <div class="card">
            <div class="card-body">
              <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: var(--spacing-md);">
                <div style="flex: 1;">
                  <h3 style="font-size: var(--font-size-lg); font-weight: var(--font-weight-semibold); color: #1A2B47; margin-bottom: var(--spacing-xs);">Bob Smith</h3>
                  <p style="font-size: var(--font-size-sm); color: #6b7280; margin-bottom: var(--spacing-xs);">Web Development</p>
                  <p style="font-size: var(--font-size-sm); color: #6b7280;">Marked absent by mistake</p>
                </div>
                <span style="font-size: var(--font-size-sm); color: #6b7280;">2024-01-14</span>
              </div>
              <div class="action-buttons">
                <button class="btn btn-success btn-sm" style="display: flex; align-items: center; gap: var(--spacing-xs);">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 1rem; height: 1rem;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                  </svg>
                  Approve
                </button>
                <button class="btn btn-danger btn-sm" style="display: flex; align-items: center; gap: var(--spacing-xs);">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 1rem; height: 1rem;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                  Reject
                </button>
                <button class="btn btn-outline btn-sm" style="display: flex; align-items: center; gap: var(--spacing-xs);">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 1rem; height: 1rem;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />
                  </svg>
                  Comment
                </button>
              </div>
            </div>
          </div>

          <!-- Request Card 3 -->
          <div class="card">
            <div class="card-body">
              <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: var(--spacing-md);">
                <div style="flex: 1;">
                  <h3 style="font-size: var(--font-size-lg); font-weight: var(--font-weight-semibold); color: #1A2B47; margin-bottom: var(--spacing-xs);">Carol White</h3>
                  <p style="font-size: var(--font-size-sm); color: #6b7280; margin-bottom: var(--spacing-xs);">Database Systems</p>
                  <p style="font-size: var(--font-size-sm); color: #6b7280;">Medical emergency</p>
                </div>
                <span style="font-size: var(--font-size-sm); color: #6b7280;">2024-01-13</span>
              </div>
              <div class="action-buttons">
                <button class="btn btn-success btn-sm" style="display: flex; align-items: center; gap: var(--spacing-xs);">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 1rem; height: 1rem;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                  </svg>
                  Approve
                </button>
                <button class="btn btn-danger btn-sm" style="display: flex; align-items: center; gap: var(--spacing-xs);">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 1rem; height: 1rem;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                  Reject
                </button>
                <button class="btn btn-outline btn-sm" style="display: flex; align-items: center; gap: var(--spacing-xs);">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 1rem; height: 1rem;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />
                  </svg>
                  Comment
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

