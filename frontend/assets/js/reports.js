// Reports Page JavaScript - Database Integration
// Use dedicated AJAX file
const API_BASE = 'reports-ajax.php';

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
  loadFilterOptions();
  loadRecentReports();
  initializeForm();
});

/**
 * Load filter options from database
 */
async function loadFilterOptions() {
  try {
    const response = await fetch(`${API_BASE}?action=get_filter_options`, {
      credentials: 'same-origin',
      headers: {
        'Accept': 'application/json'
      }
    });
    
    if (!response.ok) {
      throw new Error(`HTTP ${response.status}: ${response.statusText}`);
    }
    
    const text = await response.text();
    let data;
    try {
      data = JSON.parse(text);
    } catch (e) {
      console.error('Invalid JSON:', text);
      throw new Error('Server returned invalid JSON');
    }
    
    if (data.success) {
      populateDropdowns(data);
    } else {
      console.error('Failed to load filter options:', data.message);
      showNotification('Failed to load filter options: ' + data.message, 'error');
    }
  } catch (error) {
    console.error('Error loading filter options:', error);
    showNotification('Error: ' + error.message, 'error');
  }
}

/**
 * Populate dropdown options
 */
function populateDropdowns(data) {
  // Populate Programs
  const programSelect = document.getElementById('program');
  data.programs.forEach(program => {
    const option = document.createElement('option');
    option.value = program;
    option.textContent = program.toUpperCase();
    programSelect.appendChild(option);
  });
  
  // Populate Year Levels
  const yearSelect = document.getElementById('yearLevel');
  data.year_levels.forEach(year => {
    const option = document.createElement('option');
    option.value = year;
    option.textContent = `${year}${getOrdinalSuffix(year)} Year`;
    yearSelect.appendChild(option);
  });
  
  // Populate Sections
  const sectionSelect = document.getElementById('section');
  data.sections.forEach(section => {
    const option = document.createElement('option');
    option.value = section;
    option.textContent = `Section ${section}`;
    sectionSelect.appendChild(option);
  });
}

/**
 * Get ordinal suffix (1st, 2nd, 3rd, 4th)
 */
function getOrdinalSuffix(num) {
  const j = num % 10;
  const k = num % 100;
  if (j === 1 && k !== 11) return 'st';
  if (j === 2 && k !== 12) return 'nd';
  if (j === 3 && k !== 13) return 'rd';
  return 'th';
}

/**
 * Load recent reports from database
 */
async function loadRecentReports() {
  const loadingState = document.getElementById('loadingState');
  const emptyState = document.getElementById('emptyState');
  const container = document.getElementById('reportsListContainer');
  
  try {
    const response = await fetch(`${API_BASE}?action=get_recent_reports&limit=10`, {
      credentials: 'same-origin'
    });
    const data = await response.json();
    
    loadingState.style.display = 'none';
    
    if (data.success && data.reports.length > 0) {
      emptyState.style.display = 'none';
      renderReports(data.reports);
    } else {
      emptyState.style.display = 'block';
    }
  } catch (error) {
    loadingState.style.display = 'none';
    showNotification('Failed to load reports', 'error');
    console.error('Error loading reports:', error);
  }
}

/**
 * Render reports list
 */
function renderReports(reports) {
  const container = document.getElementById('reportsListContainer');
  
  // Clear existing items except loading/empty states
  const items = container.querySelectorAll('.report-item');
  items.forEach(item => item.remove());
  
  reports.forEach(report => {
    const reportItem = createReportItem(report);
    container.appendChild(reportItem);
  });
}

/**
 * Create report item element
 */
