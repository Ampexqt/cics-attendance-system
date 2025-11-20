/**
 * Login Page Animations and Interactive Effects
 */

document.addEventListener('DOMContentLoaded', () => {
    // Input focus scaling effect
    const inputs = document.querySelectorAll('.form-input');

    inputs.forEach(input => {
        input.addEventListener('focus', function () {
            this.style.transform = 'scale(1.01)';
        });

        input.addEventListener('blur', function () {
            this.style.transform = 'scale(1)';
        });
    });

    // Enhanced password toggle with smooth icon transition
    const passwordToggles = document.querySelectorAll('.password-toggle');

    passwordToggles.forEach(toggle => {
        toggle.addEventListener('click', function (e) {
            e.preventDefault();
            const input = this.closest('.input-group').querySelector('input');
            const isPassword = input.type === 'password';

            // Animate the toggle button
            this.style.transform = 'scale(0.9)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 100);

            // Toggle password visibility
            input.type = isPassword ? 'text' : 'password';

            // Update icon
            const eyeShow = this.querySelector('.eye-icon-show');
            const eyeHide = this.querySelector('.eye-icon-hide');

            if (isPassword) {
                eyeShow.style.display = 'none';
                eyeHide.style.display = 'block';
            } else {
                eyeShow.style.display = 'block';
                eyeHide.style.display = 'none';
            }
        });
    });

    // Button press animation
    const submitButton = document.querySelector('.btn-primary');
    if (submitButton) {
        submitButton.addEventListener('mousedown', function () {
            this.style.transform = 'scale(0.98) translateY(0)';
        });

        submitButton.addEventListener('mouseup', function () {
            this.style.transform = '';
        });

        submitButton.addEventListener('mouseleave', function () {
            this.style.transform = '';
        });
    }

    // Smooth scroll for links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href !== '#') {
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });
    });
});
