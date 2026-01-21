/**
 * Authentication JavaScript
 * Handles login and signup form interactions
 */

document.addEventListener('DOMContentLoaded', function () {
    // Initialize password toggles
    initPasswordToggles();

    // Initialize login form if present
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        initLoginForm(loginForm);
    }

    // Initialize signup form if present
    const signupForm = document.getElementById('signupForm');
    if (signupForm) {
        initSignupForm(signupForm);
    }

    // Initialize forgot password form if present
    const forgotPasswordForm = document.getElementById('forgotPasswordForm');
    if (forgotPasswordForm) {
        initForgotPasswordForm(forgotPasswordForm);
    }

    // Initialize reset password form if present
    const resetPasswordForm = document.getElementById('resetPasswordForm');
    if (resetPasswordForm) {
        initResetPasswordForm(resetPasswordForm);
    }
});

/**
 * Initialize password visibility toggles
 */
function initPasswordToggles() {
    document.querySelectorAll('.password-toggle__btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const input = this.parentElement.querySelector('input');
            const icon = this.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });
}

/**
 * Initialize login form
 * @param {HTMLFormElement} form 
 */
function initLoginForm(form) {
    const emailInput = form.querySelector('[name="email"]');
    const passwordInput = form.querySelector('[name="password"]');
    const submitBtn = form.querySelector('button[type="submit"]');

    // Real-time validation on blur
    emailInput.addEventListener('blur', function () {
        const result = Validator.email(this.value);
        if (!result.isValid) {
            FormHelper.showError(this, result.message);
        } else {
            FormHelper.clearError(this);
        }
    });

    passwordInput.addEventListener('blur', function () {
        const result = Validator.required(this.value, 'Password');
        if (!result.isValid) {
            FormHelper.showError(this, result.message);
        } else {
            FormHelper.clearError(this);
        }
    });

    // Clear errors on input
    [emailInput, passwordInput].forEach(function (input) {
        input.addEventListener('input', function () {
            FormHelper.clearError(this);
        });
    });

    // Form submission
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        FormHelper.clearAllErrors(form);

        let isValid = true;

        // Validate email
        const emailResult = Validator.email(emailInput.value);
        if (!emailResult.isValid) {
            FormHelper.showError(emailInput, emailResult.message);
            isValid = false;
        }

        // Validate password
        const passwordResult = Validator.required(passwordInput.value, 'Password');
        if (!passwordResult.isValid) {
            FormHelper.showError(passwordInput, passwordResult.message);
            isValid = false;
        }

        if (!isValid) {
            return;
        }

        // Submit form
        FormHelper.setLoading(submitBtn, true);
        form.submit();
    });
}

/**
 * Initialize signup form
 * @param {HTMLFormElement} form 
 */
