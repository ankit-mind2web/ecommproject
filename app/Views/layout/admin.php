<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="FashionHub Admin Panel">
    <title><?= esc($title ?? 'Dashboard') ?> | Admin - FashionHub</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Main CSS -->
    <link rel="stylesheet" href="<?= base_url('css/main.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/admin.css') ?>">
    
    <!-- Page Specific CSS -->
    <?php if (isset($extraCss)): ?>
        <?php foreach ($extraCss as $css): ?>
            <link rel="stylesheet" href="<?= base_url('css/' . $css) ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="admin-sidebar__header">
                <a href="<?= base_url('/admin/dashboard') ?>" class="admin-sidebar__logo">
                    <i class="fas fa-tshirt"></i>
                    <span>FashionHub</span>
                </a>
            </div>
            
            <nav class="admin-sidebar__nav">
                <div class="admin-sidebar__section">Main</div>
                <a href="<?= base_url('/admin/dashboard') ?>" class="admin-sidebar__link <?= uri_string() === 'admin/dashboard' ? 'admin-sidebar__link--active' : '' ?>">
                    <i class="fas fa-th-large"></i>
                    <span>Dashboard</span>
                </a>
                
                <div class="admin-sidebar__section">Catalog</div>
                <a href="<?= base_url('/admin/categories') ?>" class="admin-sidebar__link <?= str_starts_with(uri_string(), 'admin/categories') ? 'admin-sidebar__link--active' : '' ?>">
                    <i class="fas fa-folder"></i>
                    <span>Categories</span>
                </a>
                <a href="<?= base_url('/admin/products') ?>" class="admin-sidebar__link <?= str_starts_with(uri_string(), 'admin/products') ? 'admin-sidebar__link--active' : '' ?>">
                    <i class="fas fa-box"></i>
                    <span>Products</span>
                </a>
                
                <div class="admin-sidebar__section">Sales</div>
                <a href="<?= base_url('/admin/orders') ?>" class="admin-sidebar__link <?= str_starts_with(uri_string(), 'admin/orders') ? 'admin-sidebar__link--active' : '' ?>">
                    <i class="fas fa-shopping-bag"></i>
                    <span>Orders</span>
                </a>
                <a href="<?= base_url('/admin/coupons') ?>" class="admin-sidebar__link <?= str_starts_with(uri_string(), 'admin/coupons') ? 'admin-sidebar__link--active' : '' ?>">
                    <i class="fas fa-ticket-alt"></i>
                    <span>Coupons</span>
                </a>
                
                <div class="admin-sidebar__section">Users</div>
                <a href="<?= base_url('/admin/users') ?>" class="admin-sidebar__link <?= str_starts_with(uri_string(), 'admin/users') ? 'admin-sidebar__link--active' : '' ?>">
                    <i class="fas fa-users"></i>
                    <span>Customers</span>
                </a>
                <a href="<?= base_url('/admin/reviews') ?>" class="admin-sidebar__link <?= str_starts_with(uri_string(), 'admin/reviews') ? 'admin-sidebar__link--active' : '' ?>">
                    <i class="fas fa-star"></i>
                    <span>Reviews</span>
                </a>
                
                <div class="admin-sidebar__section">Settings</div>
                <a href="<?= base_url('/logout') ?>" class="admin-sidebar__link">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <main class="admin-main">
            <!-- Top Bar -->
            <header class="admin-topbar">
                <h1 class="admin-topbar__title"><?= esc($pageTitle ?? 'Dashboard') ?></h1>
                
                <div class="admin-topbar__actions">
                    <div class="admin-topbar__user">
                        <div class="admin-topbar__avatar">
                            <?= strtoupper(substr(session()->get('user_name') ?? 'A', 0, 1)) ?>
                        </div>
                        <span class="admin-topbar__name"><?= esc(session()->get('user_name') ?? 'Admin') ?></span>
                    </div>
                </div>
            </header>
            
            <!-- Content Area -->
            <div class="admin-content">
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
                
                <?= $this->renderSection('content') ?>
            </div>
        </main>
    </div>
    
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
