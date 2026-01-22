/**
 * Validation Utilities
 * Reusable validation functions for frontend form validation
 */

const Validator = {
    /**
     * Validate email format
     * @param {string} email 
     * @returns {object} { isValid: boolean, message: string }
     */
    email: function (email) {
        if (!email || email.trim() === '') {
            return { isValid: false, message: 'Email is required' };
        }

        const emailRegex = /^[a-z0-9._-]{1,25}@([a-z0-9-]+\.)+[a-z]{2,4}$/;
        if (!emailRegex.test(email.trim())) {
            return { isValid: false, message: 'Please enter a valid email address' };
        }

        if (email.length > 255) {
            return { isValid: false, message: 'Email must not exceed 255 characters' };
        }

        return { isValid: true, message: '' };
    },

    /**
     * Validate password
     * @param {string} password 
     * @param {object} options - { minLength, requireUppercase, requireLowercase, requireNumber }
     * @returns {object} { isValid: boolean, message: string }
     */
    password: function (password, options = {}) {
        const minLength = options.minLength || 8;
        const requireUppercase = options.requireUppercase !== false;
        const requireLowercase = options.requireLowercase !== false;
        const requireNumber = options.requireNumber !== false;

        if (!password || password === '') {
            return { isValid: false, message: 'Password is required' };
        }

        if (password.length < minLength) {
            return { isValid: false, message: `Password must be at least ${minLength} characters` };
        }

        if (requireUppercase && !/[A-Z]/.test(password)) {
            return { isValid: false, message: 'Password must contain at least one uppercase letter' };
        }

        if (requireLowercase && !/[a-z]/.test(password)) {
            return { isValid: false, message: 'Password must contain at least one lowercase letter' };
        }

        if (requireNumber && !/[0-9]/.test(password)) {
            return { isValid: false, message: 'Password must contain at least one number' };
        }

        return { isValid: true, message: '' };
    },

    /**
     * Validate name (alphabets and spaces only)
     * @param {string} name 
     * @param {object} options - { minLength, maxLength, fieldName }
     * @returns {object} { isValid: boolean, message: string }
     */
    name: function (name, options = {}) {
        const minLength = options.minLength || 3;
        const maxLength = options.maxLength || 100;
        const fieldName = options.fieldName || 'Name';

        if (!name || name.trim() === '') {
            return { isValid: false, message: `${fieldName} is required` };
        }

        const trimmedName = name.trim();

        if (trimmedName.length < minLength) {
            return { isValid: false, message: `${fieldName} must be at least ${minLength} characters` };
        }

        if (trimmedName.length > maxLength) {
            return { isValid: false, message: `${fieldName} must not exceed ${maxLength} characters` };
        }

        if (!/^[a-zA-Z\s]+$/.test(trimmedName)) {
            return { isValid: false, message: `${fieldName} can only contain alphabets ` };
        }

        return { isValid: true, message: '' };
    },

    /**
     * Validate password confirmation
     * @param {string} password 
     * @param {string} confirmPassword 
     * @returns {object} { isValid: boolean, message: string }
     */
    confirmPassword: function (password, confirmPassword) {
        if (!confirmPassword || confirmPassword === '') {
            return { isValid: false, message: 'Please confirm your password' };
        }

        if (password !== confirmPassword) {
            return { isValid: false, message: 'Passwords do not match' };
        }

        return { isValid: true, message: '' };
    },

    /**
     * Validate required field
     * @param {string} value 
     * @param {string} fieldName 
     * @returns {object} { isValid: boolean, message: string }
     */
    required: function (value, fieldName = 'This field') {
        if (!value || (typeof value === 'string' && value.trim() === '')) {
            return { isValid: false, message: `${fieldName} is required` };
        }
        return { isValid: true, message: '' };
    }
};

/**
 * Form Helper Utilities
 */
const FormHelper = {
    /**
     * Show error message for a field
     * @param {HTMLElement} input 
     * @param {string} message 
     */
    showError: function (input, message) {
        input.classList.add('form-input--error');

        // Find or create error element
        let errorEl = input.parentElement.querySelector('.form-error');
        if (!errorEl) {
            errorEl = document.createElement('span');
            errorEl.className = 'form-error';
            input.parentElement.appendChild(errorEl);
        }
        errorEl.textContent = message;
    },

    /**
     * Clear error message for a field
     * @param {HTMLElement} input 
     */
    clearError: function (input) {
        input.classList.remove('form-input--error');
        const errorEl = input.parentElement.querySelector('.form-error');
        if (errorEl) {
            errorEl.textContent = '';
        }
    },

    /**
     * Clear all errors in a form
     * @param {HTMLFormElement} form 
     */
    clearAllErrors: function (form) {
        form.querySelectorAll('.form-input--error').forEach(function (el) {
            el.classList.remove('form-input--error');
        });
        form.querySelectorAll('.form-error').forEach(function (el) {
            el.textContent = '';
        });
    },

    /**
     * Set loading state on button
     * @param {HTMLButtonElement} button 
     * @param {boolean} isLoading 
     */
    setLoading: function (button, isLoading) {
        if (isLoading) {
            button.disabled = true;
            button.classList.add('btn--loading');
            button.dataset.originalText = button.textContent;
        } else {
            button.disabled = false;
            button.classList.remove('btn--loading');
            if (button.dataset.originalText) {
                button.textContent = button.dataset.originalText;
            }
        }
    }
};
