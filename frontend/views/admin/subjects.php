<?php
require_once __DIR__ . '/../../../auth_check.php';
require_role('admin');
$activePage = 'subjects';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Subjects - CICS Attendance System</title>
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
            <h1 class="page-title">Subjects</h1>
            <p class="page-subtitle">Manage course subjects and schedules</p>
          </div>
        </div>

        <div class="card">
          <div class="card-body">
            <div class="dashboard-section-header">
              <div class="page-heading-actions">
                <button type="button" class="btn btn-primary" id="addSubjectBtn">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                  </svg>
                  Add Subject
                </button>
              </div>
              <div class="search-container table-search full-width">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="search-icon">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
                <input type="text" class="search-input" id="subjectSearchInput" placeholder="Search subjects..." aria-label="Search subjects">
              </div>
            </div>

            <div class="table-container">
              <table class="table">
                <thead>
                  <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Instructor</th>
                    <th>Program</th>
                    <th>Year Level</th>
                    <th>Section</th>
                    <th>Room</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody id="subjectsTableBody">
                  <tr>
                    <td colspan="8">
                      <div class="text-muted">Loading subjects...</div>
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

  <!-- Add/Edit Subject Modal -->
  <div class="modal-backdrop" id="subjectModalBackdrop">
    <div class="modal modal-md" id="subjectModal">
      <div class="modal-header">
        <h3 class="modal-title" id="subjectModalTitle">Add Subject</h3>
        <button class="modal-close" type="button" aria-label="Close modal" onclick="Modal.close('subjectModal')">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      <form id="subjectForm" autocomplete="off">
        <div class="modal-body">
          <div class="form-grid">
            <div class="form-field form-group">
              <label for="subjectCode">Subject Code <span class="text-danger">*</span></label>
              <input type="text" id="subjectCode" name="code" class="form-control" required maxlength="20">
            </div>
            <div class="form-field form-group" style="grid-column: span 2;">
              <label for="subjectName">Subject Name <span class="text-danger">*</span></label>
              <input type="text" id="subjectName" name="name" class="form-control" required maxlength="255">
            </div>
            <div class="form-field form-group" style="grid-column: span 2;">
              <label for="subjectInstructor">Instructor <span class="text-danger">*</span></label>
              <select id="subjectInstructor" name="instructor_id" class="form-control" required>
                <option value="">Select Instructor</option>
              </select>
            </div>
            <div class="form-field form-group">
              <label for="subjectProgram">Program <span class="text-danger">*</span></label>
              <select id="subjectProgram" name="program" class="form-control" required>
                <option value="">Select Program</option>
                <option value="BSCS">BSCS</option>
                <option value="BSIT">BSIT</option>
                <option value="BSIS">BSIS</option>
                <option value="BSCE">BSCE</option>
              </select>
            </div>
            <div class="form-field form-group">
              <label for="subjectYearLevel">Year Level <span class="text-danger">*</span></label>
              <select id="subjectYearLevel" name="year_level" class="form-control" required>
                <option value="">Select Year</option>
                <option value="1">1st Year</option>
                <option value="2">2nd Year</option>
                <option value="3">3rd Year</option>
                <option value="4">4th Year</option>
              </select>
            </div>
            <div class="form-field form-group">
              <label for="subjectSection">Section <span class="text-danger">*</span></label>
              <input type="text" id="subjectSection" name="section" class="form-control" required maxlength="10">
            </div>
            <div class="form-field form-group">
              <label for="subjectRoom">Room</label>
              <input type="text" id="subjectRoom" name="room" class="form-control" maxlength="50">
            </div>
            <div class="form-field form-group" style="grid-column: span 2;">
              <label for="subjectSchedule">Schedule (JSON Format - Optional)</label>
              <textarea id="subjectSchedule" name="schedule" class="form-control" rows="3" placeholder='{"day": "Monday", "start_time": "08:00", "end_time": "11:00"}'></textarea>
              <p class="helper-text">Enter schedule in JSON format. Example: {"day": "Monday", "start_time": "08:00", "end_time": "11:00"} or leave empty.</p>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline" onclick="Modal.close('subjectModal')">Cancel</button>
          <button type="submit" class="btn btn-primary" id="saveSubjectBtn">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
            </svg>
            Save Subject
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Delete Confirmation Modal -->
  <div class="modal-backdrop" id="deleteSubjectModalBackdrop">
    <div class="modal modal-sm">
      <div class="modal-header">
        <h3 class="modal-title">Delete Subject</h3>
        <button class="modal-close" type="button" aria-label="Close modal" onclick="Modal.close('deleteSubjectModal')">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this subject? This action cannot be undone.</p>
        <p class="text-muted" id="deleteSubjectInfo"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="Modal.close('deleteSubjectModal')">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
          </svg>
          Delete
        </button>
      </div>
    </div>
  </div>

  <?php include 'includes/scripts.php'; ?>
  <script>
    (function() {
      const API_BASE = '/cics-attendance-system/backend/api';
      const state = {
        subjects: [],
        instructors: [],
        search: '',
        editingSubjectId: null
      };

      const elements = {
        addSubjectBtn: document.getElementById('addSubjectBtn'),
        searchInput: document.getElementById('subjectSearchInput'),
        subjectsTableBody: document.getElementById('subjectsTableBody'),
        subjectForm: document.getElementById('subjectForm'),
        saveSubjectBtn: document.getElementById('saveSubjectBtn'),
        subjectModalTitle: document.getElementById('subjectModalTitle'),
        confirmDeleteBtn: document.getElementById('confirmDeleteBtn'),
        deleteSubjectInfo: document.getElementById('deleteSubjectInfo')
      };

      document.addEventListener('DOMContentLoaded', init);

      function init() {
        attachEvents();
        fetchAllData();
      }

      function attachEvents() {
        elements.addSubjectBtn.addEventListener('click', () => {
          state.editingSubjectId = null;
          elements.subjectModalTitle.textContent = 'Add Subject';
          elements.subjectForm.reset();
          document.getElementById('subjectInstructor').innerHTML = '<option value="">Select Instructor</option>';
          loadInstructors();
          Modal.open('subjectModal');
        });

        elements.searchInput.addEventListener('input', (event) => {
          state.search = event.target.value.toLowerCase();
          renderSubjects();
        });

        elements.subjectForm.addEventListener('submit', handleSaveSubject);
        elements.confirmDeleteBtn.addEventListener('click', handleDeleteSubject);
      }

      async function fetchAllData() {
        await Promise.all([fetchSubjects(), fetchInstructors()]);
        renderSubjects();
      }

      async function fetchSubjects() {
        try {
          const response = await fetch(`${API_BASE}/admin/subjects`, { credentials: 'include' });
          const result = await response.json();

          if (!response.ok || !result.success) {
            throw new Error(result.message || 'Failed to load subjects');
          }

          state.subjects = Array.isArray(result.data) ? result.data : [];
        } catch (error) {
          Toast.error('Unable to load subjects right now.');
          elements.subjectsTableBody.innerHTML = `
            <tr>
              <td colspan="8">
                <div class="text-muted">Error loading subjects.</div>
              </td>
            </tr>`;
        }
      }

      async function fetchInstructors() {
        try {
          const response = await fetch(`${API_BASE}/admin/instructors`, { credentials: 'include' });
          const result = await response.json();

          if (!response.ok || !result.success) {
            throw new Error(result.message || 'Failed to load instructors');
          }

          state.instructors = Array.isArray(result.data) ? result.data : [];
        } catch (error) {
          Toast.error('Unable to load instructors right now.');
        }
      }

      function loadInstructors() {
        const select = document.getElementById('subjectInstructor');
        select.innerHTML = '<option value="">Select Instructor</option>';
        state.instructors.forEach(instructor => {
          const option = document.createElement('option');
          option.value = instructor.id;
          option.textContent = `${instructor.first_name} ${instructor.last_name}`;
          select.appendChild(option);
        });
      }

      function filterSubjects() {
        if (!state.search) return state.subjects;
        return state.subjects.filter(subject =>
          subject.code.toLowerCase().includes(state.search) ||
          subject.name.toLowerCase().includes(state.search) ||
          (subject.instructor_name && subject.instructor_name.toLowerCase().includes(state.search)) ||
          subject.program.toLowerCase().includes(state.search) ||
          subject.section.toLowerCase().includes(state.search) ||
          (subject.room && subject.room.toLowerCase().includes(state.search))
        );
      }

      function renderSubjects() {
        const list = filterSubjects();
        if (!list.length) {
          elements.subjectsTableBody.innerHTML = `
            <tr>
              <td colspan="8">
                <div class="text-muted">No subjects found.</div>
              </td>
            </tr>`;
          return;
        }

        elements.subjectsTableBody.innerHTML = list.map(subject => `
          <tr>
            <td><strong>${subject.code || '—'}</strong></td>
            <td>${subject.name || '—'}</td>
            <td>${subject.instructor_name || '—'}</td>
            <td>${subject.program || '—'}</td>
            <td>${subject.year_level || '—'}</td>
            <td>${subject.section || '—'}</td>
            <td>${subject.room || '—'}</td>
            <td>
              <div class="action-buttons">
                <button class="btn-icon-square btn-icon--primary" title="Edit" onclick="handleEditSubject(${subject.id})">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                  </svg>
                </button>
                <button class="btn-icon-square btn-icon--danger" title="Delete" onclick="handleDeleteClick(${subject.id}, '${(subject.code || '').replace(/'/g, "\\'")}', '${(subject.name || '').replace(/'/g, "\\'")}')">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                  </svg>
                </button>
              </div>
            </td>
          </tr>
        `).join('');
      }

      window.handleEditSubject = async function(subjectId) {
        try {
          const response = await fetch(`${API_BASE}/admin/subjects/${subjectId}`, { credentials: 'include' });
          const result = await response.json();

          if (!response.ok || !result.success) {
            throw new Error(result.message || 'Failed to load subject');
          }

          const subject = result.data;
          state.editingSubjectId = subject.id;

          document.getElementById('subjectCode').value = subject.code || '';
          document.getElementById('subjectName').value = subject.name || '';
          document.getElementById('subjectProgram').value = subject.program || '';
          document.getElementById('subjectYearLevel').value = subject.year_level || '';
          document.getElementById('subjectSection').value = subject.section || '';
          document.getElementById('subjectRoom').value = subject.room || '';
          // Handle schedule - if it's an object, stringify it; if it's already a string, use it; otherwise empty
          if (subject.schedule) {
            const scheduleValue = typeof subject.schedule === 'object' 
              ? JSON.stringify(subject.schedule) 
              : subject.schedule;
            document.getElementById('subjectSchedule').value = scheduleValue;
          } else {
            document.getElementById('subjectSchedule').value = '';
          }

          loadInstructors();
          setTimeout(() => {
            document.getElementById('subjectInstructor').value = subject.instructor_id || '';
          }, 100);

          elements.subjectModalTitle.textContent = 'Edit Subject';
          Modal.open('subjectModal');
        } catch (error) {
          Toast.error(error.message || 'Unable to load subject.');
        }
      };

      window.handleDeleteClick = function(subjectId, code, name) {
        state.editingSubjectId = subjectId;
        elements.deleteSubjectInfo.textContent = `Subject: ${code} - ${name}`;
        Modal.open('deleteSubjectModal');
      };

      async function handleSaveSubject(event) {
        event.preventDefault();
        if (!FormValidator.validate(elements.subjectForm)) return;

        const formData = new FormData(elements.subjectForm);
        
        // Parse schedule JSON if provided
        let schedule = null;
        const scheduleInput = formData.get('schedule')?.trim();
        if (scheduleInput) {
          try {
            schedule = JSON.parse(scheduleInput);
          } catch (e) {
            Toast.error('Invalid JSON format in Schedule field. Please check the format.');
            setSavingState(false);
            return;
          }
        }
        
        const payload = {
          code: formData.get('code')?.trim(),
          name: formData.get('name')?.trim(),
          instructor_id: parseInt(formData.get('instructor_id')),
          program: formData.get('program')?.trim(),
          year_level: parseInt(formData.get('year_level')),
          section: formData.get('section')?.trim(),
          room: formData.get('room')?.trim() || null,
          schedule: schedule
        };

        setSavingState(true);

        try {
          const url = state.editingSubjectId
            ? `${API_BASE}/admin/subjects/${state.editingSubjectId}`
            : `${API_BASE}/admin/subjects`;
          const method = state.editingSubjectId ? 'PUT' : 'POST';

          const response = await fetch(url, {
            method: method,
            headers: { 'Content-Type': 'application/json' },
            credentials: 'include',
            body: JSON.stringify(payload)
          });

          const result = await response.json();

          if (!response.ok || !result.success) {
            const errorMessage = result.errors ? Object.values(result.errors).flat().join(', ') : result.message;
            throw new Error(errorMessage || 'Failed to save subject');
          }

          Toast.success(state.editingSubjectId ? 'Subject updated successfully' : 'Subject added successfully');
          elements.subjectForm.reset();
          Modal.close('subjectModal');
          await fetchSubjects();
          renderSubjects();
        } catch (error) {
          Toast.error(error.message || 'Unable to save subject.');
        } finally {
          setSavingState(false);
        }
      }

      async function handleDeleteSubject() {
        if (!state.editingSubjectId) return;

        elements.confirmDeleteBtn.disabled = true;
        elements.confirmDeleteBtn.innerHTML = 'Deleting...';

        try {
          const response = await fetch(`${API_BASE}/admin/subjects/${state.editingSubjectId}`, {
            method: 'DELETE',
            credentials: 'include'
          });

          const result = await response.json();

          if (!response.ok || !result.success) {
            throw new Error(result.message || 'Failed to delete subject');
          }

          Toast.success('Subject deleted successfully');
          Modal.close('deleteSubjectModal');
          await fetchSubjects();
          renderSubjects();
        } catch (error) {
          Toast.error(error.message || 'Unable to delete subject.');
        } finally {
          elements.confirmDeleteBtn.disabled = false;
          elements.confirmDeleteBtn.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
            </svg>
            Delete`;
        }
      }

      function setSavingState(isSaving) {
        elements.saveSubjectBtn.disabled = isSaving;
        elements.saveSubjectBtn.innerHTML = isSaving ? 'Saving...' : `
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
          </svg>
          Save Subject`;
      }
    })();
  </script>
</body>
</html>