function initSignupForm(form) {
    const nameInput = form.querySelector('[name="name"]');
    const emailInput = form.querySelector('[name="email"]');
    const passwordInput = form.querySelector('[name="password"]');
    const confirmPasswordInput = form.querySelector('[name="confirm_password"]');
    const submitBtn = form.querySelector('button[type="submit"]');

    // Real-time validation on blur
    nameInput.addEventListener('blur', function () {
        const result = Validator.name(this.value);
        if (!result.isValid) {
            FormHelper.showError(this, result.message);
        } else {
            FormHelper.clearError(this);
        }
    });

    emailInput.addEventListener('blur', function () {
        const result = Validator.email(this.value);
        if (!result.isValid) {
            FormHelper.showError(this, result.message);
        } else {
            FormHelper.clearError(this);
        }
    });

    passwordInput.addEventListener('blur', function () {
        const result = Validator.password(this.value);
        if (!result.isValid) {
            FormHelper.showError(this, result.message);
        } else {
            FormHelper.clearError(this);
        }
    });

    confirmPasswordInput.addEventListener('blur', function () {
        const result = Validator.confirmPassword(passwordInput.value, this.value);
        if (!result.isValid) {
            FormHelper.showError(this, result.message);
        } else {
            FormHelper.clearError(this);
        }
    });

    // Clear errors on input
    [nameInput, emailInput, passwordInput, confirmPasswordInput].forEach(function (input) {
        input.addEventListener('input', function () {
            FormHelper.clearError(this);
        });
    });

    // Form submission
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        FormHelper.clearAllErrors(form);

        let isValid = true;

        // Validate name
        const nameResult = Validator.name(nameInput.value);
        if (!nameResult.isValid) {
            FormHelper.showError(nameInput, nameResult.message);
            isValid = false;
        }

        // Validate email
        const emailResult = Validator.email(emailInput.value);
        if (!emailResult.isValid) {
            FormHelper.showError(emailInput, emailResult.message);
            isValid = false;
        }

        // Validate password
        const passwordResult = Validator.password(passwordInput.value);
        if (!passwordResult.isValid) {
            FormHelper.showError(passwordInput, passwordResult.message);
            isValid = false;
        }

        // Validate confirm password
        const confirmResult = Validator.confirmPassword(passwordInput.value, confirmPasswordInput.value);
        if (!confirmResult.isValid) {
            FormHelper.showError(confirmPasswordInput, confirmResult.message);
            isValid = false;
        }

        if (!isValid) {
            return;
        }

        // Submit form
        FormHelper.setLoading(submitBtn, true);
        form.submit();
    });
}

/**
 * Initialize forgot password form
 * @param {HTMLFormElement} form 
 */
function initForgotPasswordForm(form) {
    const emailInput = form.querySelector('[name="email"]');
    const submitBtn = form.querySelector('button[type="submit"]');

    // Real-time validation on blur
    emailInput.addEventListener('blur', function () {
        const result = Validator.email(this.value);
        if (!result.isValid) {
            FormHelper.showError(this, result.message);
        } else {
            FormHelper.clearError(this);
        }
    });

    // Clear errors on input
    emailInput.addEventListener('input', function () {
        FormHelper.clearError(this);
    });

    // Form submission
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        FormHelper.clearAllErrors(form);

        // Validate email
        const emailResult = Validator.email(emailInput.value);
        if (!emailResult.isValid) {
            FormHelper.showError(emailInput, emailResult.message);
            return;
        }

        // Submit form with loading state
        FormHelper.setLoading(submitBtn, true);
        form.submit();
    });
}

/**
 * Initialize reset password form
 * @param {HTMLFormElement} form 
 */
function initResetPasswordForm(form) {
    const passwordInput = form.querySelector('[name="password"]');
    const confirmPasswordInput = form.querySelector('[name="confirm_password"]');
    const submitBtn = form.querySelector('button[type="submit"]');

    // Real-time validation on blur
    passwordInput.addEventListener('blur', function () {
        const result = Validator.password(this.value);
        if (!result.isValid) {
            FormHelper.showError(this, result.message);
        } else {
            FormHelper.clearError(this);
        }
    });

    confirmPasswordInput.addEventListener('blur', function () {
        const result = Validator.confirmPassword(passwordInput.value, this.value);
        if (!result.isValid) {
            FormHelper.showError(this, result.message);
        } else {
            FormHelper.clearError(this);
        }
    });

    // Clear errors on input
    [passwordInput, confirmPasswordInput].forEach(function (input) {
        input.addEventListener('input', function () {
            FormHelper.clearError(this);
        });
    });

    // Form submission
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        FormHelper.clearAllErrors(form);

        let isValid = true;

        // Validate password
        const passwordResult = Validator.password(passwordInput.value);
        if (!passwordResult.isValid) {
            FormHelper.showError(passwordInput, passwordResult.message);
            isValid = false;
        }

        // Validate confirm password
        const confirmResult = Validator.confirmPassword(passwordInput.value, confirmPasswordInput.value);
        if (!confirmResult.isValid) {
            FormHelper.showError(confirmPasswordInput, confirmResult.message);
            isValid = false;
        }

        if (!isValid) {
            return;
        }

        // Submit form with loading state
        FormHelper.setLoading(submitBtn, true);
        form.submit();
    });
}
