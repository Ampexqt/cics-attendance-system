<?php
require_once __DIR__ . '/../../../auth_check.php';
// require_role('instructor');
$activePage = 'active-sessions';

// Get the current user's ID from the session
$userId = $_SESSION['user_id'] ?? null;

// Initialize models
require_once __DIR__ . '/../../../backend/models/Instructor.php';
require_once __DIR__ . '/../../../backend/models/Attendance.php';

$instructorModel = new Instructor();
$attendanceModel = new Attendance();

// Get instructor details and active sessions
$instructor = $instructorModel->findByUserId($userId);
$activeSessions = $instructor ? $attendanceModel->getActiveSessions($instructor['id']) : [];
$assignedSubjects = $instructor ? $instructorModel->getAssignedSubjects($instructor['id']) : [];
$activeSessionsBySubject = [];

foreach ($activeSessions as $session) {
  $activeSessionsBySubject[$session['subject_id']] = $session;
}

function formatYearLevelLabel($yearLevel)
{
  if (!$yearLevel) {
    return null;
  }

  $suffix = 'th';
  if (in_array($yearLevel % 100, [11, 12, 13])) {
    $suffix = 'th';
  } else {
    switch ($yearLevel % 10) {
      case 1:
        $suffix = 'st';
        break;
      case 2:
        $suffix = 'nd';
        break;
      case 3:
        $suffix = 'rd';
        break;
    }
  }

  return "{$yearLevel}{$suffix} Year";
}

function formatDurationLabel(?string $timeIn)
{
  if (!$timeIn) {
    return '—';
  }

  $timeInObj = new DateTime($timeIn);
  $now = new DateTime();
  $interval = $timeInObj->diff($now);

  $minutes = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;
  if ($minutes <= 0) {
    return 'Just now';
  }

  return "{$minutes} mins";
}

