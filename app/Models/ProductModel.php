<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table            = 'products';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'category_id',
        'name',
        'description',
        'price',
        'stock',
        'status'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'name'  => 'required|min_length[2]|max_length[255]',
        'price' => 'required|decimal',
        'stock' => 'required|integer|greater_than_equal_to[0]'
    ];

    protected $validationMessages = [
        'name' => [
            'required'   => 'Product name is required',
            'min_length' => 'Product name must be at least 2 characters',
            'max_length' => 'Product name must not exceed 255 characters'
        ],
        'price' => [
            'required' => 'Price is required',
            'decimal'  => 'Price must be a valid number'
        ],
        'stock' => [
            'required'              => 'Stock quantity is required',
            'integer'               => 'Stock must be a whole number',
            'greater_than_equal_to' => 'Stock cannot be negative'
        ]
    ];

    /**
     * Get all products with category names for admin list
     *
     * @return array
     */
    public function getProductsWithCategory(): array
    {
        return $this->select('products.*, categories.name as category_name')
                    ->join('categories', 'categories.id = products.category_id', 'left')
                    ->orderBy('products.created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get single product with category
     *
     * @param int $id
     * @return array|null
     */
    public function getProductWithCategory(int $id): ?array
    {
        return $this->select('products.*, categories.name as category_name')
                    ->join('categories', 'categories.id = products.category_id', 'left')
                    ->where('products.id', $id)
                    ->first();
    }

    /**
     * Create a new product
     *
     * @param array $data
     * @return int|false
     */
    public function createProduct(array $data)
    {
        return $this->insert($data);
    }

    /**
     * Update a product
     *
     * @param int   $id
     * @param array $data
     * @return bool
     */
    public function updateProduct(int $id, array $data): bool
    {
        return $this->update($id, $data);
    }

    /**
     * Delete a product (soft delete)
     *
     * @param int $id
     * @return bool
     */
    public function deleteProduct(int $id): bool
    {
        return $this->delete($id);
    }
}
