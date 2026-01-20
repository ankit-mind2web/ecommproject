<?= $this->extend('layout/auth') ?>

<?= $this->section('content') ?>

<div class="auth-page">
    <div class="auth-card">
        <div class="auth-card__header">
            <div class="auth-card__logo">
                <i class="fas fa-tshirt"></i> FashionHub
            </div>
            <h1 class="auth-card__title">Create Account</h1>
            <p class="auth-card__subtitle">Join us for exclusive deals and offers</p>
        </div>
        
        <div class="auth-card__body">
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert--error">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>
            
            <form id="signupForm" action="<?= base_url('/signup') ?>" method="POST" autocomplete="off">
                <?= csrf_field() ?>
                
                <div class="form-group">
                    <label for="name" class="form-label">Full Name</label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        class="form-input <?= (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['name'])) ? 'form-input--error' : '' ?>"
                        placeholder="Enter your full name"
                        value="<?= old('name') ?>"
                        autocomplete="off"
                    >
                    <?php if ($errors = session()->getFlashdata('errors')): ?>
                        <?php if (isset($errors['name'])): ?>
                            <span class="form-error"><?= $errors['name'] ?></span>
                        <?php endif; ?>
                    <?php endif; ?>
                    <span class="form-hint">Only letters and spaces allowed (3-100 characters)</span>
                </div>
                
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
                
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="password-toggle">
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="form-input <?= (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['password'])) ? 'form-input--error' : '' ?>"
                            placeholder="Create a password"
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
                    <span class="form-hint">Minimum 8 characters with uppercase, lowercase, and number</span>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password" class="form-label">Confirm Password</label>
                    <div class="password-toggle">
                        <input 
                            type="password" 
                            id="confirm_password" 
                            name="confirm_password" 
                            class="form-input <?= (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['confirm_password'])) ? 'form-input--error' : '' ?>"
                            placeholder="Confirm your password"
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
                    Create Account
                </button>
            </form>
        </div>
        
        <div class="auth-card__footer">
            Already have an account? <a href="<?= base_url('/login') ?>">Sign in</a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
