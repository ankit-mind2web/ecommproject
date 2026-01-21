<?= $this->extend('layout/auth') ?>

<?= $this->section('content') ?>

<div class="auth-page">
    <div class="auth-card">
        <div class="auth-card__header">
            <div class="auth-card__logo">
                <i class="fas fa-tshirt"></i> FashionHub
            </div>
            <h1 class="auth-card__title">Forgot Password</h1>
            <p class="auth-card__subtitle">Enter your email to receive a reset link</p>
        </div>
        
        <div class="auth-card__body">
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert--success">
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>
            
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert--error">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>
            
            <form id="forgotPasswordForm" action="<?= base_url('/forgot-password') ?>" method="POST" autocomplete="off">
                <?= csrf_field() ?>
                
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="form-input <?= (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['email'])) ? 'form-input--error' : '' ?>"
                        placeholder="Enter your email"
                        value="<?= old('email') ?>"
                        autocomplete="off"
                    >
                    <?php if ($errors = session()->getFlashdata('errors')): ?>
                        <?php if (isset($errors['email'])): ?>
                            <span class="form-error"><?= $errors['email'] ?></span>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
                
                <button type="submit" class="btn btn--primary btn--full btn--lg">
                    Send Reset Link
                </button>
            </form>
        </div>
        
        <div class="auth-card__footer">
            Remember your password? <a href="<?= base_url('/login') ?>">Sign in</a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