function createReportItem(report) {
  const item = document.createElement('div');
  item.className = 'report-item';
  
  const iconClass = getIconClass(report.file_format);
  const formattedDate = formatDate(report.created_at);
  const fileSize = formatFileSize(report.file_size);
  
  item.innerHTML = `
    <div class="report-icon ${iconClass}">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
      </svg>
    </div>
    <div class="report-info">
      <h4 class="report-name">${report.file_name}</h4>
      <p class="report-meta">Generated on ${formattedDate} • ${fileSize}</p>
    </div>
    <button class="btn-download" onclick="downloadReport('${report.file_path}', '${report.file_name}')">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
      </svg>
    </button>
  `;
  
  return item;
}

/**
 * Get icon class based on file format
 */
function getIconClass(format) {
  switch(format.toLowerCase()) {
    case 'xlsx': return 'excel';
    case 'pdf': return 'pdf';
    case 'csv': return 'csv';
    default: return 'excel';
  }
}

/**
 * Format date
 */
function formatDate(dateString) {
  const date = new Date(dateString);
  const options = { year: 'numeric', month: 'short', day: 'numeric' };
  return date.toLocaleDateString('en-US', options);
}

/**
 * Format file size
 */
function formatFileSize(bytes) {
  if (bytes === 0) return '0 Bytes';
  const k = 1024;
  const sizes = ['Bytes', 'KB', 'MB', 'GB'];
  const i = Math.floor(Math.log(bytes) / Math.log(k));
  return Math.round((bytes / Math.pow(k, i)) * 100) / 100 + ' ' + sizes[i];
}

/**
 * Initialize form
 */
function initializeForm() {
  const form = document.getElementById('reportForm');
  form.addEventListener('submit', handleFormSubmit);
  
  // Set default date range (last 30 days)
  const today = new Date();
  const lastMonth = new Date(today);
  lastMonth.setDate(today.getDate() - 30);
  
  document.getElementById('dateTo').valueAsDate = today;
  document.getElementById('dateFrom').valueAsDate = lastMonth;
}

/**
 * Handle form submission
 */
async function handleFormSubmit(e) {
  e.preventDefault();
  
  const formData = getFormData();
  
  // Validate dates
  if (!validateDateRange(formData.dateFrom, formData.dateTo)) {
    showNotification('Please select a valid date range', 'error');
    return;
  }
  
  // Show loading state
  setLoadingState(true);
  
  try {
    // Fetch report data from database
    const response = await fetch(`${API_BASE}?action=get_report_data`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      credentials: 'same-origin',
      body: JSON.stringify(formData)
    });
    
    const data = await response.json();
    
    if (data.success) {
      // Generate the report file (CSV for now)
      generateReportFile(data.data, formData);
    } else {
      showNotification(data.message || 'Failed to generate report', 'error');
    }
  } catch (error) {
    showNotification('Error generating report', 'error');
    console.error('Error:', error);
  } finally {
    setLoadingState(false);
  }
}

/**
 * Get form data
 */
function getFormData() {
  return {
    reportType: document.getElementById('reportType').value,
    dateFrom: document.getElementById('dateFrom').value,
    dateTo: document.getElementById('dateTo').value,
    program: document.getElementById('program').value,
    yearLevel: document.getElementById('yearLevel').value,
    section: document.getElementById('section').value,
    format: document.querySelector('input[name="format"]:checked').value
  };
}

/**
 * Validate date range
 */
function validateDateRange(dateFrom, dateTo) {
  if (!dateFrom || !dateTo) return false;
  return new Date(dateFrom) <= new Date(dateTo);
}

/**
 * Generate report file (CSV/Excel)
 */
function generateReportFile(data, formData) {
  if (!data || data.length === 0) {
    showNotification('No data available for the selected filters', 'warning');
    return;
  }
  
  // For CSV format
  if (formData.format === 'csv') {
    const csv = convertToCSV(data, formData.reportType);
    const fileName = generateFileName(formData);
    downloadCSV(csv, fileName);
    showNotification('Report generated successfully!', 'success');
    
    // Reload recent reports
    setTimeout(() => loadRecentReports(), 1000);
  } else {
    showNotification('Excel and PDF formats coming soon!', 'info');
  }
}

