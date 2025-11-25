<?php
require_once __DIR__ . '/../../../auth_check.php';
require_role('instructor');
$activePage = 'attendance-logs';

// Get the current user's ID from the session
$userId = $_SESSION['user_id'] ?? null;

// Initialize models
require_once __DIR__ . '/../../../backend/models/Instructor.php';
require_once __DIR__ . '/../../../backend/models/Attendance.php';

$instructorModel = new Instructor();
$attendanceModel = new Attendance();

// Get instructor details
$instructor = $instructorModel->findByUserId($userId);
$assignedSubjects = [];
$sections = [];
$logs = [];

if ($instructor) {
  // Get assigned subjects for filter
  $assignedSubjects = $instructorModel->getAssignedSubjects($instructor['id']);

  // Extract unique sections from assigned subjects
  $sections = array_unique(array_column($assignedSubjects, 'section'));
  sort($sections);

  // Get filters from request
  $filters = [
    'instructor_id' => $instructor['id'],
    'subject_id' => $_GET['subject'] ?? '',
    'section' => $_GET['section'] ?? '',
    'start_date' => $_GET['date'] ?? '', // Using start_date for exact date match if end_date is same, or just >=
    'end_date' => $_GET['date'] ?? '',
    'search' => $_GET['search'] ?? ''
  ];

  // If date is empty, remove it from filters to show all history
  if (empty($filters['start_date'])) {
    unset($filters['start_date']);
    unset($filters['end_date']);
  }

  // Fetch attendance logs
  $logs = $attendanceModel->getRecords($filters);
} else {
  $error_message = "Instructor profile not found.";
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Attendance Logs - CICS Attendance System</title>
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
        <h2 style="font-size: var(--font-size-2xl); font-weight: var(--font-weight-semibold); color: #1A2B47; margin-bottom: var(--spacing-lg);">Attendance Logs</h2>

        <?php if (isset($error_message)): ?>
          <div style="background-color: #fee2e2; border: 1px solid #ef4444; color: #b91c1c; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem;">
            <?php echo htmlspecialchars($error_message); ?>
          </div>
        <?php endif; ?>

        <!-- Attendance Logs Card -->
        <div class="card">
          <div class="card-body">
            <!-- Filter Controls -->
            <form method="GET" action="" class="filter-controls">
              <div class="filter-group">
                <label class="filter-label">Subject</label>
                <select name="subject" class="form-select" style="width: 200px;" onchange="this.form.submit()">
                  <option value="">All Subjects</option>
                  <?php foreach ($assignedSubjects as $subject): ?>
                    <option value="<?php echo $subject['id']; ?>" <?php echo (isset($_GET['subject']) && $_GET['subject'] == $subject['id']) ? 'selected' : ''; ?>>
                      <?php echo htmlspecialchars($subject['code'] . ' - ' . $subject['name']); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="filter-group">
                <label class="filter-label">Section</label>
                <select name="section" class="form-select" style="width: 200px;" onchange="this.form.submit()">
                  <option value="">All Sections</option>
                  <?php foreach ($sections as $section): ?>
                    <option value="<?php echo htmlspecialchars($section); ?>" <?php echo (isset($_GET['section']) && $_GET['section'] == $section) ? 'selected' : ''; ?>>
                      <?php echo htmlspecialchars($section); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="filter-group">
                <label class="filter-label">Date</label>
                <input type="date" name="date" class="form-control" style="width: 200px;" value="<?php echo $_GET['date'] ?? ''; ?>" onchange="this.form.submit()">
              </div>
              <div class="filter-group" style="flex: 1; min-width: 250px;">
                <label class="filter-label">Search</label>
                <div class="search-container">
                  <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                  </svg>
                  <input type="text" name="search" class="search-input" placeholder="Search students..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                </div>
              </div>
            </form>

            <!-- Attendance Log Table -->
            <table class="table">
              <thead>
                <tr>
                  <th>STUDENT NAME</th>
                  <th>TIME-IN</th>
                  <th>STATUS</th>
                  <th>NOTES</th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($logs)): ?>
                  <tr>
                    <td colspan="4" style="text-align: center; padding: 2rem; color: #6b7280;">
                      No attendance records found.
                    </td>
                  </tr>
                <?php else: ?>
                  <?php foreach ($logs as $log): ?>
                    <tr>
                      <td>
                        <div style="font-weight: 500; color: #111827;">
                          <?php echo htmlspecialchars($log['first_name'] . ' ' . $log['last_name']); ?>
                        </div>
                        <div style="font-size: 0.75rem; color: #6b7280;">
                          <?php echo htmlspecialchars($log['subject_code'] . ' | ' . $log['section']); ?>
                        </div>
                      </td>
                      <td>
                        <div style="color: #111827;">
                          <?php echo date('h:i A', strtotime($log['time_in'])); ?>
                        </div>
                        <div style="font-size: 0.75rem; color: #6b7280;">
                          <?php echo date('M d, Y', strtotime($log['session_date'])); ?>
                        </div>
                      </td>
                      <td>
                        <?php
                        $statusClass = match (strtolower($log['status'])) {
                          'present' => 'present',
                          'late' => 'late',
                          'absent' => 'absent',
                          default => 'absent'
                        };
                        ?>
                        <span class="status-badge <?php echo $statusClass; ?>">
                          <?php echo ucfirst($log['status']); ?>
                        </span>
                      </td>
                      <td><?php echo htmlspecialchars($log['notes'] ?? '-'); ?></td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </main>
  </div>

  <?php include 'includes/scripts.php'; ?>
  <script>
    // Debounce search input
    const searchInput = document.querySelector('input[name="search"]');
    let timeoutId;

    searchInput.addEventListener('input', (e) => {
      clearTimeout(timeoutId);
      timeoutId = setTimeout(() => {
        e.target.form.submit();
      }, 500);
    });

    // Focus search input after reload if it has value
    if (searchInput.value) {
      searchInput.focus();
      // Move cursor to end
      const val = searchInput.value;
      searchInput.value = '';
      searchInput.value = val;
    }
  </script>
</body>

</html>