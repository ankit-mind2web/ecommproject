<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'is_verified'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'name'     => 'required|min_length[3]|max_length[100]|regex_match[/^[a-zA-Z\s]+$/]',
        'email'    => 'required|valid_email|max_length[255]|is_unique[users.email,id,{id}]',
        'password' => 'required|min_length[8]',
    ];

    protected $validationMessages = [
        'name' => [
            'required'    => 'Name is required',
            'min_length'  => 'Name must be at least 3 characters',
            'max_length'  => 'Name must not exceed 100 characters',
            'regex_match' => 'Name can only contain letters and spaces'
        ],
        'email' => [
            'required'    => 'Email is required',
            'valid_email' => 'Please enter a valid email address',
            'max_length'  => 'Email must not exceed 255 characters',
            'is_unique'   => 'This email is already registered'
        ],
        'password' => [
            'required'   => 'Password is required',
            'min_length' => 'Password must be at least 8 characters'
        ]
    ];

    protected $skipValidation = false;

    /**
     * Find user by email
     *
     * @param string $email
     * @return array|null
     */
    public function findByEmail(string $email): ?array
    {
        return $this->where('email', $email)->first();
    }

    /**
     * Find user by email including soft-deleted users
     *
     * @param string $email
     * @return array|null
     */
    public function findByEmailWithDeleted(string $email): ?array
    {
        return $this->withDeleted()->where('email', $email)->first();
    }

    /**
     * Find active user by email
     *
     * @param string $email
     * @return array|null
     */
    public function findActiveByEmail(string $email): ?array
    {
        return $this->where('email', $email)
                    ->where('status', 'active')
                    ->first();
    }

    /**
     * Check if email exists
     *
     * @param string $email
     * @param int|null $excludeId
     * @return bool
     */
    public function emailExists(string $email, ?int $excludeId = null): bool
    {
        $builder = $this->where('email', $email);
        
        if ($excludeId) {
            $builder->where('id !=', $excludeId);
        }
        
        return $builder->countAllResults() > 0;
    }

    /**
     * Create new user with hashed password
     *
     * @param array $data
     * @return int|false
     */
    public function createUser(array $data)
    {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $data['role'] = $data['role'] ?? 'customer';
        $data['status'] = $data['status'] ?? 'active';
        
        return $this->insert($data);
    }

    /**
     * Mark user as verified
     *
     * @param int $userId
     * @return bool
     */
    public function markAsVerified(int $userId): bool
    {
        return $this->update($userId, ['is_verified' => 1]);
    }

    /**
     * Get all customers (users with role = customer)
     *
     * @return array
     */
    public function getCustomers(): array
    {
        return $this->where('role', 'customer')
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get customer by ID
     *
     * @param int $id
     * @return array|null
     */
    public function getCustomer(int $id): ?array
    {
        return $this->where('id', $id)
                    ->where('role', 'customer')
                    ->first();
    }

    /**
     * Update customer details (without password)
     *
     * @param int   $id
     * @param array $data
     * @return bool
     */
    public function updateCustomer(int $id, array $data): bool
    {
        // Remove password from data if empty
        if (empty($data['password'])) {
            unset($data['password']);
        }
        
        // Skip model validation since controller handles it
        // Model validation requires password which is not provided in admin updates
        return $this->skipValidation(true)->update($id, $data);
    }
}
