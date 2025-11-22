/**
 * Requests Page JavaScript
 * Handles modal interactions and form submission for correction requests
 */

document.addEventListener('DOMContentLoaded', () => {
  const fab = document.getElementById('newRequestFab');
  const modal = document.getElementById('newRequestModal');
  const closeModalBtn = document.getElementById('closeModal');
  const cancelBtn = document.getElementById('cancelRequest');
  const form = document.getElementById('newRequestForm');
  const fileInput = document.getElementById('attachment');
  const fileLabel = document.getElementById('fileLabel');
  const filePreview = document.getElementById('filePreview');

  // Open modal when FAB is clicked
  if (fab) {
    fab.addEventListener('click', () => {
      // Use the backdrop ID directly (Modal.open can handle both backdrop and modal IDs)
      if (modal) {
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
      } else {
        Modal.open('newRequestModal');
      }
    });
  }

  // Close modal handlers
  const closeModal = () => {
    // Close the backdrop directly
    if (modal) {
      modal.classList.remove('show');
      document.body.style.overflow = '';
    } else {
      Modal.close('newRequestModal');
    }
    // Reset form after a short delay to allow modal animation
    setTimeout(() => {
      if (form) {
        form.reset();
        filePreview.innerHTML = '';
        filePreview.classList.remove('show');
        fileLabel.textContent = 'Choose file or drag and drop';
        // Clear any validation errors
        form.querySelectorAll('.error').forEach(el => el.classList.remove('error'));
        form.querySelectorAll('.form-error').forEach(el => el.remove());
      }
    }, 300);
  };

  if (closeModalBtn) {
    closeModalBtn.addEventListener('click', closeModal);
  }

  if (cancelBtn) {
    cancelBtn.addEventListener('click', closeModal);
  }

  // File input handling
  if (fileInput) {
    fileInput.addEventListener('change', (e) => {
      handleFileSelection(e.target.files);
    });

    // Drag and drop functionality
    const fileUploadLabel = fileInput.closest('.file-upload-wrapper')?.querySelector('.file-upload-label');
    
    if (fileUploadLabel) {
      // Prevent default drag behaviors
      ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        fileUploadLabel.addEventListener(eventName, preventDefaults, false);
        document.body.addEventListener(eventName, preventDefaults, false);
      });

      // Highlight drop area when item is dragged over it
      ['dragenter', 'dragover'].forEach(eventName => {
        fileUploadLabel.addEventListener(eventName, () => {
          fileUploadLabel.classList.add('dragover');
        }, false);
      });

      ['dragleave', 'drop'].forEach(eventName => {
        fileUploadLabel.addEventListener(eventName, () => {
          fileUploadLabel.classList.remove('dragover');
        }, false);
      });

      // Handle dropped files
      fileUploadLabel.addEventListener('drop', handleDrop, false);
    }
  }

  function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
  }

  function handleDrop(e) {
    const dt = e.dataTransfer;
    const files = dt.files;
    if (files.length > 0) {
      fileInput.files = files;
      handleFileSelection(files);
    }
  }

  function handleFileSelection(files) {
    if (files.length === 0) return;

    const file = files[0];
    
    // Validate file type (PNG only)
    if (file.type !== 'image/png' && !file.name.toLowerCase().endsWith('.png')) {
      Toast.error('Only PNG files are allowed', 'Invalid File Type');
      fileInput.value = '';
      return;
    }

    // Validate file size (max 5MB)
    const maxSize = 5 * 1024 * 1024; // 5MB in bytes
    if (file.size > maxSize) {
      Toast.error('File size must be less than 5MB', 'File Too Large');
      fileInput.value = '';
      return;
    }

    // Display file preview
    displayFilePreview(file);
  }

  function displayFilePreview(file) {
    fileLabel.textContent = file.name;
    filePreview.innerHTML = '';
    
    const previewItem = document.createElement('div');
    previewItem.className = 'file-preview-item';
    
    const fileSize = formatFileSize(file.size);
    
    previewItem.innerHTML = `
      <div class="file-preview-info">
        <svg class="file-preview-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941l-7.81 7.81a1.5 1.5 0 002.112 2.13" />
        </svg>
        <div class="file-preview-details">
          <div class="file-preview-name">${file.name}</div>
          <div class="file-preview-size">${fileSize}</div>
        </div>
      </div>
      <button type="button" class="file-preview-remove" aria-label="Remove file">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    `;

    // Add remove button functionality
    const removeBtn = previewItem.querySelector('.file-preview-remove');
    removeBtn.addEventListener('click', () => {
      fileInput.value = '';
      filePreview.innerHTML = '';
      filePreview.classList.remove('show');
      fileLabel.textContent = 'Choose file or drag and drop';
    });

    filePreview.appendChild(previewItem);
    filePreview.classList.add('show');
  }

  function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
  }

  // Form submission
  if (form) {
    form.addEventListener('submit', (e) => {
      e.preventDefault();

      // Validate form
      if (!FormValidator.validate(form)) {
        Toast.error('Please fill in all required fields correctly', 'Validation Error');
        return;
      }

      // Get form data
      const formData = new FormData(form);
      const subject = formData.get('subject');
      const date = formData.get('date');
      const message = formData.get('message');
      const attachment = formData.get('attachment');

      // Validate file if present
      if (attachment && attachment.size > 0) {
        if (attachment.type !== 'image/png' && !attachment.name.toLowerCase().endsWith('.png')) {
          Toast.error('Only PNG files are allowed', 'Invalid File Type');
          return;
        }

        const maxSize = 5 * 1024 * 1024; // 5MB
        if (attachment.size > maxSize) {
          Toast.error('File size must be less than 5MB', 'File Too Large');
          return;
        }
      }

      // Show loading state
      const submitBtn = form.querySelector('button[type="submit"]');
      const originalText = submitBtn.textContent;
      submitBtn.disabled = true;
      submitBtn.textContent = 'Submitting...';

      // Simulate API call (frontend only - no backend)
      setTimeout(() => {
        Toast.success(
          'Your correction request has been submitted successfully!',
          'Request Submitted',
          5000
        );

        // Reset form and close modal
        closeModal();

        // Reset button state
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;

        // In a real application, you would:
        // 1. Send the form data to your backend API
        // 2. Handle the response
        // 3. Update the requests list
        // 4. Show success/error messages accordingly

        console.log('Form data would be sent to backend:', {
          subject,
          date,
          message,
          attachment: attachment ? attachment.name : 'No attachment'
        });
      }, 1500);
    });
  }
});

