<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <div class="hero__content">
            <h1 class="hero__title">Discover Your Style</h1>
            <p class="hero__subtitle">Explore our latest collection of trendy and affordable clothing for everyone.</p>
            <div class="hero__actions">
                <a href="<?= base_url('/products') ?>" class="btn btn--primary btn--lg">Shop Now</a>
                <a href="<?= base_url('/categories') ?>" class="btn btn--outline btn--lg">Browse Categories</a>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features">
    <div class="container">
        <div class="features__grid">
            <div class="feature-card">
                <div class="feature-card__icon">
                    <i class="fas fa-truck"></i>
                </div>
                <h3 class="feature-card__title">Free Shipping</h3>
                <p class="feature-card__text">Free shipping on orders over ₹999</p>
            </div>
            <div class="feature-card">
                <div class="feature-card__icon">
                    <i class="fas fa-undo"></i>
                </div>
                <h3 class="feature-card__title">Easy Returns</h3>
                <p class="feature-card__text">30-day hassle-free returns</p>
            </div>
            <div class="feature-card">
                <div class="feature-card__icon">
                    <i class="fas fa-lock"></i>
                </div>
                <h3 class="feature-card__title">Secure Payment</h3>
                <p class="feature-card__text">100% secure payment methods</p>
            </div>
            <div class="feature-card">
                <div class="feature-card__icon">
                    <i class="fas fa-headset"></i>
                </div>
                <h3 class="feature-card__title">24/7 Support</h3>
                <p class="feature-card__text">Dedicated customer support</p>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="categories-section">
    <div class="container">
        <h2 class="section-title">Shop by Category</h2>
        <div class="categories-grid">
            <a href="#" class="category-card">
                <div class="category-card__overlay"></div>
                <i class="fas fa-male category-card__icon"></i>
                <h3 class="category-card__title">Men</h3>
            </a>
            <a href="#" class="category-card">
                <div class="category-card__overlay"></div>
                <i class="fas fa-female category-card__icon"></i>
                <h3 class="category-card__title">Women</h3>
            </a>
            <a href="#" class="category-card">
                <div class="category-card__overlay"></div>
                <i class="fas fa-child category-card__icon"></i>
                <h3 class="category-card__title">Kids</h3>
            </a>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