/**
 * Convert data to CSV
 */
function convertToCSV(data, reportType) {
  if (data.length === 0) return '';
  
  // Get headers
  const headers = Object.keys(data[0]);
  const csvHeaders = headers.join(',');
  
  // Get rows
  const csvRows = data.map(row => {
    return headers.map(header => {
      const value = row[header] !== null ? row[header] : '';
      // Escape quotes and wrap in quotes if contains comma
      const escaped = String(value).replace(/"/g, '""');
      return escaped.includes(',') ? `"${escaped}"` : escaped;
    }).join(',');
  });
  
  return [csvHeaders, ...csvRows].join('\n');
}

/**
 * Download CSV
 */
function downloadCSV(csv, fileName) {
  const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
  const link = document.createElement('a');
  const url = URL.createObjectURL(blob);
  
  link.setAttribute('href', url);
  link.setAttribute('download', fileName);
  link.style.visibility = 'hidden';
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
}

/**
 * Generate file name
 */
function generateFileName(formData) {
  const reportTypeName = formData.reportType.replace(/_/g, '_');
  const date = new Date().toISOString().split('T')[0];
  return `${reportTypeName}_${date}.${formData.format}`;
}

/**
 * Download report
 */
function downloadReport(filePath, fileName) {
  showNotification('Downloading report...', 'info');
  
  // Create a temporary link to download
  const link = document.createElement('a');
  link.href = filePath;
  link.download = fileName;
  link.click();
  
  setTimeout(() => {
    showNotification('Report downloaded successfully!', 'success');
  }, 500);
}

/**
 * Set loading state
 */
function setLoadingState(isLoading) {
  const button = document.querySelector('.btn-generate');
  const originalHTML = button.getAttribute('data-original-html') || button.innerHTML;
  
  if (!button.hasAttribute('data-original-html')) {
    button.setAttribute('data-original-html', originalHTML);
  }
  
  if (isLoading) {
    button.disabled = true;
    button.innerHTML = `
      <svg class="spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
      </svg>
      Generating...
    `;
  } else {
    button.disabled = false;
    button.innerHTML = originalHTML;
  }
}

/**
 * Show notification
 */
function showNotification(message, type = 'info') {
  // Remove existing notification
  const existing = document.querySelector('.notification');
  if (existing) existing.remove();
  
  // Create notification
  const notification = document.createElement('div');
  notification.className = `notification notification-${type}`;
  notification.textContent = message;
  
  // Add to body
  document.body.appendChild(notification);
  
  // Trigger animation
  setTimeout(() => notification.classList.add('show'), 10);
  
  // Auto remove
  setTimeout(() => {
    notification.classList.remove('show');
    setTimeout(() => notification.remove(), 300);
  }, 3000);
}

// Add CSS for notifications and spinner
const style = document.createElement('style');
style.textContent = `
  .notification {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 1rem 1.5rem;
    border-radius: 8px;
    font-size: 0.9375rem;
    font-weight: 500;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    opacity: 0;
    transform: translateX(100px);
    transition: all 0.3s ease;
    z-index: 9999;
  }

  .notification.show {
    opacity: 1;
    transform: translateX(0);
  }

  .notification-success {
    background: #d4f4dd;
    color: #166534;
    border-left: 4px solid #16a34a;
  }

  .notification-error {
    background: #fecaca;
    color: #991b1b;
    border-left: 4px solid #dc2626;
  }

  .notification-info {
    background: #bfdbfe;
    color: #1e40af;
    border-left: 4px solid #3b82f6;
  }

  .notification-warning {
    background: #fef3c7;
    color: #92400e;
    border-left: 4px solid #f59e0b;
  }

  .spin {
    animation: spin 1s linear infinite;
  }

  @keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
  }

  .loading-state, .empty-state {
    text-align: center;
    padding: 2rem;
    color: #6b7280;
  }
`;
document.head.appendChild(style);
