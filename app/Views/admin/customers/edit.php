<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>

<div class="admin-page-header">
    <div class="admin-page-header__info">
        <a href="<?= base_url('/admin/users') ?>" class="admin-back-link">
            <i class="fas fa-arrow-left"></i> Back to Customers
        </a>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card__header">
        <h3 class="admin-card__title">Edit Customer</h3>
    </div>
    <div class="admin-card__body">
        <form id="customerForm" action="<?= base_url('/admin/users/update/' . $customer['id']) ?>" method="POST">
            <?= csrf_field() ?>
            
            <div class="form-row">
                <div class="form-group form-group--half">
                    <label for="name" class="form-label">Full Name <span class="required">*</span></label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        class="form-input <?= session()->getFlashdata('errors.name') ? 'form-input--error' : '' ?>"
                        value="<?= old('name', $customer['name']) ?>"
                        required
                    >
                    <?php if ($errors = session()->getFlashdata('errors')): ?>
                        <?php if (isset($errors['name'])): ?>
                            <span class="form-error"><?= $errors['name'] ?></span>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
                
                <div class="form-group form-group--half">
                    <label for="email" class="form-label">Email Address <span class="required">*</span></label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="form-input <?= session()->getFlashdata('errors.email') ? 'form-input--error' : '' ?>"
                        value="<?= old('email', $customer['email']) ?>"
                        required
                    >
                    <?php if ($errors = session()->getFlashdata('errors')): ?>
                        <?php if (isset($errors['email'])): ?>
                            <span class="form-error"><?= $errors['email'] ?></span>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group form-group--half">
                    <label for="status" class="form-label">Status <span class="required">*</span></label>
                    <select id="status" name="status" class="form-input" required>
                        <option value="active" <?= old('status', $customer['status']) === 'active' ? 'selected' : '' ?>>Active</option>
                        <option value="inactive" <?= old('status', $customer['status']) === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>
                
                <div class="form-group form-group--half">
                    <label class="form-label">Email Verification</label>
                    <div class="form-checkbox">
                        <label class="checkbox-label <?= $customer['is_verified'] ? 'checkbox-label--disabled' : '' ?>">
                            <input 
                                type="checkbox" 
                                name="is_verified" 
                                value="1" 
                                <?= old('is_verified', $customer['is_verified']) ? 'checked' : '' ?>
                                <?= $customer['is_verified'] ? 'disabled' : '' ?>
                            >
                            <span>Email is verified</span>
                            <?php if ($customer['is_verified']): ?>
                                <!-- Hidden field to preserve value when disabled -->
                                <input type="hidden" name="is_verified" value="1">
                            <?php endif; ?>
                        </label>
                        <?php if ($customer['is_verified']): ?>
                            <p class="form-hint">Verified status cannot be changed once verified.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="customer-info">
                <p><strong>Role:</strong> <?= ucfirst($customer['role']) ?></p>
                <p><strong>Joined:</strong> <?= date('F d, Y \a\t h:i A', strtotime($customer['created_at'])) ?></p>
            </div>
            
            <div class="form-actions">
                <a href="<?= base_url('/admin/users') ?>" class="btn btn--secondary">Cancel</a>
                <button type="submit" class="btn btn--primary">Update Customer</button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
