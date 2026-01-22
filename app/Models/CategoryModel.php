<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table            = 'categories';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name',
        'status'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    /**
     * Get all active categories
     *
     * @return array
     */
    public function getAllActive(): array
    {
        return $this->where('status', 'active')
                    ->orderBy('name', 'ASC')
                    ->findAll();
    }

    /**
     * Get all categories for admin
     *
     * @return array
     */
    public function getAllForAdmin(): array
    {
        return $this->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Create a new category
     *
     * @param array $data
     * @return int|false
     */
    public function createCategory(array $data)
    {
        return $this->insert($data);
    }

    /**
     * Update a category
     *
     * @param int   $id
     * @param array $data
     * @return bool
     */
    public function updateCategory(int $id, array $data): bool
    {
        return $this->update($id, $data);
    }

    /**
     * Delete a category (soft delete)
     *
     * @param int $id
     * @return bool
     */
    public function deleteCategory(int $id): bool
    {
        return $this->delete($id);
    }

    /**
     * Get product count for a category
     *
     * @param int $categoryId
     * @return int
     */
    public function getProductCount(int $categoryId): int
    {
        $db = \Config\Database::connect();
        return $db->table('products')
                  ->where('category_id', $categoryId)
                  ->where('deleted_at IS NULL')
                  ->countAllResults();
    }
}
