<?= $this->extend('layout/auth') ?>

<?= $this->section('content') ?>

<div class="auth-page">
    <div class="auth-card">
        <div class="auth-card__header">
            <div class="auth-card__logo">
                <i class="fas fa-tshirt"></i> FashionHub
            </div>
            <h1 class="auth-card__title">Welcome Back</h1>
            <p class="auth-card__subtitle">Sign in to continue shopping</p>
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
            
            <form id="loginForm" action="<?= base_url('/login') ?>" method="POST" autocomplete="off">
                <?= csrf_field() ?>
                
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="form-input <?= session()->getFlashdata('errors.email') ? 'form-input--error' : '' ?>"
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
                
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="password-toggle">
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="form-input <?= session()->getFlashdata('errors.password') ? 'form-input--error' : '' ?>"
                            placeholder="Enter your password"
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
                </div>
                
                <div class="auth-remember">
                    <label class="auth-remember__checkbox">
                        <input type="checkbox" name="remember">
                        <span>Remember me</span>
                    </label>
                    <a href="<?= base_url('/forgot-password') ?>" class="auth-forgot">Forgot Password?</a>
                </div>
                
                <button type="submit" class="btn btn--primary btn--full btn--lg">
                    Sign In
                </button>
            </form>
        </div>
        
        <div class="auth-card__footer">
            Don't have an account? <a href="<?= base_url('/signup') ?>">Create one</a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
