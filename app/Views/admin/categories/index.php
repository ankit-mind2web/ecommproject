<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>

<div class="admin-page-header">
    <div class="admin-page-header__info">
        <h2 class="admin-page-header__title">All Categories</h2>
        <p class="admin-page-header__count"><?= count($categories) ?> categories found</p>
    </div>
    <a href="<?= base_url('/admin/categories/create') ?>" class="btn btn--primary">
        <i class="fas fa-plus"></i> Add Category
    </a>
</div>

<div class="admin-card">
    <?php if (empty($categories)): ?>
        <div class="admin-empty">
            <i class="fas fa-folder-open"></i>
            <p>No categories found</p>
            <a href="<?= base_url('/admin/categories/create') ?>" class="btn btn--primary btn--sm">Add Your First Category</a>
        </div>
    <?php else: ?>
        <div class="admin-table-wrapper">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Products</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $category): ?>
                        <tr data-id="<?= $category['id'] ?>">
                            <td>#<?= $category['id'] ?></td>
                            <td>
                                <span class="admin-table__category-name"><?= esc($category['name']) ?></span>
                            </td>
                            <td>
                                <span class="badge badge--info"><?= $category['product_count'] ?></span>
                            </td>
                            <td>
                                <span class="badge badge--<?= $category['status'] === 'active' ? 'success' : 'warning' ?>">
                                    <?= ucfirst($category['status']) ?>
                                </span>
                            </td>
                            <td><?= date('M d, Y', strtotime($category['created_at'])) ?></td>
                            <td>
                                <div class="admin-actions">
                                    <a href="<?= base_url('/admin/categories/edit/' . $category['id']) ?>" class="admin-action admin-action--edit" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="admin-action admin-action--delete" title="Delete" data-id="<?= $category['id'] ?>" data-name="<?= esc($category['name']) ?>" data-products="<?= $category['product_count'] ?>">
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
            <h3 class="modal__title">Delete Category</h3>
            <button type="button" class="modal__close">&times;</button>
        </div>
        <div class="modal__body">
            <p>Are you sure you want to delete <strong id="deleteCategoryName"></strong>?</p>
            <p class="modal__warning">This action cannot be undone.</p>
        </div>
        <div class="modal__footer">
            <button type="button" class="btn btn--secondary" id="cancelDelete">Cancel</button>
            <button type="button" class="btn btn--danger" id="confirmDelete">Delete</button>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
