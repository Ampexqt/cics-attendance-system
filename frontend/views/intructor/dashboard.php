<?php
require_once __DIR__ . '/../../../auth_check.php';
// require_role('instructor');
$activePage = 'dashboard';

// Get the current user's ID from the session
$userId = $_SESSION['user_id'] ?? null;

// Initialize instructor model
require_once __DIR__ . '/../../../backend/models/Instructor.php';
$instructorModel = new Instructor();
// Attendance model (for correction requests and sessions)
require_once __DIR__ . '/../../../backend/models/Attendance.php';
$attendanceModel = new Attendance();

// Get instructor details
$instructor = $instructorModel->findByUserId($userId);
$assignedSubjects = [];

if ($instructor) {
    // Get assigned subjects for the instructor
    $assignedSubjects = $instructorModel->getAssignedSubjects($instructor['id']);
}

// Count the number of assigned subjects
$assignedSubjectsCount = count($assignedSubjects);
// Prepare weekly schedule and counts for dashboard stats
$weeklySchedule = $instructor ? $instructorModel->getWeeklySchedule($instructor['id']) : [];

// If the model returned an empty schedule (parsing may fail for some schedule formats),
// build a safe fallback by scanning the assigned subjects' schedule strings for day names.
$daysList = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
$hasAny = false;
foreach ($daysList as $d) { if (!empty($weeklySchedule[$d])) { $hasAny = true; break; } }
if (!$hasAny && !empty($assignedSubjects)) {
  // initialize empty structure
  $weeklySchedule = array_fill_keys($daysList, []);

  foreach ($assignedSubjects as $subject) {
    $schedStr = trim($subject['schedule'] ?? '');
    if ($schedStr === '') continue;

    // Split into segments by semicolon (common separator). This ensures a Saturday segment
    // that appears after a semicolon won't be erroneously attached to the previous day.
    $segments = preg_split('/\s*;\s*/', $schedStr);

    foreach ($segments as $segment) {
      $segment = trim($segment);
      if ($segment === '') continue;

      // Attribute this segment only to the exact day it mentions
      foreach ($daysList as $day) {
        if (stripos($segment, $day) !== false) {
          $weeklySchedule[$day][] = [
            'subject_code' => $subject['code'] ?? '',
            'subject_name' => $subject['name'] ?? '',
            'section' => $subject['section'] ?? '',
            'time' => $segment,
            'start_time' => date('H:i', strtotime($segment)) ?: '00:00',
            'end_time' => date('H:i', strtotime($segment)) ?: '00:00',
            'room' => $subject['room'] ?? 'TBA'
          ];
        }
      }
    }
  }
}

// Sections handling: count unique non-empty sections from assigned subjects
$sectionsHandlingCount = 0;
if (!empty($assignedSubjects)) {
  $sections = array_filter(array_map('trim', array_column($assignedSubjects, 'section')));
  $sectionsHandlingCount = count(array_unique($sections));
}

