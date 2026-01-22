/**
 * Admin Categories JavaScript
 * Handles delete confirmation
 */

document.addEventListener('DOMContentLoaded', function () {
    initDeleteCategory();
});

/**
 * Initialize category delete functionality with modal
 */
function initDeleteCategory() {
    const deleteButtons = document.querySelectorAll('.admin-action--delete');
    const modal = document.getElementById('deleteModal');

    if (!modal || deleteButtons.length === 0) return;

    const modalBackdrop = modal.querySelector('.modal__backdrop');
    const closeBtn = modal.querySelector('.modal__close');
    const cancelBtn = document.getElementById('cancelDelete');
    const confirmBtn = document.getElementById('confirmDelete');
    const categoryNameEl = document.getElementById('deleteCategoryName');

    let currentCategoryId = null;

    // Open modal on delete button click
    deleteButtons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            const productCount = parseInt(this.dataset.products) || 0;

            if (productCount > 0) {
                alert('Cannot delete this category. It has ' + productCount + ' product(s) assigned.');
                return;
            }

            currentCategoryId = this.dataset.id;
            categoryNameEl.textContent = this.dataset.name;
            modal.classList.add('modal--open');
        });
    });

    // Close modal functions
    function closeModal() {
        modal.classList.remove('modal--open');
        currentCategoryId = null;
    }

    modalBackdrop.addEventListener('click', closeModal);
    closeBtn.addEventListener('click', closeModal);
    cancelBtn.addEventListener('click', closeModal);

    // Confirm delete
    confirmBtn.addEventListener('click', function () {
        if (!currentCategoryId) return;

        confirmBtn.disabled = true;
        confirmBtn.textContent = 'Deleting...';

        fetch('/admin/categories/delete/' + currentCategoryId, {
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
                    // Remove row from table
                    const row = document.querySelector('tr[data-id="' + currentCategoryId + '"]');
                    if (row) {
                        row.remove();
                    }
                    closeModal();
                    showNotification('Category deleted successfully', 'success');
                } else {
                    showNotification(data.message || 'Failed to delete category', 'error');
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
