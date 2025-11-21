<?php
require_once __DIR__ . '/../../../auth_check.php';
require_role('admin');
$activePage = 'manage';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Accounts - CICS Attendance System</title>
  <link rel="stylesheet" href="../../assets/css/base/variables.css">
  <link rel="stylesheet" href="../../assets/css/pages/admin.css">
  <link rel="stylesheet" href="../../assets/css/main.css">
  <link rel="stylesheet" href="../../assets/css/components/modals.css">
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
            <h1 class="page-title">Manage Accounts</h1>
            <p class="page-subtitle">Maintain instructor and student records</p>
          </div>
        </div>

        <div class="card">
          <div class="card-body">
            <div class="tab-nav" id="manageTabs">
              <button type="button" class="tab-btn active" data-tab="instructors">Instructors</button>
              <button type="button" class="tab-btn" data-tab="students">Students</button>
            </div>

            <div class="dashboard-section-header">
              <div class="page-heading-actions">
                <button type="button" class="btn btn-primary" id="addInstructorBtn">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                  </svg>
                  Add Instructor
                </button>
              </div>
              <div class="search-container table-search full-width">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="search-icon">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
                <input type="text" class="search-input" id="manageSearchInput" placeholder="Search instructors..." aria-label="Search records">
              </div>
            </div>

            <div class="table-container" id="instructorsSection">
              <table class="table">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Department</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody id="instructorsTableBody"></tbody>
              </table>
            </div>

            <div class="table-container" id="studentsSection" hidden>
              <table class="table">
                <thead>
                  <tr>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Program</th>
                    <th>Section</th>
                    <th>Email</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody id="studentsTableBody"></tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="modal-backdrop" id="addInstructorModalBackdrop">
          <div class="modal modal-md" id="addInstructorModal">
            <div class="modal-header">
              <h3 class="modal-title">Add Instructor</h3>
              <button class="modal-close" type="button" aria-label="Close modal">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
            <form id="addInstructorForm" autocomplete="off">
              <div class="modal-body">
                <div class="form-grid">
                  <div class="form-field form-group">
                    <label for="instructorFirstName">First Name</label>
                    <input type="text" id="instructorFirstName" name="first_name" class="form-control" required>
                  </div>
                  <div class="form-field form-group">
                    <label for="instructorLastName">Last Name</label>
                    <input type="text" id="instructorLastName" name="last_name" class="form-control" required>
                  </div>
                  <div class="form-field form-group">
                    <label for="instructorDepartment">Department</label>
                    <input type="text" id="instructorDepartment" name="department" class="form-control" required>
                  </div>
                  <div class="form-field form-group">
                    <label for="instructorEmployeeId">Employee ID (optional)</label>
                    <input type="text" id="instructorEmployeeId" name="employee_id" class="form-control">
                  </div>
                  <div class="form-field form-group" style="grid-column: span 2;">
                    <label for="instructorEmail">Email</label>
                    <input type="email" id="instructorEmail" name="email" class="form-control" required>
                  </div>
                </div>
                <p class="helper-text">A temporary password will be generated and emailed via EmailJS once configured.</p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="Modal.close('addInstructorModal')">Cancel</button>
                <button type="submit" class="btn btn-primary" id="saveInstructorBtn">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                  </svg>
                  Save Instructor
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </main>
  </div>

  <?php include 'includes/scripts.php'; ?>
  <script src="https://cdn.jsdelivr.net/npm/emailjs-com@3/dist/email.min.js"></script>
  <script src="../../assets/js/emailjs-config.js"></script>
  <script>
    // Initialize EmailJS when library is loaded
    function initEmailJS() {
      if (typeof emailjs === 'undefined') {
        setTimeout(initEmailJS, 100);
        return;
      }

      const config = window.EMAILJS_CONFIG || {};
      
      if (config.publicKey && !config.publicKey.startsWith('YOUR_')) {
        try {
          emailjs.init(config.publicKey);
        } catch (error) {
          // EmailJS initialization failed silently
        }
      }
    }

    // Try to initialize immediately
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', initEmailJS);
    } else {
      initEmailJS();
    }
  </script>
  <script>
    (function() {
      const API_BASE = '/cics-attendance-system/backend/api';
      const state = {
        activeTab: 'instructors',
        search: '',
        instructors: [],
        students: []
      };

      const elements = {
        tabButtons: document.querySelectorAll('.tab-btn'),
        searchInput: document.getElementById('manageSearchInput'),
        addInstructorBtn: document.getElementById('addInstructorBtn'),
        instructorsSection: document.getElementById('instructorsSection'),
        studentsSection: document.getElementById('studentsSection'),
        instructorsTableBody: document.getElementById('instructorsTableBody'),
        studentsTableBody: document.getElementById('studentsTableBody'),
        addInstructorForm: document.getElementById('addInstructorForm'),
        saveInstructorBtn: document.getElementById('saveInstructorBtn')
      };

      document.addEventListener('DOMContentLoaded', init);

      function init() {
        attachEvents();
        fetchAllData();
        updateSearchPlaceholder();
        toggleAddButton();
      }

      function attachEvents() {
        elements.tabButtons.forEach((btn) => {
          btn.addEventListener('click', () => handleTabChange(btn.dataset.tab));
        });

        elements.searchInput.addEventListener('input', (event) => {
          state.search = event.target.value.toLowerCase();
          renderActiveTable();
        });

        elements.addInstructorBtn.addEventListener('click', () => {
          Modal.open('addInstructorModal');
        });

        elements.addInstructorForm.addEventListener('submit', handleAddInstructor);
      }

      async function fetchAllData() {
        await Promise.all([fetchInstructors(), fetchStudents()]);
        renderActiveTable();
      }

      async function fetchInstructors() {
        try {
          const response = await fetch(`${API_BASE}/admin/instructors`, { credentials: 'include' });
          const result = await response.json();

          if (!response.ok || !result.success) {
            throw new Error(result.message || 'Failed to load instructors');
          }

          state.instructors = Array.isArray(result.data) ? result.data : [];
          renderInstructors();
        } catch (error) {
          Toast.error('Unable to load instructors right now.');
        }
      }

      async function fetchStudents() {
        try {
          const response = await fetch(`${API_BASE}/admin/students`, { credentials: 'include' });
          const result = await response.json();

          if (!response.ok || !result.success) {
            throw new Error(result.message || 'Failed to load students');
          }

          state.students = Array.isArray(result.data) ? result.data : [];
          renderStudents();
        } catch (error) {
          Toast.error('Unable to load students right now.');
        }
      }

      function handleTabChange(tab) {
        if (state.activeTab === tab) return;
        state.activeTab = tab;

        elements.tabButtons.forEach((btn) => {
          btn.classList.toggle('active', btn.dataset.tab === tab);
        });

        elements.instructorsSection.hidden = tab !== 'instructors';
        elements.studentsSection.hidden = tab !== 'students';

        updateSearchPlaceholder();
        toggleAddButton();
        renderActiveTable();
      }

      function toggleAddButton() {
        elements.addInstructorBtn.style.display = state.activeTab === 'instructors' ? 'inline-flex' : 'none';
      }

      function updateSearchPlaceholder() {
        elements.searchInput.placeholder = state.activeTab === 'instructors'
          ? 'Search instructors...'
          : 'Search students...';
      }

      function renderActiveTable() {
        if (state.activeTab === 'instructors') {
          renderInstructors();
        } else {
          renderStudents();
        }
      }

      function filterList(items, fields) {
        if (!state.search) return items;
        return items.filter((item) =>
          fields.some((field) => {
            const value = (item[field] ?? '').toString().toLowerCase();
            return value.includes(state.search);
          })
        );
      }

      function renderInstructors() {
        const list = filterList(state.instructors, ['first_name', 'last_name', 'department', 'email', 'employee_id']);
        if (!list.length) {
          elements.instructorsTableBody.innerHTML = `
            <tr>
              <td colspan="6">
                <div class="text-muted">No instructors found.</div>
              </td>
            </tr>`;
          return;
        }

        elements.instructorsTableBody.innerHTML = list.map((instructor) => `
          <tr>
            <td>${formatInstructorId(instructor)}</td>
            <td>${formatName(instructor)}</td>
            <td>${instructor.department || '—'}</td>
            <td>${instructor.email || '—'}</td>
            <td>${renderStatusBadge(instructor.user_status)}</td>
            <td>
              <div class="action-buttons">
                <button class="btn-icon-square btn-icon--primary" title="Edit" disabled>
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                  </svg>
                </button>
                <button class="btn-icon-square btn-icon--danger" title="Delete" disabled>
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                  </svg>
                </button>
              </div>
            </td>
          </tr>
        `).join('');
      }

      function renderStudents() {
        const list = filterList(state.students, ['first_name', 'last_name', 'program', 'section', 'email', 'student_id']);
        if (!list.length) {
          elements.studentsTableBody.innerHTML = `
            <tr>
              <td colspan="6">
                <div class="text-muted">No students found.</div>
              </td>
            </tr>`;
          return;
        }

        elements.studentsTableBody.innerHTML = list.map((student) => `
          <tr>
            <td>${student.student_id || '—'}</td>
            <td>${formatName(student)}</td>
            <td>${student.program || '—'}</td>
            <td>${student.section || '—'}</td>
            <td>${student.email || '—'}</td>
            <td>${renderStatusBadge(student.user_status)}</td>
          </tr>
        `).join('');
      }

      function formatInstructorId(instructor) {
        if (instructor.employee_id) return instructor.employee_id;
        return `INST-${String(instructor.id).padStart(3, '0')}`;
      }

      function formatName(person) {
        return `${person.first_name || ''} ${person.last_name || ''}`.trim() || '—';
      }

      function renderStatusBadge(status = '') {
        const normalized = status ? status.toLowerCase() : '';
        const map = {
          active: { label: 'Active', className: 'badge-success' },
          pending: { label: 'Pending', className: 'badge-warning' },
          inactive: { label: 'Inactive', className: 'badge-danger' },
          rejected: { label: 'Rejected', className: 'badge-danger' }
        };

        const badge = map[normalized] || { label: normalized || 'Unknown', className: 'badge-warning' };
        return `<span class="badge ${badge.className}">${badge.label}</span>`;
      }

      async function handleAddInstructor(event) {
        event.preventDefault();
        if (!FormValidator.validate(elements.addInstructorForm)) return;

        const formData = new FormData(elements.addInstructorForm);
        const payload = {
          first_name: formData.get('first_name')?.trim(),
          last_name: formData.get('last_name')?.trim(),
          department: formData.get('department')?.trim(),
          email: formData.get('email')?.trim(),
          employee_id: formData.get('employee_id')?.trim() || null
        };

        setSavingState(true);

        try {
          const response = await fetch(`${API_BASE}/admin/instructors`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            credentials: 'include',
            body: JSON.stringify(payload)
          });

          const result = await response.json();

          if (!response.ok || !result.success) {
            const errorMessage = result.errors ? Object.values(result.errors).flat().join(', ') : result.message;
            throw new Error(errorMessage || 'Failed to add instructor');
          }

          const { instructor, temp_password: tempPassword } = result.data;
          state.instructors.unshift(instructor);
          renderInstructors();
          Toast.success('Instructor added successfully');

          elements.addInstructorForm.reset();
          Modal.close('addInstructorModal');
          sendInstructorCredentials(instructor, tempPassword);
        } catch (error) {
          Toast.error(error.message || 'Unable to add instructor.');
        } finally {
          setSavingState(false);
        }
      }

      function setSavingState(isSaving) {
        elements.saveInstructorBtn.disabled = isSaving;
        elements.saveInstructorBtn.innerHTML = isSaving ? 'Saving...' : `
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
          </svg>
          Save Instructor`;
      }

      function sendInstructorCredentials(instructor, tempPassword) {
        const config = window.EMAILJS_CONFIG || {};

        // Check if EmailJS is loaded
        if (!window.emailjs || typeof emailjs === 'undefined') {
          Toast.info('Instructor password generated. EmailJS library not loaded.');
          return;
        }

        // Check configuration
        if (!config.serviceId || !config.templateId || !config.publicKey || 
            config.serviceId.startsWith('YOUR_') || 
            config.templateId.startsWith('YOUR_') || 
            config.publicKey.startsWith('YOUR_')) {
          Toast.info('Instructor password generated. Configure EmailJS to send credentials automatically.');
          return;
        }

        // Ensure EmailJS is initialized
        if (config.publicKey) {
          try {
            emailjs.init(config.publicKey);
          } catch (initError) {
            Toast.error('EmailJS initialization failed.');
            return;
          }
        }

        // Prepare template parameters
        const templateParams = {
          to_email: instructor.email,
          reply_to: instructor.email,
          instructor_name: formatName(instructor),
          instructor_email: instructor.email,
          temp_password: tempPassword
        };

        // Send email
        emailjs.send(config.serviceId, config.templateId, templateParams)
          .then(() => {
            Toast.success('Login credentials sent to instructor via email.', null, 4000);
          })
          .catch((error) => {
            let errorMessage = 'Instructor created, but email could not be sent. ';
            if (error.status === 400) {
              errorMessage += 'Invalid template parameters.';
            } else if (error.status === 401) {
              errorMessage += 'Unauthorized. Check your Public Key.';
            } else if (error.status === 404) {
              errorMessage += 'Service or Template not found.';
            } else if (error.status === 412) {
              errorMessage += 'Gmail authentication required. Re-authenticate Gmail service in EmailJS Dashboard.';
            } else if (error.status === 422) {
              errorMessage += 'Invalid template or missing recipient.';
            } else if (error.status === 429) {
              errorMessage += 'Rate limit exceeded.';
            } else {
              errorMessage += `Error (${error.status}): ${error.text || error.message || 'Unknown error'}`;
            }
            Toast.error(errorMessage);
          });
      }
    })();
  </script>
</body>
</html>
