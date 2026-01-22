<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>

<div class="admin-page-header">
    <div class="admin-page-header__info">
        <a href="<?= base_url('/admin/categories') ?>" class="admin-back-link">
            <i class="fas fa-arrow-left"></i> Back to Categories
        </a>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card__header">
        <h3 class="admin-card__title"><?= $isEdit ? 'Edit Category' : 'Add New Category' ?></h3>
    </div>
    <div class="admin-card__body">
        <form id="categoryForm" action="<?= $isEdit ? base_url('/admin/categories/update/' . $category['id']) : base_url('/admin/categories/store') ?>" method="POST">
            <?= csrf_field() ?>
            
            <div class="form-group">
                <label for="name" class="form-label">Category Name <span class="required">*</span></label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    class="form-input <?= session()->getFlashdata('errors.name') ? 'form-input--error' : '' ?>"
                    value="<?= old('name', $category['name'] ?? '') ?>"
                    placeholder="Enter category name"
                    required
                >
                <?php if ($errors = session()->getFlashdata('errors')): ?>
                    <?php if (isset($errors['name'])): ?>
                        <span class="form-error"><?= $errors['name'] ?></span>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="status" class="form-label">Status <span class="required">*</span></label>
                <select id="status" name="status" class="form-input" required>
                    <option value="active" <?= old('status', $category['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= old('status', $category['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>
            
            <div class="form-actions">
                <a href="<?= base_url('/admin/categories') ?>" class="btn btn--secondary">Cancel</a>
                <button type="submit" class="btn btn--primary">
                    <?= $isEdit ? 'Update Category' : 'Create Category' ?>
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
