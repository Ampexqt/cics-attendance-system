<?php
require_once __DIR__ . '/../auth_check.php';
require_role('admin');
$activePage = 'approve';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Approve Registration - CICS Attendance System</title>
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
        <div class="dashboard-section-header">
          <h2 class="dashboard-section-title">Approve Student Registration</h2>
        </div>

        <div class="card">
          <div class="card-body">
            <div class="dashboard-section-header">
              <h3 class="stat-card-title" style="font-size: var(--font-size-lg); color: var(--text-primary);">Pending Approvals</h3>
              <div style="width: 300px;">
                <input type="text" class="form-control" placeholder="Search students...">
              </div>
            </div>

            <div class="table-container">
              <table class="table">
                <thead>
                  <tr>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Program</th>
                    <th>Device Fingerprint</th>
                    <th>Registration Date</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>2023-0001</td>
                    <td>Juan Dela Cruz</td>
                    <td>BSCS</td>
                    <td style="font-family: monospace;">df7c3c7d...</td>
                    <td>2023-09-15</td>
                    <td>
                      <div style="display: flex; gap: 0.5rem;">
                        <button class="btn btn-sm btn-warning" style="background-color: #fcd34d; color: #92400e; border: none;" title="Approve">
                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 1.25rem; height: 1.25rem;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                          </svg>
                        </button>
                        <button class="btn btn-sm btn-secondary" style="background-color: #e5e7eb; color: #374151; border: none;" title="Reject">
                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 1.25rem; height: 1.25rem;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                          </svg>
                        </button>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>2023-0002</td>
                    <td>Maria Santos</td>
                    <td>BSIT</td>
                    <td style="font-family: monospace;">a1b2c3d4...</td>
                    <td>2023-09-15</td>
                    <td>
                      <div style="display: flex; gap: 0.5rem;">
                         <button class="btn btn-sm btn-warning" style="background-color: #fcd34d; color: #92400e; border: none;" title="Approve">
                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 1.25rem; height: 1.25rem;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                          </svg>
                        </button>
                        <button class="btn btn-sm btn-secondary" style="background-color: #e5e7eb; color: #374151; border: none;" title="Reject">
                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 1.25rem; height: 1.25rem;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                          </svg>
                        </button>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>2023-0003</td>
                    <td>Pedro Gonzales</td>
                    <td>BSIS</td>
                    <td style="font-family: monospace;">q7r8s9t0...</td>
                    <td>2023-09-16</td>
                    <td>
                      <div style="display: flex; gap: 0.5rem;">
                         <button class="btn btn-sm btn-warning" style="background-color: #fcd34d; color: #92400e; border: none;" title="Approve">
                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 1.25rem; height: 1.25rem;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                          </svg>
                        </button>
                        <button class="btn btn-sm btn-secondary" style="background-color: #e5e7eb; color: #374151; border: none;" title="Reject">
                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 1.25rem; height: 1.25rem;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                          </svg>
                        </button>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>2023-0004</td>
                    <td>Ana Reyes</td>
                    <td>BSCS</td>
                    <td style="font-family: monospace;">g3h4i5j6...</td>
                    <td>2023-09-16</td>
                    <td>
                      <div style="display: flex; gap: 0.5rem;">
                         <button class="btn btn-sm btn-warning" style="background-color: #fcd34d; color: #92400e; border: none;" title="Approve">
                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 1.25rem; height: 1.25rem;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                          </svg>
                        </button>
                        <button class="btn btn-sm btn-secondary" style="background-color: #e5e7eb; color: #374151; border: none;" title="Reject">
                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 1.25rem; height: 1.25rem;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                          </svg>
                        </button>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>2023-0005</td>
                    <td>Carlos Mendoza</td>
                    <td>BSIT</td>
                    <td style="font-family: monospace;">w9x0y1z2...</td>
                    <td>2023-09-17</td>
                    <td>
                      <div style="display: flex; gap: 0.5rem;">
                         <button class="btn btn-sm btn-warning" style="background-color: #fcd34d; color: #92400e; border: none;" title="Approve">
                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 1.25rem; height: 1.25rem;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                          </svg>
                        </button>
                        <button class="btn btn-sm btn-secondary" style="background-color: #e5e7eb; color: #374151; border: none;" title="Reject">
                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 1.25rem; height: 1.25rem;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                          </svg>
                        </button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>

  <?php include 'includes/scripts.php'; ?>
</body>
</html>