// Today's classes count
$todayName = date('l'); // e.g., Monday
$todaysClassesCount = isset($weeklySchedule[$todayName]) ? count($weeklySchedule[$todayName]) : 0;

  // Pending correction requests for this instructor
  $pendingCorrectionsCount = 0;
  if ($instructor) {
    $corrections = $attendanceModel->getInstructorCorrectionRequests($instructor['id']);
    $pendingCorrectionsCount = is_array($corrections) ? count($corrections) : 0;
  }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Instructor Dashboard - CICS Attendance System</title>
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
        <!-- Dashboard Title -->
        <h2 style="font-size: var(--font-size-2xl); font-weight: var(--font-weight-semibold); color: #1A2B47; margin-bottom: var(--spacing-lg);">Instructor Dashboard</h2>

        <!-- Dashboard Statistics Cards -->
        <div class="dashboard-grid">
          <div class="stat-card">
            <div class="stat-card-header">
              <h3 class="stat-card-title">Pending Corrections</h3>
              <div class="stat-card-icon warning">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
              </div>
            </div>
            <div class="stat-card-value"><?php echo $pendingCorrectionsCount; ?></div>
          </div>
          <div class="stat-card">
            <div class="stat-card-header">
              <h3 class="stat-card-title">Subjects Assigned</h3>
              <div class="stat-card-icon primary">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
              </div>
            </div>
            <div class="stat-card-value"><?php echo $assignedSubjectsCount; ?></div>
          </div>

          <div class="stat-card">
            <div class="stat-card-header">
              <h3 class="stat-card-title">Sections Handling</h3>
              <div class="stat-card-icon primary">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                </svg>
              </div>
            </div>
            <div class="stat-card-value"><?php echo $sectionsHandlingCount; ?></div>
          </div>

          <div class="stat-card">
            <div class="stat-card-header">
              <h3 class="stat-card-title">Today's Classes</h3>
              <div class="stat-card-icon primary">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
            </div>
            <div class="stat-card-value"><?php echo $todaysClassesCount; ?></div>
          </div>
        </div>

        <!-- Weekly Schedule Section -->
        <div class="dashboard-section">
          <div class="dashboard-section-header">
            <h2 class="dashboard-section-title">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-16.5 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-16.5 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
              </svg>
              Weekly Schedule
            </h2>
          </div>
          <div class="card">
            <div class="card-body">
              <div class="schedule-grid">
                <?php
                // Use the prepared weekly schedule (from model or fallback)
                $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

                foreach ($days as $day): 
                  $hasClasses = !empty($weeklySchedule[$day]);
                ?>
                  <div class="schedule-day">
                    <div class="schedule-day-header"><?php echo $day; ?></div>
                    <div class="schedule-day-body">
                    <?php if ($hasClasses): ?>
                      <?php
                        $items = $weeklySchedule[$day];
                        $visibleItems = array_slice($items, 0, 3);
                        $hiddenCount = max(0, count($items) - count($visibleItems));
                      ?>
                      <?php foreach ($visibleItems as $class): ?>
                        <div class="schedule-item">
                          <div class="schedule-item-time"><?php echo htmlspecialchars($class['time']); ?></div>
                          <div class="schedule-item-subject"><?php echo htmlspecialchars($class['subject_name']); ?></div>
                          <div class="schedule-item-section">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 0.75rem; height: 0.75rem; color: #6b7280;">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                              <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 6.627-5.373 12-12 12s-12-5.373-12-12 5.373-12 12-12 12 5.373 12 12z" />
                            </svg>
                            <?php echo htmlspecialchars($class['section'] . ' | ' . $class['room']); ?>
                          </div>
                        </div>
                      <?php endforeach; ?>
                      <?php if ($hiddenCount > 0): ?>
                        <div class="schedule-more">+<?php echo $hiddenCount; ?> more</div>
                      <?php endif; ?>
                    <?php else: ?>
                      <div class="no-classes">No classes scheduled</div>
                    <?php endif; ?>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
        </div>

        <!-- Assigned Subjects Section -->
        <div class="dashboard-section">
          <div class="dashboard-section-header">
            <h2 class="dashboard-section-title">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
              </svg>
              My Assigned Subjects
            </h2>
          </div>
          
          <?php if (!empty($assignedSubjects)): ?>
            <div class="subject-cards">
              <?php foreach ($assignedSubjects as $subject): ?>
                <div class="subject-card">
                  <div class="subject-card-header">
                    <h3 class="subject-code"><?php echo htmlspecialchars($subject['code']); ?></h3>
                    <span class="subject-section"><?php echo htmlspecialchars($subject['section']); ?></span>
                  </div>
                  <h4 class="subject-name"><?php echo htmlspecialchars($subject['name']); ?></h4>
                  <div class="subject-details">
                    <span class="subject-program"><?php echo htmlspecialchars($subject['program']); ?> - Year <?php echo $subject['year_level']; ?></span>
                    <?php if (!empty($subject['schedule'])): ?>
                      <div class="subject-schedule">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span><?php echo htmlspecialchars($subject['schedule']); ?></span>
                      </div>
                    <?php endif; ?>
                    <?php if (!empty($subject['room'])): ?>
                      <div class="subject-room">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                        </svg>
                        <span><?php echo htmlspecialchars($subject['room']); ?></span>
                      </div>
                    <?php endif; ?>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <div class="no-subjects">
              <p>No subjects assigned yet.</p>
            </div>
          <?php endif; ?>
        </div>

        <!-- Active Attendance Sessions removed -->

        <!-- Attendance Logs and Correction Requests removed -->
      </div>
    </main>
  </div>

  <?php include 'includes/scripts.php'; ?>
</body>

</html>

