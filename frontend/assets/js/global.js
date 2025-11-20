/**
 * Global JavaScript for CICS Attendance System
 */

// Toast Notification System
class Toast {
  static container = null;

  static init() {
    if (!this.container) {
      this.container = document.createElement('div');
      this.container.className = 'toast-container';
      document.body.appendChild(this.container);
    }
  }

  static show(message, type = 'info', title = null, duration = 5000) {
    this.init();

    const toast = document.createElement('div');
    toast.className = `toast ${type}`;

    const icon = this.getIcon(type);
    const closeIcon = `
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
      </svg>
    `;

    toast.innerHTML = `
      <div class="toast-icon">${icon}</div>
      <div class="toast-content">
        ${title ? `<div class="toast-title">${title}</div>` : ''}
        <div class="toast-message">${message}</div>
      </div>
      <button class="toast-close" aria-label="Close">${closeIcon}</button>
    `;

    const closeBtn = toast.querySelector('.toast-close');
    closeBtn.addEventListener('click', () => this.hide(toast));

    this.container.appendChild(toast);

    // Auto hide after duration
    if (duration > 0) {
      setTimeout(() => this.hide(toast), duration);
    }

    return toast;
  }

  static hide(toast) {
    toast.classList.add('hiding');
    setTimeout(() => {
      if (toast.parentNode) {
        toast.parentNode.removeChild(toast);
      }
    }, 300);
  }

  static getIcon(type) {
    const icons = {
      success: `
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
      `,
      error: `
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
      `,
      warning: `
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
        </svg>
      `,
      info: `
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
        </svg>
      `
    };
    return icons[type] || icons.info;
  }

  static success(message, title = null, duration = 5000) {
    return this.show(message, 'success', title, duration);
  }

  static error(message, title = null, duration = 5000) {
    return this.show(message, 'error', title, duration);
  }

  static warning(message, title = null, duration = 5000) {
    return this.show(message, 'warning', title, duration);
  }

  static info(message, title = null, duration = 5000) {
    return this.show(message, 'info', title, duration);
  }
}

// Modal System
class Modal {
  static open(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
      const backdrop = modal.closest('.modal-backdrop') || modal;
      backdrop.classList.add('show');
      document.body.style.overflow = 'hidden';
    }
  }

  static close(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
      const backdrop = modal.closest('.modal-backdrop') || modal;
      backdrop.classList.remove('show');
      document.body.style.overflow = '';
    }
  }

  static init() {
    // Close modal on backdrop click
    document.addEventListener('click', (e) => {
      if (e.target.classList.contains('modal-backdrop')) {
        const modal = e.target.querySelector('.modal');
        if (modal) {
          const modalId = modal.id;
          this.close(modalId);
        }
      }
    });

    // Close modal on close button click
    document.querySelectorAll('.modal-close').forEach(btn => {
      btn.addEventListener('click', (e) => {
        const modal = e.target.closest('.modal-backdrop') || e.target.closest('.modal');
        if (modal) {
          const modalId = modal.id || modal.querySelector('.modal').id;
          this.close(modalId);
        }
      });
    });

    // Close modal on Escape key
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        const openModal = document.querySelector('.modal-backdrop.show');
        if (openModal) {
          const modal = openModal.querySelector('.modal');
          if (modal) {
            this.close(modal.id);
          }
        }
      }
    });
  }
}

// Form Validation
class FormValidator {
  static validate(form) {
    const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
    let isValid = true;

    inputs.forEach(input => {
      if (!this.validateField(input)) {
        isValid = false;
      }
    });

    return isValid;
  }

  static validateField(field) {
    const value = field.value.trim();
    let isValid = true;

    // Remove previous error states
    field.classList.remove('error');
    const errorMsg = field.parentElement.querySelector('.form-error');
    if (errorMsg) {
      errorMsg.remove();
    }

    // Required validation
    if (field.hasAttribute('required') && !value) {
      this.showError(field, 'This field is required');
      isValid = false;
    }

    // Email validation
    if (field.type === 'email' && value) {
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(value)) {
        this.showError(field, 'Please enter a valid email address');
        isValid = false;
      }
    }

    // Password validation
    if (field.type === 'password' && value) {
      if (value.length < 8) {
        this.showError(field, 'Password must be at least 8 characters');
        isValid = false;
      }
    }

    // Password confirmation
    if (field.hasAttribute('data-confirm')) {
      const confirmField = document.querySelector(`[name="${field.getAttribute('data-confirm')}"]`);
      if (confirmField && value !== confirmField.value) {
        this.showError(field, 'Passwords do not match');
        isValid = false;
      }
    }

    return isValid;
  }

  static showError(field, message) {
    field.classList.add('error');

    // Get the form-group container
    const formGroup = field.closest('.form-group') || field.parentElement;

    // Remove ALL existing error messages in this form-group to prevent stacking
    const existingErrors = formGroup.querySelectorAll('.form-error');
    existingErrors.forEach(error => error.remove());

    // Create and add new error message
    const errorDiv = document.createElement('div');
    errorDiv.className = 'form-error';
    errorDiv.textContent = message;
    formGroup.appendChild(errorDiv);
  }

  static init() {
    // Real-time validation
    document.querySelectorAll('input, select, textarea').forEach(field => {
      field.addEventListener('blur', () => {
        this.validateField(field);
      });

      field.addEventListener('input', () => {
        if (field.classList.contains('error')) {
          this.validateField(field);
        }
      });
    });
  }
}

// Password Toggle
function initPasswordToggle() {
  document.querySelectorAll('.password-toggle').forEach(toggle => {
    toggle.addEventListener('click', (e) => {
      const input = e.target.closest('.input-group')?.querySelector('input[type="password"], input[type="text"]');
      if (input) {
        const isPassword = input.type === 'password';
        input.type = isPassword ? 'text' : 'password';

        const icon = toggle.querySelector('svg');
        if (icon) {
          if (isPassword) {
            icon.innerHTML = `
              <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 01-4.243-4.243m4.242 4.242L9.88 9.88" />
            `;
          } else {
            icon.innerHTML = `
              <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            `;
          }
        }
      }
    });
  });
}

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
  Toast.init();
  Modal.init();
  FormValidator.init();
  initPasswordToggle();
});

// Export for use in other scripts
window.Toast = Toast;
window.Modal = Modal;
window.FormValidator = FormValidator;

