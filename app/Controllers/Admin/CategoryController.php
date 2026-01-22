<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CategoryModel;

class CategoryController extends BaseController
{
    protected CategoryModel $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
    }

    /**
     * Display categories list
     */
    public function index()
    {
        $categories = $this->categoryModel->getAllForAdmin();
        
        // Get product count for each category
        foreach ($categories as &$category) {
            $category['product_count'] = $this->categoryModel->getProductCount($category['id']);
        }

        return view('admin/categories/index', [
            'title'      => 'Categories',
            'pageTitle'  => 'Categories',
            'categories' => $categories,
            'extraJs'    => ['admin-categories.js']
        ]);
    }

    /**
     * Show create category form
     */
    public function create()
    {
        return view('admin/categories/form', [
            'title'     => 'Add Category',
            'pageTitle' => 'Add Category',
            'category'  => null,
            'isEdit'    => false
        ]);
    }

    /**
     * Store new category
     */
    public function store()
    {
        $rules = [
            'name'   => 'required|min_length[2]|max_length[255]',
            'status' => 'required|in_list[active,inactive]'
        ];

        $messages = [
            'name' => [
                'required'   => 'Category name is required',
                'min_length' => 'Name must be at least 2 characters',
                'max_length' => 'Name must not exceed 255 characters'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name'   => $this->request->getPost('name'),
            'status' => $this->request->getPost('status')
        ];

        if ($this->categoryModel->createCategory($data)) {
            return redirect()->to('/admin/categories')
                           ->with('success', 'Category created successfully');
        }

        return redirect()->back()
                       ->withInput()
                       ->with('error', 'Failed to create category');
    }

    /**
     * Show edit category form
     */
    public function edit($id)
    {
        $category = $this->categoryModel->find($id);

        if (!$category) {
            return redirect()->to('/admin/categories')
                           ->with('error', 'Category not found');
        }

        return view('admin/categories/form', [
            'title'    => 'Edit Category',
            'pageTitle' => 'Edit Category',
            'category' => $category,
            'isEdit'   => true
        ]);
    }

    /**
     * Update category
     */
    public function update($id)
    {
        $category = $this->categoryModel->find($id);

        if (!$category) {
            return redirect()->to('/admin/categories')
                           ->with('error', 'Category not found');
        }

        $rules = [
            'name'   => 'required|min_length[2]|max_length[255]',
            'status' => 'required|in_list[active,inactive]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name'   => $this->request->getPost('name'),
            'status' => $this->request->getPost('status')
        ];

        if ($this->categoryModel->updateCategory($id, $data)) {
            return redirect()->to('/admin/categories')
                           ->with('success', 'Category updated successfully');
        }

        return redirect()->back()
                       ->withInput()
                       ->with('error', 'Failed to update category');
    }

    /**
     * Delete category (AJAX)
     */
    public function delete($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)
                                 ->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $category = $this->categoryModel->find($id);

        if (!$category) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Category not found'
            ]);
        }

        // Check if category has products
        $productCount = $this->categoryModel->getProductCount($id);
        if ($productCount > 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => "Cannot delete category. It has {$productCount} product(s) assigned."
            ]);
        }

        if ($this->categoryModel->deleteCategory($id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Category deleted successfully'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to delete category'
        ]);
    }
}
