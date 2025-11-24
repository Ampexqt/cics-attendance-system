<?php
require_once __DIR__ . '/../../../auth_check.php';
// require_role('instructor');
$activePage = 'class-schedule';

// Get the current user's ID from the session
$userId = $_SESSION['user_id'] ?? null;

// Initialize instructor model
require_once __DIR__ . '/../../../backend/models/Instructor.php';
$instructorModel = new Instructor();

// Get instructor details and weekly schedule
$instructor = $instructorModel->findByUserId($userId);
$weeklySchedule = $instructor ? $instructorModel->getWeeklySchedule($instructor['id']) : [];
$days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
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
              <?php foreach ($days as $day): 
                $hasClasses = !empty($weeklySchedule[$day]);
              ?>
                <div class="schedule-day">
                  <div class="schedule-day-header"><?php echo $day; ?></div>
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

