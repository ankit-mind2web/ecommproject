<?php


namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\CategoryModel;
use App\Models\ProductImageModel;

class ProductController extends BaseController
{
    protected ProductModel $productModel;
    protected CategoryModel $categoryModel;
    protected ProductImageModel $imageModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->categoryModel = new CategoryModel();
        $this->imageModel = new ProductImageModel();
    }

    /**
     * Display products list
     */
    public function index()
    {
        $products = $this->productModel->getProductsWithCategory();

        return view('admin/products/index', [
            'title'     => 'Products',
            'pageTitle' => 'Products',
            'products'  => $products,
            'extraJs'   => ['admin-products.js']
        ]);
    }

    /**
     * Show create product form
     */
    public function create()
    {
        $categories = $this->categoryModel->getAllActive();

        return view('admin/products/form', [
            'title'      => 'Add Product',
            'pageTitle'  => 'Add Product',
            'categories' => $categories,
            'product'    => null,
            'isEdit'     => false,
            'extraJs'    => ['admin-products.js']
        ]);
    }

    /**
     * Store new product
     */
    public function store()
    {
        $rules = [
            'name'        => 'required|min_length[2]|max_length[255]',
            'price'       => 'required|decimal|greater_than[0]',
            'stock'       => 'required|integer|greater_than_equal_to[0]',
            'category_id' => 'permit_empty|integer',
            'status'      => 'required|in_list[active,inactive]'
        ];

        $messages = [
            'name' => [
                'required'   => 'Product name is required',
                'min_length' => 'Name must be at least 2 characters',
                'max_length' => 'Name must not exceed 255 characters'
            ],
            'price' => [
                'required'     => 'Price is required',
                'decimal'      => 'Price must be a valid number',
                'greater_than' => 'Price must be greater than 0'
            ],
            'stock' => [
                'required'              => 'Stock is required',
                'integer'               => 'Stock must be a whole number',
                'greater_than_equal_to' => 'Stock cannot be negative'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }

        // Handle multiple image uploads
        $imageFiles = $this->request->getFileMultiple('product_images');
        $uploadedImages = [];

        if ($imageFiles) {
            foreach ($imageFiles as $imageFile) {
                if ($imageFile->isValid() && !$imageFile->hasMoved()) {
                    // Validate each image
                    $validation = \Config\Services::validation();
                    $validation->setRules([
                        'uploaded_file' => 'max_size[uploaded_file,2048]|is_image[uploaded_file]'
                    ]);

                    if (!$validation->run(['uploaded_file' => $imageFile])) {
                        return redirect()->back()
                                       ->withInput()
                                       ->with('error', 'One or more images failed validation. Max size: 2MB. Only image files allowed.');
                    }

                    // Generate unique filename and move
                    $newName = $imageFile->getRandomName();
                    if ($imageFile->move(FCPATH . 'uploads/products', $newName)) {
                        $uploadedImages[] = 'uploads/products/' . $newName;
                    }
                }
            }
        }

        $data = [
            'name'        => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'category_id' => $this->request->getPost('category_id') ?: null,
            'price'       => $this->request->getPost('price'),
            'stock'       => $this->request->getPost('stock'),
            'status'      => $this->request->getPost('status')
        ];

        $productId = $this->productModel->createProduct($data);
        
        if ($productId) {
            // Save all uploaded images
            foreach ($uploadedImages as $imagePath) {
                $this->imageModel->addImage($productId, $imagePath);
            }
            
            return redirect()->to('/admin/products')
                           ->with('success', 'Product created successfully');
        }

        return redirect()->back()
                       ->withInput()
                       ->with('error', 'Failed to create product');
    }

    /**
     * Show edit product form
     */
    public function edit($id)
    {
        $product = $this->productModel->find($id);

        if (!$product) {
            return redirect()->to('/admin/products')
                           ->with('error', 'Product not found');
        }

        $categories = $this->categoryModel->getAllActive();
        $existingImages = $this->imageModel->getProductImages($id);

        return view('admin/products/form', [
            'title'          => 'Edit Product',
            'pageTitle'      => 'Edit Product',
            'categories'     => $categories,
            'product'        => $product,
            'existingImages' => $existingImages,
            'isEdit'         => true,
            'extraJs'        => ['admin-products.js']
        ]);
    }

    /**
     * Update product
     */
    public function update($id)
    {
        $product = $this->productModel->find($id);

        if (!$product) {
            return redirect()->to('/admin/products')
                           ->with('error', 'Product not found');
        }

        $rules = [
            'name'        => 'required|min_length[2]|max_length[255]',
            'price'       => 'required|decimal|greater_than[0]',
            'stock'       => 'required|integer|greater_than_equal_to[0]',
            'category_id' => 'permit_empty|integer',
            'status'      => 'required|in_list[active,inactive]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }

        // Handle multiple image uploads
        $imageFiles = $this->request->getFileMultiple('product_images');
        $uploadedImages = [];

        if ($imageFiles) {
            foreach ($imageFiles as $imageFile) {
                if ($imageFile->isValid() && !$imageFile->hasMoved()) {
                    // Validate each image
                    $validation = \Config\Services::validation();
                    $validation->setRules([
                        'uploaded_file' => 'max_size[uploaded_file,2048]|is_image[uploaded_file]'
                    ]);

                    if (!$validation->run(['uploaded_file' => $imageFile])) {
                        return redirect()->back()
                                       ->withInput()
                                       ->with('error', 'One or more images failed validation. Max size: 2MB. Only image files allowed.');
                    }

                    // Generate unique filename and move
                    $newName = $imageFile->getRandomName();
                    if ($imageFile->move(FCPATH . 'uploads/products', $newName)) {
                        $uploadedImages[] = 'uploads/products/' . $newName;
                    }
                }
            }
        }

        $data = [
            'name'        => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'category_id' => $this->request->getPost('category_id') ?: null,
            'price'       => $this->request->getPost('price'),
            'stock'       => $this->request->getPost('stock'),
            'status'      => $this->request->getPost('status')
        ];

        if ($this->productModel->updateProduct($id, $data)) {
            // Save all uploaded images
            foreach ($uploadedImages as $imagePath) {
                $this->imageModel->addImage($id, $imagePath);
            }
            
            return redirect()->to('/admin/products')
                           ->with('success', 'Product updated successfully');
        }

        return redirect()->back()
                       ->withInput()
                       ->with('error', 'Failed to update product');
    }

    /**
     * Delete product image (AJAX)
     */
    public function deleteImage($imageId)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)
                                 ->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $image = $this->imageModel->find($imageId);

        if (!$image) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Image not found'
            ]);
        }

        // Delete physical file
        $filePath = FCPATH . $image['image_url'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Delete from database
        if ($this->imageModel->deleteImage($imageId)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Image deleted successfully'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to delete image'
        ]);
    }

    /**
     * Delete product (AJAX)
     */
    public function delete($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)
                                 ->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $product = $this->productModel->find($id);

        if (!$product) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Product not found'
            ]);
        }

        if ($this->productModel->deleteProduct($id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Product deleted successfully'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to delete product'
        ]);
    }
}
