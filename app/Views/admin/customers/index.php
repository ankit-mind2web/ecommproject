<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>

<div class="admin-page-header">
    <div class="admin-page-header__info">
        <h2 class="admin-page-header__title">All Customers</h2>
        <p class="admin-page-header__count"><?= count($customers) ?> customers found</p>
    </div>
</div>

<div class="admin-card">
    <?php if (empty($customers)): ?>
        <div class="admin-empty">
            <i class="fas fa-users"></i>
            <p>No customers found</p>
        </div>
    <?php else: ?>
        <div class="admin-table-wrapper">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Verified</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($customers as $customer): ?>
                        <tr>
                            <td>#<?= $customer['id'] ?></td>
                            <td>
                                <div class="admin-table__user">
                                    <div class="admin-table__avatar">
                                        <?= strtoupper(substr($customer['name'], 0, 1)) ?>
                                    </div>
                                    <span><?= esc($customer['name']) ?></span>
                                </div>
                            </td>
                            <td><?= esc($customer['email']) ?></td>
                            <td>
                                <span class="badge badge--<?= $customer['status'] === 'active' ? 'success' : 'warning' ?>">
                                    <?= ucfirst($customer['status']) ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($customer['is_verified']): ?>
                                    <span class="badge badge--success">
                                        <i class="fas fa-check"></i> Verified
                                    </span>
                                <?php else: ?>
                                    <span class="badge badge--warning">
                                        <i class="fas fa-clock"></i> Pending
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td><?= date('M d, Y', strtotime($customer['created_at'])) ?></td>
                            <td>
                                <div class="admin-actions">
                                    <a href="<?= base_url('/admin/users/edit/' . $customer['id']) ?>" class="admin-action admin-action--edit" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