function getStatusBadgeClass($status)
{
  switch (strtolower($status)) {
    case 'present':
      return 'status-pill present';
    case 'late':
      return 'status-pill late';
    case 'absent':
      return 'status-pill absent';
    default:
      return 'status-pill';
  }
}
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

        <?php if (empty($assignedSubjects)): ?>
          <div class="empty-state">
            <p>No subjects assigned yet.</p>
            <span>Contact the administrator to have subjects assigned to your account.</span>
          </div>
        <?php else: ?>
          <div class="active-session-list">
            <?php foreach ($assignedSubjects as $subject):
              $session = $activeSessionsBySubject[$subject['id']] ?? null;
              $students = $session ? $attendanceModel->getSessionStudents($session['id']) : [];
              $metaParts = array_filter([
                $subject['section'] ? 'Section ' . $subject['section'] : null,
                $subject['year_level'] ? formatYearLevelLabel((int) $subject['year_level']) : null,
                $subject['program'] ?? null
              ]);
              $scheduleLabel = !empty($subject['schedule']) ? $subject['schedule'] : 'Schedule not set';
              $timeRange = $scheduleLabel;

              if ($session && !empty($session['start_time'])) {
                $startTime = DateTime::createFromFormat('H:i:s', $session['start_time']);
                if ($startTime) {
                  $endTime = !empty($session['end_time'])
                    ? DateTime::createFromFormat('H:i:s', $session['end_time'])
                    : (clone $startTime)->modify('+2 hours');
                  $timeRange = $startTime->format('h:i A') . ' - ' . $endTime->format('h:i A');
                }
              }
            ?>
              <section class="active-session-card<?php echo $session ? ' is-live' : ''; ?>">
                <header class="active-session-header">
                  <div>
                    <p class="active-session-eyebrow">
                      <?php echo $session ? 'Active Session – ' : ''; ?>
                      <?php echo htmlspecialchars($subject['name']); ?>
                    </p>
                    <?php if (!empty($metaParts)): ?>
                      <div class="active-session-meta">
                        <?php foreach ($metaParts as $meta): ?>
                          <span><?php echo htmlspecialchars($meta); ?></span>
                        <?php endforeach; ?>
                      </div>
                    <?php endif; ?>
                  </div>
                  <div class="active-session-header-actions">
                    <div class="session-status-badge <?php echo $session ? 'active' : 'idle'; ?>">
                      <span class="status-dot"></span>
                      <?php echo $session ? 'Session Active' : 'Not Active'; ?>
                    </div>
                    <div class="active-session-instructor">
                      <span>Instructor</span>
                      <p><?php echo htmlspecialchars($instructor['first_name'] . ' ' . $instructor['last_name']); ?></p>
                    </div>
                    <?php if ($session): ?>
                      <button class="btn btn-danger btn-sm end-session-btn" data-session-id="<?php echo $session['id']; ?>" data-loading-text="Ending...">
                        End Session
                      </button>
                    <?php else: ?>
                      <button class="btn btn-primary btn-sm start-session-btn" data-subject-id="<?php echo $subject['id']; ?>" data-loading-text="Starting...">
                        Start Session
                      </button>
                    <?php endif; ?>
                  </div>
                </header>

                <div class="active-session-summary">
                  <div>
                    <span>Subject Code</span>
                    <p><?php echo htmlspecialchars($subject['code']); ?></p>
                  </div>
                  <div>
                    <span><?php echo $session ? 'Session Window' : 'Scheduled Time'; ?></span>
                    <p><?php echo htmlspecialchars($timeRange); ?></p>
                  </div>
                  <div>
                    <span>Room</span>
                    <p><?php echo htmlspecialchars($subject['room'] ?? '—'); ?></p>
                  </div>
                  <div>
                    <span>Students Logged</span>
                    <p><?php echo $session ? count($students) : 0; ?></p>
                  </div>
                </div>

                <div class="active-session-table">
                  <table>
                    <thead>
                      <tr>
                        <th>Student Name</th>
                        <th>Student ID</th>
                        <th>Program</th>
                        <th>Year Level</th>
                        <th>Section</th>
                        <th>Time-In</th>
                        <th>Time Duration</th>
                        <th>Status</th>
                        <th>Notes</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (!$session): ?>
                        <tr>
                          <td colspan="9" class="text-center py-4">Start the session to begin tracking attendance for this subject.</td>
                        </tr>
                      <?php elseif (empty($students)): ?>
                        <tr>
                          <td colspan="9" class="text-center py-4">No attendance logs yet for this session.</td>
                        </tr>
                      <?php else: ?>
                        <?php foreach ($students as $student):
                          $fullName = trim($student['first_name'] . ' ' . $student['last_name']);
                          $yearLabel = formatYearLevelLabel((int) $student['year_level']);
                          $timeInLabel = $student['time_in'] ? (new DateTime($student['time_in']))->format('h:i A') : '—';
                        ?>
                          <tr>
                            <td><?php echo htmlspecialchars($fullName); ?></td>
                            <td><?php echo htmlspecialchars($student['student_number']); ?></td>
                            <td><?php echo htmlspecialchars($student['program']); ?></td>
                            <td><?php echo htmlspecialchars($yearLabel ?? '—'); ?></td>
                            <td><?php echo htmlspecialchars($student['section']); ?></td>
                            <td><?php echo htmlspecialchars($timeInLabel); ?></td>
                            <td><?php echo htmlspecialchars(formatDurationLabel($student['time_in'])); ?></td>
                            <td>
                              <span class="<?php echo getStatusBadgeClass($student['status']); ?>">
                                <?php echo ucfirst($student['status']); ?>
                              </span>
                            </td>
                            <td><?php echo htmlspecialchars($student['notes'] ?? '—'); ?></td>
                          </tr>
                        <?php endforeach; ?>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>
              </section>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </main>
  </div>

  <?php include 'includes/scripts.php'; ?>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const performSessionAction = async (button, endpoint, payload, successMessage) => {
        const originalText = button.textContent.trim();
        const loadingText = button.dataset.loadingText || 'Please wait...';
        button.disabled = true;
        button.textContent = loadingText;

        try {
          const response = await fetch(`${API_BASE}/attendance/${endpoint}`, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            credentials: 'include',
            body: JSON.stringify(payload)
          });

          const result = await response.json();

          if (!response.ok || !result.success) {
            throw new Error(result.message || 'Something went wrong');
          }

          Toast.success(successMessage);
          setTimeout(() => window.location.reload(), 600);
        } catch (error) {
          Toast.error(error.message || 'Unable to complete the action');
          button.disabled = false;
          button.textContent = originalText;
        }
      };

      document.querySelectorAll('.start-session-btn').forEach((button) => {
        button.addEventListener('click', () => {
          const subjectId = button.dataset.subjectId;
          if (!subjectId) return;
          performSessionAction(button, 'start-session', { subject_id: subjectId }, 'Attendance session started.');
        });
      });

      document.querySelectorAll('.end-session-btn').forEach((button) => {
        button.addEventListener('click', () => {
          if (!confirm('End this attendance session?')) {
            return;
          }
          const sessionId = button.dataset.sessionId;
          if (!sessionId) return;
          performSessionAction(button, 'end-session', { session_id: sessionId }, 'Attendance session ended.');
        });
      });
    });
  </script>
</body>

</html>

