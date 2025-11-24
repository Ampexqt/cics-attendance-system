<?php
require_once __DIR__ . '/../../../auth_check.php';
// require_role('instructor');
$activePage = 'correction-requests';

// Get the current user's ID from the session
$userId = $_SESSION['user_id'] ?? null;

// Initialize models
require_once __DIR__ . '/../../../backend/models/Instructor.php';
require_once __DIR__ . '/../../../backend/models/Attendance.php';

$instructorModel = new Instructor();
$attendanceModel = new Attendance();

// Get instructor details and correction requests
$instructor = $instructorModel->findByUserId($userId);
$correctionRequests = $instructor ? $attendanceModel->getInstructorCorrectionRequests($instructor['id']) : [];

// Function to format date
function formatDate($dateString) {
    $date = new DateTime($dateString);
    return $date->format('Y-m-d');
}

// Function to format time
function formatTime($dateTimeString) {
    $date = new DateTime($dateTimeString);
    return $date->format('h:i A');
}
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
          <?php if (empty($correctionRequests)): ?>
            <div class="card">
              <div class="card-body text-center py-4">
                <p style="color: #6b7280; margin: 0;">No pending correction requests found.</p>
              </div>
            </div>
          <?php else: ?>
            <?php foreach ($correctionRequests as $request): 
              $studentName = htmlspecialchars($request['first_name'] . ' ' . $request['last_name']);
              $subjectInfo = htmlspecialchars($request['subject_name'] . ' (' . $request['subject_code'] . ')');
              $requestDate = formatDate($request['created_at']);
              $sessionDate = formatDate($request['session_date']);
              $currentStatus = ucfirst($request['current_status']);
              $requestedStatus = ucfirst($request['requested_status']);
            ?>
              <div class="card">
                <div class="card-body">
                  <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: var(--spacing-md);">
                    <div style="flex: 1;">
                      <h3 style="font-size: var(--font-size-lg); font-weight: var(--font-weight-semibold); color: #1A2B47; margin-bottom: var(--spacing-xs);">
                        <?php echo $studentName; ?>
                        <span style="font-size: var(--font-size-sm); color: #6b7280; font-weight: normal;">(ID: <?php echo htmlspecialchars($request['student_number']); ?>)</span>
                      </h3>
                      <p style="font-size: var(--font-size-sm); color: #6b7280; margin-bottom: var(--spacing-xs);">
                        <?php echo $subjectInfo; ?><br>
                        Session: <?php echo $sessionDate; ?>
                      </p>
                      <div style="background-color: #f9fafb; border-radius: 0.375rem; padding: 0.5rem; margin: 0.5rem 0;">
                        <p style="font-size: var(--font-size-sm); color: #6b7280; margin: 0 0 0.25rem 0;">
                          <strong>Current Status:</strong> <span class="status-badge <?php echo strtolower($currentStatus); ?>"><?php echo $currentStatus; ?></span>
                        </p>
                        <p style="font-size: var(--font-size-sm); color: #6b7280; margin: 0 0 0.25rem 0;">
                          <strong>Requested Status:</strong> <span class="status-badge <?php echo strtolower($requestedStatus); ?>"><?php echo $requestedStatus; ?></span>
                        </p>
                        <p style="font-size: var(--font-size-sm); color: #6b7280; margin: 0.5rem 0 0 0; font-style: italic;">
                          <strong>Reason:</strong> <?php echo nl2br(htmlspecialchars($request['reason'])); ?>
                        </p>
                      </div>
                    </div>
                    <span style="font-size: var(--font-size-sm); color: #6b7280;"><?php echo $requestDate; ?></span>
                  </div>
                  <div class="action-buttons">
                    <button class="btn btn-success btn-sm approve-request" data-request-id="<?php echo $request['id']; ?>" style="display: flex; align-items: center; gap: var(--spacing-xs);">
                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 1rem; height: 1rem;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                      </svg>
                      Approve
                    </button>
                    <button class="btn btn-danger btn-sm reject-request" data-request-id="<?php echo $request['id']; ?>" style="display: flex; align-items: center; gap: var(--spacing-xs);">
                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 1rem; height: 1rem;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                      </svg>
                      Reject
                    </button>
                    <button class="btn btn-outline btn-sm add-comment" data-request-id="<?php echo $request['id']; ?>" style="display: flex; align-items: center; gap: var(--spacing-xs);">
                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 1rem; height: 1rem;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />
                      </svg>
                      Comment
                    </button>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
        </div>
      </div>
    </main>
  </div>

  <?php include 'includes/scripts.php'; ?>
</body>

</html>

