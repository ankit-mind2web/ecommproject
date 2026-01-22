<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>

<div class="admin-page-header">
    <div class="admin-page-header__info">
        <a href="<?= base_url('/admin/products') ?>" class="admin-back-link">
            <i class="fas fa-arrow-left"></i> Back to Products
        </a>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card__header">
        <h3 class="admin-card__title"><?= $isEdit ? 'Edit Product' : 'Add New Product' ?></h3>
    </div>
    <div class="admin-card__body">
        <form id="productForm" action="<?= $isEdit ? base_url('/admin/products/update/' . $product['id']) : base_url('/admin/products/store') ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
            <div class="form-row">
                <div class="form-group form-group--half">
                    <label for="name" class="form-label">Product Name <span class="required">*</span></label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        class="form-input <?= session()->getFlashdata('errors.name') ? 'form-input--error' : '' ?>"
                        value="<?= old('name', $product['name'] ?? '') ?>"
                        placeholder="Enter product name"
                        required
                    >
                    <?php if ($errors = session()->getFlashdata('errors')): ?>
                        <?php if (isset($errors['name'])): ?>
                            <span class="form-error"><?= $errors['name'] ?></span>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
                
                <div class="form-group form-group--half">
                    <label for="category_id" class="form-label">Category</label>
                    <select id="category_id" name="category_id" class="form-input">
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id'] ?>" <?= old('category_id', $product['category_id'] ?? '') == $category['id'] ? 'selected' : '' ?>>
                                <?= esc($category['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label for="description" class="form-label">Description</label>
                <textarea 
                    id="description" 
                    name="description" 
                    class="form-input form-textarea"
                    rows="4"
                    placeholder="Enter product description"
                ><?= old('description', $product['description'] ?? '') ?></textarea>
            </div>
            
            <div class="form-group">
                <label class="form-label">Product Images</label>
                <div id="imageUploadContainer">
                    <div class="image-upload-item">
                        <input 
                            type="file" 
                            name="product_images[]" 
                            class="form-input form-file"
                            accept="image/jpeg,image/png,image/jpg,image/webp"
                        >
                    </div>
                </div>
                <button type="button" id="addImageField" class="btn btn--secondary btn--sm" style="margin-top: 10px;">
                    <i class="fas fa-plus"></i> Add Another Image
                </button>
                <p class="form-hint">Accepted formats: JPG, PNG, WEBP. Max size: 2MB per image. First image will be the primary/featured image.</p>
                <?php if ($errors = session()->getFlashdata('errors')): ?>
                    <?php if (isset($errors['product_images'])): ?>
                        <span class="form-error"><?= $errors['product_images'] ?></span>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <?php if ($isEdit && !empty($existingImages)): ?>
                <div class="form-group">
                    <label class="form-label">Existing Images</label>
                    <div class="existing-images-grid" id="existingImagesContainer">
                        <?php foreach ($existingImages as $index => $image): ?>
                            <div class="existing-image-item" data-image-id="<?= $image['id'] ?>">
                                <img src="<?= base_url($image['image_url']) ?>" alt="Product Image">
                                <?php if ($index === 0): ?>
                                    <span class="primary-badge">Primary</span>
                                <?php endif; ?>
                                <button type="button" class="delete-image-btn" data-image-id="<?= $image['id'] ?>" data-image-url="<?= $image['image_url'] ?>">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <div class="form-row">
                <div class="form-group form-group--third">
                    <label for="price" class="form-label">Price (₹) <span class="required">*</span></label>
                    <input 
                        type="number" 
                        id="price" 
                        name="price" 
                        class="form-input <?= session()->getFlashdata('errors.price') ? 'form-input--error' : '' ?>"
                        value="<?= old('price', $product['price'] ?? '') ?>"
                        placeholder="0.00"
                        step="0.01"
                        min="0"
                        required
                    >
                    <?php if ($errors = session()->getFlashdata('errors')): ?>
                        <?php if (isset($errors['price'])): ?>
                            <span class="form-error"><?= $errors['price'] ?></span>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
                
                <div class="form-group form-group--third">
                    <label for="stock" class="form-label">Stock Quantity <span class="required">*</span></label>
                    <input 
                        type="number" 
                        id="stock" 
                        name="stock" 
                        class="form-input <?= session()->getFlashdata('errors.stock') ? 'form-input--error' : '' ?>"
                        value="<?= old('stock', $product['stock'] ?? 0) ?>"
                        placeholder="0"
                        min="0"
                        required
                    >
                    <?php if ($errors = session()->getFlashdata('errors')): ?>
                        <?php if (isset($errors['stock'])): ?>
                            <span class="form-error"><?= $errors['stock'] ?></span>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
                
                <div class="form-group form-group--third">
                    <label for="status" class="form-label">Status <span class="required">*</span></label>
                    <select id="status" name="status" class="form-input" required>
                        <option value="active" <?= old('status', $product['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Active</option>
                        <option value="inactive" <?= old('status', $product['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>
            </div>
            
            <div class="form-actions">
                <a href="<?= base_url('/admin/products') ?>" class="btn btn--secondary">Cancel</a>
                <button type="submit" class="btn btn--primary">
                    <?= $isEdit ? 'Update Product' : 'Create Product' ?>
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
