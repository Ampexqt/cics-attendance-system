<?php
require_once __DIR__ . '/../../../auth_check.php';
require_role('instructor');
$activePage = 'class-schedule';

// Get the current user's ID from the session
$userId = $_SESSION['user_id'] ?? null;

// Initialize instructor model
require_once __DIR__ . '/../../../backend/models/Instructor.php';
$instructorModel = new Instructor();

// Get instructor details and weekly schedule
$instructor = $instructorModel->findByUserId($userId);
$assignedSubjects = [];
$weeklySchedule = [];

if ($instructor) {
  $assignedSubjects = $instructorModel->getAssignedSubjects($instructor['id']);
  $weeklySchedule = $instructorModel->getWeeklySchedule($instructor['id']);
} else {
  // Handle case where user is logged in as instructor but has no instructor profile
  $error_message = "Instructor profile not found. Please contact the administrator.";
}

// Fallback logic for schedule parsing (copied from dashboard.php)
// If the model returned an empty schedule (parsing may fail for some schedule formats),
// build a safe fallback by scanning the assigned subjects' schedule strings for day names.
$daysList = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
$hasAny = false;
foreach ($daysList as $d) {
  if (!empty($weeklySchedule[$d])) {
    $hasAny = true;
    break;
  }
}

if (!$hasAny && !empty($assignedSubjects)) {
  // initialize empty structure
  $weeklySchedule = array_fill_keys($daysList, []);

  foreach ($assignedSubjects as $subject) {
    $schedStr = trim($subject['schedule'] ?? '');
    if ($schedStr === '') continue;

    // Split into segments by semicolon (common separator).
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

$days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
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
        <h2 style="font-size: var(--font-size-2xl); font-weight: var(--font-weight-semibold); color: #1A2B47; margin-bottom: var(--spacing-lg);">
          Teaching Schedule
        </h2>

        <?php if (isset($error_message)): ?>
          <div style="background-color: #fee2e2; border: 1px solid #ef4444; color: #b91c1c; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem;">
            <?php echo htmlspecialchars($error_message); ?>
          </div>
        <?php endif; ?>

        <!-- Schedule Grid -->
        <div class="card">
          <div class="card-body">
            <div class="schedule-grid">
              <?php foreach ($days as $day):
                $hasClasses = !empty($weeklySchedule[$day]);
              ?>
                <div class="schedule-day">
                  <div class="schedule-day-header"><?php echo $day; ?></div>
                  <div class="schedule-day-body">
                    <?php if ($hasClasses): ?>
                      <?php foreach ($weeklySchedule[$day] as $class): ?>
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
    </main>
  </div>

  <?php include 'includes/scripts.php'; ?>
</body>

</html>