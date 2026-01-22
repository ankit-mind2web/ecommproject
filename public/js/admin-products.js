/**
 * Admin Products JavaScript
 * Handles delete confirmation and form validation
 */

document.addEventListener('DOMContentLoaded', function () {
    // Initialize delete functionality
    initDeleteProduct();

    // Initialize form validation
    initProductForm();

    // Initialize image upload functionality
    initImageUpload();

    // Initialize existing image delete functionality
    initExistingImageDelete();
});

/**
 * Initialize product delete functionality with modal
 */
function initDeleteProduct() {
    const deleteButtons = document.querySelectorAll('.admin-action--delete');
    const modal = document.getElementById('deleteModal');

    if (!modal || deleteButtons.length === 0) return;

    const modalBackdrop = modal.querySelector('.modal__backdrop');
    const closeBtn = modal.querySelector('.modal__close');
    const cancelBtn = document.getElementById('cancelDelete');
    const confirmBtn = document.getElementById('confirmDelete');
    const productNameEl = document.getElementById('deleteProductName');

    let currentProductId = null;

    // Open modal on delete button click
    deleteButtons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            currentProductId = this.dataset.id;
            productNameEl.textContent = this.dataset.name;
            modal.classList.add('modal--open');
        });
    });

    // Close modal functions
    function closeModal() {
        modal.classList.remove('modal--open');
        currentProductId = null;
    }

    modalBackdrop.addEventListener('click', closeModal);
    closeBtn.addEventListener('click', closeModal);
    cancelBtn.addEventListener('click', closeModal);

    // Confirm delete
    confirmBtn.addEventListener('click', function () {
        if (!currentProductId) return;

        confirmBtn.disabled = true;
        confirmBtn.textContent = 'Deleting...';

        // Get CSRF token
        const csrfToken = document.querySelector('input[name="csrf_test_name"]');

        fetch('/admin/products/delete/' + currentProductId, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                csrf_test_name: csrfToken ? csrfToken.value : ''
            })
        })
            .then(function (response) {
                return response.json();
            })
            .then(function (data) {
                if (data.success) {
                    // Remove row from table
                    const row = document.querySelector('tr[data-id="' + currentProductId + '"]');
                    if (row) {
                        row.remove();
                    }
                    closeModal();
                    // Show success message
                    showNotification('Product deleted successfully', 'success');
                } else {
                    showNotification(data.message || 'Failed to delete product', 'error');
                }
            })
            .catch(function () {
                showNotification('Network error. Please try again.', 'error');
            })
            .finally(function () {
                confirmBtn.disabled = false;
                confirmBtn.textContent = 'Delete';
            });
    });
}

/**
 * Initialize product form validation and AJAX submission
 */
function initProductForm() {
    const form = document.getElementById('productForm');
    if (!form) return;

    const nameInput = form.querySelector('[name="name"]');
    const priceInput = form.querySelector('[name="price"]');
    const stockInput = form.querySelector('[name="stock"]');
    const submitBtn = form.querySelector('button[type="submit"]');

    // Real-time validation
    if (nameInput) {
        nameInput.addEventListener('blur', function () {
            if (this.value.trim().length < 2) {
                showFieldError(this, 'Name must be at least 2 characters');
            } else {
                clearFieldError(this);
            }
        });
    }

    if (priceInput) {
        priceInput.addEventListener('blur', function () {
            if (!this.value || parseFloat(this.value) <= 0) {
                showFieldError(this, 'Price must be greater than 0');
            } else {
                clearFieldError(this);
            }
        });
    }

    if (stockInput) {
        stockInput.addEventListener('blur', function () {
            if (this.value === '' || parseInt(this.value) < 0) {
                showFieldError(this, 'Stock cannot be negative');
            } else {
                clearFieldError(this);
            }
        });
    }

    // Handle form submission via AJAX
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        // Disable submit button
        submitBtn.disabled = true;
        submitBtn.textContent = 'Saving...';

        // Create FormData object to handle files
        const formData = new FormData(form);

        // Get form action URL
        const actionUrl = form.getAttribute('action');

        fetch(actionUrl, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(function (response) {
                return response.json();
            })
            .then(function (data) {
                if (data.success) {
                    showNotification(data.message || 'Product saved successfully', 'success');

                    // Redirect to products list after short delay
                    setTimeout(function () {
                        window.location.href = '/admin/products';
                    }, 1500);
                } else {
                    // Show validation errors
                    if (data.errors) {
                        Object.keys(data.errors).forEach(function (field) {
                            const input = form.querySelector('[name="' + field + '"]');
                            if (input) {
                                showFieldError(input, data.errors[field]);
                            }
                        });
                    }
                    showNotification(data.message || 'Failed to save product', 'error');
                    submitBtn.disabled = false;
                    submitBtn.textContent = form.dataset.isEdit === 'true' ? 'Update Product' : 'Create Product';
                }
            })
            .catch(function () {
                showNotification('Network error. Please try again.', 'error');
                submitBtn.disabled = false;
                submitBtn.textContent = form.dataset.isEdit === 'true' ? 'Update Product' : 'Create Product';
            });
    });
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

/**
 * Show notification
 */
function showNotification(message, type) {
    const container = document.querySelector('.admin-content');
    if (!container) return;

    const alert = document.createElement('div');
    alert.className = 'alert alert--' + type;
    alert.textContent = message;
    container.insertBefore(alert, container.firstChild);

    setTimeout(function () {
        alert.remove();
    }, 5000);
}

/**
 * Initialize dynamic image upload functionality
 */
function initImageUpload() {
    const addImageBtn = document.getElementById('addImageField');
    const imageContainer = document.getElementById('imageUploadContainer');

    if (!addImageBtn || !imageContainer) return;

    addImageBtn.addEventListener('click', function () {
        const newField = document.createElement('div');
        newField.className = 'image-upload-item';
        newField.style.marginTop = '10px';
        newField.innerHTML = `
            <input 
                type="file" 
                name="product_images[]" 
                class="form-input form-file"
                accept="image/jpeg,image/png,image/jpg,image/webp"
            >
            <button type="button" class="btn btn--danger btn--sm remove-image-field" style="margin-left: 10px;">
                <i class="fas fa-times"></i> Remove
            </button>
        `;

        imageContainer.appendChild(newField);

        // Add remove functionality
        const removeBtn = newField.querySelector('.remove-image-field');
        removeBtn.addEventListener('click', function () {
            newField.remove();
        });
    });
}

/**
 * Initialize existing image delete functionality
 */
function initExistingImageDelete() {
    const deleteButtons = document.querySelectorAll('.delete-image-btn');

    deleteButtons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            if (!confirm('Are you sure you want to delete this image?')) {
                return;
            }

            const imageId = this.dataset.imageId;
            const imageItem = this.closest('.existing-image-item');

            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            fetch('/admin/products/delete-image/' + imageId, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(function (response) {
                    return response.json();
                })
                .then(function (data) {
                    if (data.success) {
                        imageItem.remove();
                        showNotification('Image deleted successfully', 'success');
                    } else {
                        showNotification(data.message || 'Failed to delete image', 'error');
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fas fa-trash"></i>';
                    }
                })
                .catch(function () {
                    showNotification('Network error. Please try again.', 'error');
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-trash"></i>';
                });
        });
    });
}
