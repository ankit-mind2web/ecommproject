<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>

<div class="admin-page-header">
    <div class="admin-page-header__info">
        <h2 class="admin-page-header__title">All Products</h2>
        <p class="admin-page-header__count"><?= count($products) ?> products found</p>
    </div>
    <a href="<?= base_url('/admin/products/create') ?>" class="btn btn--primary">
        <i class="fas fa-plus"></i> Add Product
    </a>
</div>

<div class="admin-card">
    <?php if (empty($products)): ?>
        <div class="admin-empty">
            <i class="fas fa-box-open"></i>
            <p>No products found</p>
            <a href="<?= base_url('/admin/products/create') ?>" class="btn btn--primary btn--sm">Add Your First Product</a>
        </div>
    <?php else: ?>
        <div class="admin-table-wrapper">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr data-id="<?= $product['id'] ?>">
                            <td>#<?= $product['id'] ?></td>
                            <td class="admin-table__product">
                                <span class="admin-table__product-name"><?= esc($product['name']) ?></span>
                            </td>
                            <td><?= esc($product['category_name'] ?? 'Uncategorized') ?></td>
                            <td>₹<?= number_format($product['price'], 2) ?></td>
                            <td>
                                <span class="badge <?= $product['stock'] > 0 ? 'badge--success' : 'badge--error' ?>">
                                    <?= $product['stock'] ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge badge--<?= $product['status'] === 'active' ? 'success' : 'warning' ?>">
                                    <?= ucfirst($product['status']) ?>
                                </span>
                            </td>
                            <td>
                                <div class="admin-actions">
                                    <a href="<?= base_url('/admin/products/edit/' . $product['id']) ?>" class="admin-action admin-action--edit" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="admin-action admin-action--delete" title="Delete" data-id="<?= $product['id'] ?>" data-name="<?= esc($product['name']) ?>">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal" id="deleteModal">
    <div class="modal__backdrop"></div>
    <div class="modal__content">
        <div class="modal__header">
            <h3 class="modal__title">Delete Product</h3>
            <button type="button" class="modal__close">&times;</button>
        </div>
        <div class="modal__body">
            <p>Are you sure you want to delete <strong id="deleteProductName"></strong>?</p>
            <p class="modal__warning">This action cannot be undone.</p>
        </div>
        <div class="modal__footer">
            <button type="button" class="btn btn--secondary" id="cancelDelete">Cancel</button>
            <button type="button" class="btn btn--danger" id="confirmDelete">Delete</button>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
