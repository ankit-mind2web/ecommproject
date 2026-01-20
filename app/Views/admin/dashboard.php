<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-card__content">
            <p class="stat-card__label">Total Customers</p>
            <p class="stat-card__value"><?= number_format($stats['totalUsers'] ?? 0) ?></p>
            <p class="stat-card__change stat-card__change--up">
                <i class="fas fa-arrow-up"></i> 12% from last month
            </p>
        </div>
        <div class="stat-card__icon stat-card__icon--primary">
            <i class="fas fa-users"></i>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-card__content">
            <p class="stat-card__label">Total Orders</p>
            <p class="stat-card__value"><?= number_format($stats['totalOrders'] ?? 0) ?></p>
            <p class="stat-card__change stat-card__change--up">
                <i class="fas fa-arrow-up"></i> 8% from last month
            </p>
        </div>
        <div class="stat-card__icon stat-card__icon--success">
            <i class="fas fa-shopping-bag"></i>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-card__content">
            <p class="stat-card__label">Total Products</p>
            <p class="stat-card__value"><?= number_format($stats['totalProducts'] ?? 0) ?></p>
            <p class="stat-card__change stat-card__change--up">
                <i class="fas fa-arrow-up"></i> 5 new this week
            </p>
        </div>
        <div class="stat-card__icon stat-card__icon--warning">
            <i class="fas fa-box"></i>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-card__content">
            <p class="stat-card__label">Total Revenue</p>
            <p class="stat-card__value">₹<?= number_format($stats['totalRevenue'] ?? 0, 2) ?></p>
            <p class="stat-card__change stat-card__change--up">
                <i class="fas fa-arrow-up"></i> 15% from last month
            </p>
        </div>
        <div class="stat-card__icon stat-card__icon--info">
            <i class="fas fa-rupee-sign"></i>
        </div>
    </div>
</div>

<!-- Dashboard Grid -->
<div class="dashboard-grid">
    <!-- Recent Orders -->
    <div class="dashboard-card">
        <div class="dashboard-card__header">
            <h3 class="dashboard-card__title">Recent Orders</h3>
            <a href="<?= base_url('/admin/orders') ?>" class="btn btn--secondary btn--sm">View All</a>
        </div>
        <div class="dashboard-card__body">
            <?php if (empty($recentOrders)): ?>
                <p style="text-align: center; color: var(--color-gray-500); padding: var(--spacing-8);">
                    <i class="fas fa-inbox" style="font-size: 2rem; margin-bottom: var(--spacing-3); display: block;"></i>
                    No orders yet
                </p>
            <?php else: ?>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentOrders as $order): ?>
                            <tr>
                                <td>#<?= $order['id'] ?></td>
                                <td><?= esc($order['customer_name']) ?></td>
                                <td>₹<?= number_format($order['total_amount'], 2) ?></td>
                                <td>
                                    <span class="badge badge--<?= $order['status_class'] ?>">
                                        <?= ucfirst($order['order_status']) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="dashboard-card">
        <div class="dashboard-card__header">
            <h3 class="dashboard-card__title">Quick Actions</h3>
        </div>
        <div class="dashboard-card__body">
            <div class="quick-actions">
                <a href="<?= base_url('/admin/products') ?>" class="quick-action">
                    <i class="fas fa-plus"></i>
                    <span>Add Product</span>
                </a>
                <a href="<?= base_url('/admin/categories') ?>" class="quick-action">
                    <i class="fas fa-folder-plus"></i>
                    <span>Add Category</span>
                </a>
                <a href="<?= base_url('/admin/orders') ?>" class="quick-action">
                    <i class="fas fa-clipboard-list"></i>
                    <span>View Orders</span>
                </a>
                <a href="<?= base_url('/admin/coupons') ?>" class="quick-action">
                    <i class="fas fa-percent"></i>
                    <span>Create Coupon</span>
                </a>
                <a href="<?= base_url('/admin/users') ?>" class="quick-action">
                    <i class="fas fa-user-plus"></i>
                    <span>Manage Users</span>
                </a>
                <a href="<?= base_url('/admin/reviews') ?>" class="quick-action">
                    <i class="fas fa-star"></i>
                    <span>Manage Reviews</span>
                </a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
