<?php
require_once __DIR__ . '/../../../auth_check.php';
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
  <link rel="stylesheet" href="../../assets/css/components/toast.css">
  <link rel="stylesheet" href="../../assets/css/components/modals.css">
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
        <div class="page-heading">
          <div>
            <h1 class="page-title">Approve Student Registration</h1>
            <p class="page-subtitle">Review and process pending student requests</p>
          </div>
        </div>

        <div class="card">
          <div class="card-body">
            <div class="card-header">
              <h3 class="card-title">Pending Approvals</h3>
              <div class="search-container">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="search-icon">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
                <input type="text" id="searchInput" class="search-input" placeholder="Search students...">
              </div>
            </div>

            <div id="loadingState" class="placeholder-panel placeholder-panel--compact">
              <p>Loading pending registrations...</p>
            </div>

            <div id="emptyState" class="placeholder-panel placeholder-panel--compact" style="display: none;">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
              </svg>
              <h4>No Pending Registrations</h4>
              <p>All student registrations have been processed.</p>
            </div>

            <div class="table-container" id="tableContainer" style="display: none;">
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
                <tbody id="pendingStudentsTable">
                  <!-- Dynamic content will be loaded here -->
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>

  <!-- Approve Confirmation Modal -->
  <div class="modal-backdrop" id="approveModalBackdrop">
    <div class="modal modal-sm">
      <div class="modal-header">
        <h3 class="modal-title">Approve Registration</h3>
        <button class="modal-close" onclick="closeApproveModal()">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to approve this student's registration?</p>
        <p class="helper-text">
          The student will be able to log in after approval.
        </p>
      </div>
      <div class="modal-footer">
        <button class="btn btn-outline btn-sm" onclick="closeApproveModal()">Cancel</button>
        <button class="btn btn-warning btn-sm" onclick="confirmApprove()">
          Approve
        </button>
      </div>
    </div>
  </div>

  <!-- Reject Confirmation Modal -->
  <div class="modal-backdrop" id="rejectModalBackdrop">
    <div class="modal modal-sm">
      <div class="modal-header">
        <h3 class="modal-title">Reject Registration</h3>
        <button class="modal-close" onclick="closeRejectModal()">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to reject this student's registration?</p>
        <p class="helper-text helper-text--danger">
          <strong>Warning:</strong> This action cannot be undone.
        </p>
      </div>
      <div class="modal-footer">
        <button class="btn btn-outline btn-sm" onclick="closeRejectModal()">Cancel</button>
        <button class="btn btn-danger btn-sm" onclick="confirmReject()">
          Reject
        </button>
      </div>
    </div>
  </div>

  <?php include 'includes/scripts.php'; ?>
  <script>
    let pendingStudents = [];
    let filteredStudents = [];
    let pendingApprovalUserId = null;
    let pendingRejectionUserId = null;

    // Load pending registrations on page load
    document.addEventListener('DOMContentLoaded', async function() {
      // Ensure modals are hidden on page load (defensive coding)
      document.getElementById('approveModalBackdrop').classList.remove('show');
      document.getElementById('rejectModalBackdrop').classList.remove('show');

      await loadPendingRegistrations();

      // Setup search functionality
      document.getElementById('searchInput').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        filterStudents(searchTerm);
      });
    });

    async function loadPendingRegistrations() {
      try {
        const response = await fetch('/cics-attendance-system/backend/api/admin/pending', {
          method: 'GET',
          credentials: 'include'
        });

        const data = await response.json();

        if (response.ok && data.success) {
          pendingStudents = data.data || [];
          filteredStudents = [...pendingStudents];
          renderPendingStudents();
        } else {
          Toast.error(data.message || 'Failed to load pending registrations', 'Error');
          showEmptyState();
        }
      } catch (error) {
        Toast.error('Failed to connect to server', 'Error');
        showEmptyState();
      } finally {
        document.getElementById('loadingState').style.display = 'none';
      }
    }

    function renderPendingStudents() {
      const tbody = document.getElementById('pendingStudentsTable');
      tbody.innerHTML = '';

      if (filteredStudents.length === 0) {
        showEmptyState();
        return;
      }

      document.getElementById('emptyState').style.display = 'none';
      document.getElementById('tableContainer').style.display = 'block';

      filteredStudents.forEach(student => {
        const row = document.createElement('tr');
        const fullName = `${student.first_name} ${student.last_name}`;
        const deviceFingerprint = student.device_fingerprint || 'N/A';
        const fingerprintDisplay = deviceFingerprint !== 'N/A' ?
          `${deviceFingerprint.substring(0, 10)}...` : 'N/A';
        const registrationDate = new Date(student.created_at).toLocaleDateString();

        row.innerHTML = `
          <td>${student.student_id}</td>
          <td>${fullName}</td>
          <td>${student.program}</td>
          <td class="text-mono" title="${deviceFingerprint}">${fingerprintDisplay}</td>
          <td>${registrationDate}</td>
          <td>
            <div class="action-buttons">
              <button class="btn-action btn-approve" title="Approve" onclick="approveStudent(${student.user_id})">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
              </button>
              <button class="btn-action btn-reject" title="Reject" onclick="rejectStudent(${student.user_id})">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
          </td>
        `;

        tbody.appendChild(row);
      });
    }

    function showEmptyState() {
      document.getElementById('tableContainer').style.display = 'none';
      document.getElementById('emptyState').style.display = 'block';
    }

    function filterStudents(searchTerm) {
      if (!searchTerm) {
        filteredStudents = [...pendingStudents];
      } else {
        filteredStudents = pendingStudents.filter(student => {
          const fullName = `${student.first_name} ${student.last_name}`.toLowerCase();
          const studentId = student.student_id.toLowerCase();
          const program = student.program.toLowerCase();

          return fullName.includes(searchTerm) ||
            studentId.includes(searchTerm) ||
            program.includes(searchTerm);
        });
      }

      renderPendingStudents();
    }

    // Approve/Reject trigger functions
    function approveStudent(userId) {
      pendingApprovalUserId = userId;
      document.getElementById('approveModalBackdrop').classList.add('show');
    }

    function rejectStudent(userId) {
      pendingRejectionUserId = userId;
      document.getElementById('rejectModalBackdrop').classList.add('show');
    }

    // Close modal functions
    function closeApproveModal() {
      document.getElementById('approveModalBackdrop').classList.remove('show');
      pendingApprovalUserId = null;
    }

    function closeRejectModal() {
      document.getElementById('rejectModalBackdrop').classList.remove('show');
      pendingRejectionUserId = null;
    }

    // Confirm approve
    async function confirmApprove() {
      const userId = pendingApprovalUserId;
      if (!userId) return;

      closeApproveModal();

      try {
        const response = await fetch('/cics-attendance-system/backend/api/admin/approve', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          credentials: 'include',
          body: JSON.stringify({
            user_id: userId,
            action: 'approve'
          })
        });

        const data = await response.json();

        if (response.ok && data.success) {
          Toast.success('Student registration approved successfully', 'Success');
          pendingStudents = pendingStudents.filter(s => s.user_id !== userId);
          filteredStudents = filteredStudents.filter(s => s.user_id !== userId);
          renderPendingStudents();
        } else {
          Toast.error(data.message || 'Failed to approve registration', 'Error');
        }
      } catch (error) {
        Toast.error('Failed to connect to server', 'Error');
      }
    }

    // Confirm reject
    async function confirmReject() {
      const userId = pendingRejectionUserId;
      if (!userId) return;

      closeRejectModal();

      try {
        const response = await fetch('/cics-attendance-system/backend/api/admin/approve', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          credentials: 'include',
          body: JSON.stringify({
            user_id: userId,
            action: 'reject'
          })
        });

        const data = await response.json();

        if (response.ok && data.success) {
          Toast.success('Student registration rejected', 'Success');
          pendingStudents = pendingStudents.filter(s => s.user_id !== userId);
          filteredStudents = filteredStudents.filter(s => s.user_id !== userId);
          renderPendingStudents();
        } else {
          Toast.error(data.message || 'Failed to reject registration', 'Error');
        }
      } catch (error) {
        Toast.error('Failed to connect to server', 'Error');
      }
    }

    // Close modals when clicking backdrop
    document.getElementById('approveModalBackdrop').addEventListener('click', function(e) {
      if (e.target === this) {
        closeApproveModal();
      }
    });

    document.getElementById('rejectModalBackdrop').addEventListener('click', function(e) {
      if (e.target === this) {
        closeRejectModal();
      }
    });
  </script>
</body>

</html>
