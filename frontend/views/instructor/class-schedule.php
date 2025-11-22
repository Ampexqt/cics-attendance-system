<?php
require_once __DIR__ . '/../../../auth_check.php';
// require_role('instructor');
$activePage = 'class-schedule';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Class Schedule - CICS Attendance System</title>
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
        <h2 style="font-size: var(--font-size-2xl); font-weight: var(--font-weight-semibold); color: #1A2B47; margin-bottom: var(--spacing-lg);">Teaching Schedule</h2>

        <!-- Schedule Grid -->
        <div class="card">
          <div class="card-body">
            <div class="schedule-grid">
              <!-- Monday -->
              <div class="schedule-day">
                <div class="schedule-day-header">Monday</div>
                <div class="schedule-item">
                  <div class="schedule-item-time">08:00 AM</div>
                  <div class="schedule-item-subject">Data Structures</div>
                  <div class="schedule-item-section" style="display: flex; align-items: center; gap: 0.25rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 0.75rem; height: 0.75rem; color: #6b7280;">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                      <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 6.627-5.373 12-12 12s-12-5.373-12-12 5.373-12 12-12 12 5.373 12 12z" />
                    </svg>
                    CS-3A | Room 301
                  </div>
                </div>
                <div class="schedule-item">
                  <div class="schedule-item-time">01:00 PM</div>
                  <div class="schedule-item-subject">Database Systems</div>
                  <div class="schedule-item-section" style="display: flex; align-items: center; gap: 0.25rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 0.75rem; height: 0.75rem; color: #6b7280;">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                      <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 6.627-5.373 12-12 12s-12-5.373-12-12 5.373-12 12-12 12 5.373 12 12z" />
                    </svg>
                    CS-3B | Room 305
                  </div>
                </div>
              </div>

              <!-- Tuesday -->
              <div class="schedule-day">
                <div class="schedule-day-header">Tuesday</div>
                <div class="schedule-item">
                  <div class="schedule-item-time">10:00 AM</div>
                  <div class="schedule-item-subject">Web Development</div>
                  <div class="schedule-item-section" style="display: flex; align-items: center; gap: 0.25rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 0.75rem; height: 0.75rem; color: #6b7280;">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                      <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 6.627-5.373 12-12 12s-12-5.373-12-12 5.373-12 12-12 12 5.373 12 12z" />
                    </svg>
                    IT-2B | Lab 204
                  </div>
                </div>
              </div>

              <!-- Wednesday -->
              <div class="schedule-day">
                <div class="schedule-day-header">Wednesday</div>
                <div class="schedule-item">
                  <div class="schedule-item-time">08:00 AM</div>
                  <div class="schedule-item-subject">Data Structures</div>
                  <div class="schedule-item-section" style="display: flex; align-items: center; gap: 0.25rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 0.75rem; height: 0.75rem; color: #6b7280;">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                      <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 6.627-5.373 12-12 12s-12-5.373-12-12 5.373-12 12-12 12 5.373 12 12z" />
                    </svg>
                    CS-3A | Room 301
                  </div>
                </div>
                <div class="schedule-item">
                  <div class="schedule-item-time">03:00 PM</div>
                  <div class="schedule-item-subject">Web Development</div>
                  <div class="schedule-item-section" style="display: flex; align-items: center; gap: 0.25rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 0.75rem; height: 0.75rem; color: #6b7280;">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                      <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 6.627-5.373 12-12 12s-12-5.373-12-12 5.373-12 12-12 12 5.373 12 12z" />
                    </svg>
                    IT-2B | Lab 204
                  </div>
                </div>
              </div>

              <!-- Thursday -->
              <div class="schedule-day">
                <div class="schedule-day-header">Thursday</div>
                <div class="schedule-item">
                  <div class="schedule-item-time">01:00 PM</div>
                  <div class="schedule-item-subject">Database Systems</div>
                  <div class="schedule-item-section" style="display: flex; align-items: center; gap: 0.25rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 0.75rem; height: 0.75rem; color: #6b7280;">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                      <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 6.627-5.373 12-12 12s-12-5.373-12-12 5.373-12 12-12 12 5.373 12 12z" />
                    </svg>
                    CS-3B | Room 305
                  </div>
                </div>
              </div>

              <!-- Friday -->
              <div class="schedule-day">
                <div class="schedule-day-header">Friday</div>
                <div class="schedule-item">
                  <div class="schedule-item-time">10:00 AM</div>
                  <div class="schedule-item-subject">Data Structures</div>
                  <div class="schedule-item-section" style="display: flex; align-items: center; gap: 0.25rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 0.75rem; height: 0.75rem; color: #6b7280;">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                      <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 6.627-5.373 12-12 12s-12-5.373-12-12 5.373-12 12-12 12 5.373 12 12z" />
                    </svg>
                    CS-3A | Room 301
                  </div>
                </div>
              </div>

              <!-- Saturday -->
              <div class="schedule-day">
                <div class="schedule-day-header">Saturday</div>
              </div>

              <!-- Sunday -->
              <div class="schedule-day">
                <div class="schedule-day-header">Sunday</div>
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

