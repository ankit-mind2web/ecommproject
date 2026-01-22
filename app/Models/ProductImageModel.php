<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductImageModel extends Model
{
    protected $table            = 'product_images';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'product_id',
        'image_url'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    /**
     * Get images for a product
     *
     * @param int $productId
     * @return array
     */
    public function getProductImages(int $productId): array
    {
        return $this->where('product_id', $productId)
                    ->orderBy('id', 'ASC')
                    ->findAll();
    }

    /**
     * Get primary (first) image for a product
     *
     * @param int $productId
     * @return array|null
     */
    public function getPrimaryImage(int $productId): ?array
    {
        return $this->where('product_id', $productId)
                    ->orderBy('id', 'ASC')
                    ->first();
    }

    /**
     * Add image to product
     *
     * @param int    $productId
     * @param string $imageUrl
     * @return int|false
     */
    public function addImage(int $productId, string $imageUrl)
    {
        return $this->insert([
            'product_id' => $productId,
            'image_url'  => $imageUrl
        ]);
    }

    /**
     * Delete all images for a product
     *
     * @param int $productId
     * @return bool
     */
    public function deleteProductImages(int $productId): bool
    {
        return $this->where('product_id', $productId)->delete();
    }

    /**
     * Delete a specific image
     *
     * @param int $imageId
     * @return bool
     */
    public function deleteImage(int $imageId): bool
    {
        return $this->delete($imageId);
    }
}
