<?php

namespace App\Models;

use CodeIgniter\Model;

class TokenModel extends Model
{
    protected $table            = 'tokens';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'token',
        'token_type',
        'expires_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = '';

    // Token types constants
    public const TYPE_PASSWORD_RESET     = 'password_reset';
    public const TYPE_EMAIL_VERIFICATION = 'email_verification';

    /**
     * Create a new token
     *
     * @param int    $userId
     * @param string $hashedToken
     * @param string $tokenType
     * @param string $expiresAt
     * @return int|false
     */
    public function createToken(int $userId, string $hashedToken, string $tokenType, string $expiresAt)
    {
        // Delete any existing tokens of this type for this user first
        $this->deleteUserTokensByType($userId, $tokenType);

        return $this->insert([
            'user_id'    => $userId,
            'token'      => $hashedToken,
            'token_type' => $tokenType,
            'expires_at' => $expiresAt
        ]);
    }

    /**
     * Find a valid (non-expired) token by type
     *
     * @param string $hashedToken
     * @param string $tokenType
     * @return array|null
     */
    public function findValidToken(string $hashedToken, string $tokenType): ?array
    {
        return $this->where('token', $hashedToken)
                    ->where('token_type', $tokenType)
                    ->where('expires_at >', date('Y-m-d H:i:s'))
                    ->first();
    }

    /**
     * Find all valid tokens of a type (for password_verify checking)
     *
     * @param string $tokenType
     * @return array
     */
    public function findAllValidTokensByType(string $tokenType): array
    {
        return $this->where('token_type', $tokenType)
                    ->where('expires_at >', date('Y-m-d H:i:s'))
                    ->findAll();
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
     * Delete all tokens of a specific type for a user
     *
     * @param int    $userId
     * @param string $tokenType
     * @return bool
     */
    public function deleteUserTokensByType(int $userId, string $tokenType): bool
    {
        return $this->where('user_id', $userId)
                    ->where('token_type', $tokenType)
                    ->delete();
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
