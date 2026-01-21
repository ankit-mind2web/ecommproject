<?= $this->extend('layout/auth') ?>

<?= $this->section('content') ?>

<div class="auth-page">
    <div class="auth-card">
        <div class="auth-card__header">
            <div class="auth-card__logo">
                <i class="fas fa-tshirt"></i> FashionHub
            </div>
            <h1 class="auth-card__title">Reset Password</h1>
            <p class="auth-card__subtitle">Create a new password for your account</p>
        </div>
        
        <div class="auth-card__body">
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert--error">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>
            
            <form id="resetPasswordForm" action="<?= base_url('/reset-password/' . $token) ?>" method="POST" autocomplete="off">
                <?= csrf_field() ?>
                
                <div class="form-group">
                    <label for="password" class="form-label">New Password</label>
                    <div class="password-toggle">
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="form-input <?= (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['password'])) ? 'form-input--error' : '' ?>"
                            placeholder="Enter new password"
                            autocomplete="new-password"
                        >
                        <button type="button" class="password-toggle__btn" aria-label="Toggle password visibility">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <?php if ($errors = session()->getFlashdata('errors')): ?>
                        <?php if (isset($errors['password'])): ?>
                            <span class="form-error"><?= $errors['password'] ?></span>
                        <?php endif; ?>
                    <?php endif; ?>
                    <span class="form-hint">Minimum 8 characters</span>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password" class="form-label">Confirm Password</label>
                    <div class="password-toggle">
                        <input 
                            type="password" 
                            id="confirm_password" 
                            name="confirm_password" 
                            class="form-input <?= (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['confirm_password'])) ? 'form-input--error' : '' ?>"
                            placeholder="Confirm new password"
                            autocomplete="new-password"
                        >
                        <button type="button" class="password-toggle__btn" aria-label="Toggle password visibility">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <?php if ($errors = session()->getFlashdata('errors')): ?>
                        <?php if (isset($errors['confirm_password'])): ?>
                            <span class="form-error"><?= $errors['confirm_password'] ?></span>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
                
                <button type="submit" class="btn btn--primary btn--full btn--lg">
                    Reset Password
                </button>
            </form>
        </div>
        
        <div class="auth-card__footer">
            Remember your password? <a href="<?= base_url('/login') ?>">Sign in</a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
