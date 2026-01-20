<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="FashionHub - Your one-stop destination for trendy clothing">
    <title><?= esc($title ?? 'FashionHub') ?> | FashionHub</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Main CSS -->
    <link rel="stylesheet" href="<?= base_url('css/main.css') ?>">
    
    <!-- Page Specific CSS -->
    <?php if (isset($extraCss)): ?>
        <?php foreach ($extraCss as $css): ?>
            <link rel="stylesheet" href="<?= base_url('css/' . $css) ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container header__container">
            <a href="<?= base_url('/') ?>" class="header__logo">
                <i class="fas fa-tshirt"></i> FashionHub
            </a>
            
            <nav class="header__nav">
                <a href="<?= base_url('/') ?>" class="header__nav-link <?= uri_string() === '' ? 'header__nav-link--active' : '' ?>">Home</a>
                <a href="<?= base_url('/products') ?>" class="header__nav-link">Products</a>
                <a href="<?= base_url('/categories') ?>" class="header__nav-link">Categories</a>
            </nav>
            
            <div class="header__actions">
                <?php if (session()->get('logged_in')): ?>
                    <a href="<?= base_url('/wishlist') ?>" class="btn btn--secondary btn--sm">
                        <i class="fas fa-heart"></i>
                    </a>
                    <a href="<?= base_url('/cart') ?>" class="btn btn--secondary btn--sm">
                        <i class="fas fa-shopping-cart"></i>
                    </a>
                    <a href="<?= base_url('/logout') ?>" class="btn btn--outline btn--sm">Logout</a>
                <?php else: ?>
                    <a href="<?= base_url('/login') ?>" class="btn btn--outline btn--sm">Login</a>
                    <a href="<?= base_url('/signup') ?>" class="btn btn--primary btn--sm">Sign Up</a>
                <?php endif; ?>
            </div>
        </div>
    </header>
    
    <!-- Main Content -->
    <main class="main-content">
        <?= $this->renderSection('content') ?>
    </main>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer__container">
                <div class="footer__section">
                    <h4 class="footer__section-title">FashionHub</h4>
                    <p style="color: var(--color-gray-400); font-size: var(--font-size-sm);">Your one-stop destination for trendy and affordable clothing. Shop the latest styles for men, women, and kids.</p>
                </div>
                
                <div class="footer__section">
                    <h4 class="footer__section-title">Quick Links</h4>
                    <ul class="footer__links">
                        <li><a href="<?= base_url('/') ?>" class="footer__link">Home</a></li>
                        <li><a href="<?= base_url('/products') ?>" class="footer__link">Products</a></li>
                        <li><a href="<?= base_url('/categories') ?>" class="footer__link">Categories</a></li>
                    </ul>
                </div>
                
                <div class="footer__section">
                    <h4 class="footer__section-title">Customer Service</h4>
                    <ul class="footer__links">
                        <li><a href="#" class="footer__link">Contact Us</a></li>
                        <li><a href="#" class="footer__link">FAQs</a></li>
                        <li><a href="#" class="footer__link">Shipping Info</a></li>
                        <li><a href="#" class="footer__link">Returns</a></li>
                    </ul>
                </div>
                
                <div class="footer__section">
                    <h4 class="footer__section-title">Connect With Us</h4>
                    <ul class="footer__links">
                        <li><a href="#" class="footer__link"><i class="fab fa-facebook"></i> Facebook</a></li>
                        <li><a href="#" class="footer__link"><i class="fab fa-instagram"></i> Instagram</a></li>
                        <li><a href="#" class="footer__link"><i class="fab fa-twitter"></i> Twitter</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="footer__bottom">
                <p>&copy; <?= date('Y') ?> FashionHub. All rights reserved.</p>
            </div>
        </div>
    </footer>
    
    <!-- Core JS -->
    <script src="<?= base_url('js/validation.js') ?>"></script>
    
    <!-- Page Specific JS -->
    <?php if (isset($extraJs)): ?>
        <?php foreach ($extraJs as $js): ?>
            <script src="<?= base_url('js/' . $js) ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
