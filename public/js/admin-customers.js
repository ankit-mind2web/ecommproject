/**
 * Admin Customers JavaScript
 * Handles form validation
 */

document.addEventListener('DOMContentLoaded', function () {
    initCustomerForm();
});

/**
 * Initialize customer form validation
 */
function initCustomerForm() {
    const form = document.getElementById('customerForm');
    if (!form) return;

    const nameInput = form.querySelector('[name="name"]');
    const emailInput = form.querySelector('[name="email"]');

    // Real-time validation on blur
    if (nameInput) {
        nameInput.addEventListener('blur', function () {
            const value = this.value.trim();
            if (value.length < 3) {
                showFieldError(this, 'Name must be at least 3 characters');
            } else if (!/^[a-zA-Z\s]+$/.test(value)) {
                showFieldError(this, 'Name can only contain letters and spaces');
            } else {
                clearFieldError(this);
            }
        });

        nameInput.addEventListener('input', function () {
            clearFieldError(this);
        });
    }

    if (emailInput) {
        emailInput.addEventListener('blur', function () {
            const value = this.value.trim();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                showFieldError(this, 'Please enter a valid email address');
            } else {
                clearFieldError(this);
            }
        });

        emailInput.addEventListener('input', function () {
            clearFieldError(this);
        });
    }
}

/**
 * Show field error
 */
function showFieldError(input, message) {
    clearFieldError(input);
    input.classList.add('form-input--error');
    const error = document.createElement('span');
    error.className = 'form-error';
    error.textContent = message;
    input.parentNode.appendChild(error);
}

/**
 * Clear field error
 */
function clearFieldError(input) {
    input.classList.remove('form-input--error');
    const error = input.parentNode.querySelector('.form-error');
    if (error) {
        error.remove();
    }
}
