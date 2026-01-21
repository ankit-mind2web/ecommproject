<?php

namespace App\Models;

use CodeIgniter\Model;

class PasswordResetModel extends Model
{
    protected $table            = 'password_resets';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'token',
        'expires_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = '';

    /**
     * Create a new password reset token
     *
     * @param int    $userId
     * @param string $hashedToken
     * @param string $expiresAt
     * @return int|false
     */
    public function createToken(int $userId, string $hashedToken, string $expiresAt)
    {
        // Delete any existing tokens for this user first
        $this->deleteUserTokens($userId);

        return $this->insert([
            'user_id'    => $userId,
            'token'      => $hashedToken,
            'expires_at' => $expiresAt
        ]);
    }

    /**
     * Find a valid (non-expired) token
     *
     * @param string $hashedToken
     * @return array|null
     */
    public function findValidToken(string $hashedToken): ?array
    {
        return $this->where('token', $hashedToken)
                    ->where('expires_at >', date('Y-m-d H:i:s'))
                    ->first();
    }

    /**
     * Delete a specific token by ID
     *
     * @param int $tokenId
     * @return bool
     */
    public function deleteToken(int $tokenId): bool
    {
        return $this->delete($tokenId);
    }

    /**
     * Delete all tokens for a specific user
     *
     * @param int $userId
     * @return bool
     */
    public function deleteUserTokens(int $userId): bool
    {
        return $this->where('user_id', $userId)->delete();
    }

    /**
     * Clean up expired tokens
     *
     * @return bool
     */
    public function deleteExpiredTokens(): bool
    {
        return $this->where('expires_at <', date('Y-m-d H:i:s'))->delete();
    }
}
