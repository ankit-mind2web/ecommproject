<?php

namespace App\Services;

use App\Libraries\Mailer;
use App\Models\UserModel;

class AuthService
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Attempt to authenticate user
     *
     * @param string $email
     * @param string $password
     * @return array ['success' => bool, 'message' => string, 'user' => array|null]
     */
    public function login(string $email, string $password): array
    {
        // First check if user exists at all (including soft-deleted)
        $userWithDeleted = $this->userModel->findByEmailWithDeleted($email);

        // If user doesn't exist at all
        if (!$userWithDeleted) {
            return [
                'success' => false,
                'message' => 'Invalid email or password',
                'user'    => null
            ];
        }

        // Check if user is soft-deleted (deactivated by admin)
        if (!empty($userWithDeleted['deleted_at'])) {
            return [
                'success' => false,
                'message' => 'Your account has been deactivated.',
                'user'    => null
            ];
        }

        // Check if user is inactive
        if ($userWithDeleted['status'] === 'inactive') {
            return [
                'success' => false,
                'message' => 'Your account is currently inactive.',
                'user'    => null
            ];
        }

        // Verify password
        if (!password_verify($password, $userWithDeleted['password'])) {
            return [
                'success' => false,
                'message' => 'Invalid email or password',
                'user'    => null
            ];
        }

        // Remove password from user data
        unset($userWithDeleted['password']);

        // Set session
        $this->setUserSession($userWithDeleted);

        return [
            'success' => true,
            'message' => 'Login successful',
            'user'    => $userWithDeleted
        ];
    }

    /**
     * Register new user
     *
     * @param array $data
     * @return array ['success' => bool, 'message' => string, 'errors' => array]
     */
    public function register(array $data): array
    {
        // Check if email already exists
        if ($this->userModel->emailExists($data['email'])) {
            return [
                'success' => false,
                'message' => 'This email is already registered',
                'errors'  => ['email' => 'This email is already registered']
            ];
        }

        // Validate password strength
        $passwordValidation = $this->validatePasswordStrength($data['password']);
        if (!$passwordValidation['isValid']) {
            return [
                'success' => false,
                'message' => $passwordValidation['message'],
                'errors'  => ['password' => $passwordValidation['message']]
            ];
        }

        // Create user with is_verified = 0
        $userId = $this->userModel->createUser([
            'name'        => trim($data['name']),
            'email'       => trim($data['email']),
            'password'    => $data['password'],
            'role'        => 'customer',
            'status'      => 'active',
            'is_verified' => 0
        ]);

        if (!$userId) {
            $errors = $this->userModel->errors();
            return [
                'success' => false,
                'message' => 'Registration failed. Please try again.',
                'errors'  => $errors
            ];
        }

        // Send welcome email with verification link
        $this->sendWelcomeVerificationEmail($userId, trim($data['name']), trim($data['email']));

        return [
            'success' => true,
            'message' => 'Registration successful! Please check your email to verify your account.',
            'errors'  => []
        ];
    }

    /**
     * Send welcome email with verification link
     *
     * @param int    $userId
     * @param string $name
     * @param string $email
     * @return bool
     */
    protected function sendWelcomeVerificationEmail(int $userId, string $name, string $email): bool
    {
        // Generate verification token
        $token = bin2hex(random_bytes(32));
        $hashedToken = password_hash($token, PASSWORD_DEFAULT);

        // Store token (expires in 24 hours)
        $tokenModel = new \App\Models\TokenModel();
        $expiresAt = date('Y-m-d H:i:s', strtotime('+24 hours'));
        $tokenModel->createToken($userId, $hashedToken, \App\Models\TokenModel::TYPE_EMAIL_VERIFICATION, $expiresAt);

        // Build verification link
        $verificationLink = base_url("verify-email/{$token}");

        // Send email
        $mailer = new Mailer();
        $result = $mailer->sendWelcomeEmail($email, $name, $verificationLink);

        return $result['success'];
    }

    /**
     * Logout user
     *
     * @return void
     */
    public function logout(): void
    {
        $session = session();
        $session->destroy();
    }

    /**
     * Check if user is logged in
     *
     * @return bool
     */
    public function isLoggedIn(): bool
    {
        $session = session();
        return $session->has('user_id');
    }

    /**
     * Get current logged in user
     *
     * @return array|null
     */
    public function getCurrentUser(): ?array
    {
        $session = session();
        
        if (!$session->has('user_id')) {
            return null;
        }

        return [
            'id'    => $session->get('user_id'),
            'name'  => $session->get('user_name'),
            'email' => $session->get('user_email'),
            'role'  => $session->get('user_role')
        ];
    }

    /**
     * Check if current user is admin
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        $session = session();
        return $session->get('user_role') === 'admin';
    }

    /**
     * Set user session data
     *
     * @param array $user
     * @return void
     */
    protected function setUserSession(array $user): void
    {
        $session = session();
        $session->set([
            'user_id'    => $user['id'],
            'user_name'  => $user['name'],
            'user_email' => $user['email'],
            'user_role'  => $user['role'],
            'logged_in'  => true
        ]);
    }

    /**
     * Validate password strength
     *
     * @param string $password
     * @return array ['isValid' => bool, 'message' => string]
     */
    protected function validatePasswordStrength(string $password): array
    {
        if (strlen($password) < 8) {
            return ['isValid' => false, 'message' => 'Password must be at least 8 characters'];
        }

        if (!preg_match('/[A-Z]/', $password)) {
            return ['isValid' => false, 'message' => 'Password must contain at least one uppercase letter'];
        }

        if (!preg_match('/[a-z]/', $password)) {
            return ['isValid' => false, 'message' => 'Password must contain at least one lowercase letter'];
        }

        if (!preg_match('/[0-9]/', $password)) {
            return ['isValid' => false, 'message' => 'Password must contain at least one number'];
        }

        return ['isValid' => true, 'message' => ''];
    }

    /**
     * Send password reset link to user
     *
     * @param string $email
     * @return array ['success' => bool, 'message' => string]
     */
    public function sendPasswordResetLink(string $email): array
    {
        // Find user by email
        $user = $this->userModel->findByEmail($email);

        // If user not found, do not reveal this information
        if (!$user) {
            return [
                'success' => true,
                'message' => 'A reset link has been sent to your email address.'
            ];
        }

        // Generate token
        $token = bin2hex(random_bytes(32));
        $hashedToken = password_hash($token, PASSWORD_DEFAULT);

        // Store token (expires in 30 minutes)
        $tokenModel = new \App\Models\TokenModel();
        $expiresAt = date('Y-m-d H:i:s', strtotime('+30 minutes'));
        $tokenModel->createToken($user['id'], $hashedToken, \App\Models\TokenModel::TYPE_PASSWORD_RESET, $expiresAt);

        // Build reset link
        $resetLink = base_url("reset-password/{$token}");

        // Send email
        $mailer = new Mailer();
        $result = $mailer->sendResetEmail($email, $resetLink);

        if (!$result['success']) {
            return [
                'success' => false,
                'message' => 'Failed to send email. Please try again.'
            ];
        }

        return [
            'success' => true,
            'message' => 'A reset link has been sent to your email address.'
        ];
    }

    /**
     * Validate a password reset token
     *
     * @param string $token
     * @return array|null Returns reset data if valid, null if invalid
     */
    public function validateResetToken(string $token): ?array
    {
        $tokenModel = new \App\Models\TokenModel();
        $allTokens = $tokenModel->findAllValidTokensByType(\App\Models\TokenModel::TYPE_PASSWORD_RESET);

        foreach ($allTokens as $reset) {
            if (password_verify($token, $reset['token'])) {
                return $reset;
            }
        }

        return null;
    }

    /**
     * Reset user password
     *
     * @param string $token
     * @param string $newPassword
     * @return array ['success' => bool, 'message' => string]
     */
    public function resetPassword(string $token, string $newPassword): array
    {
        // Validate token
        $validReset = $this->validateResetToken($token);

        if (!$validReset) {
            return [
                'success' => false,
                'message' => 'Invalid or expired reset link. Please request a new one.'
            ];
        }

        // Update password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $this->userModel->update($validReset['user_id'], ['password' => $hashedPassword]);

        // Delete token
        $tokenModel = new \App\Models\TokenModel();
        $tokenModel->deleteToken($validReset['id']);

        return [
            'success' => true,
            'message' => 'Password reset successful! Please login with your new password.'
        ];
    }

    /**
     * Verify user email with token
     *
     * @param string $token
     * @return array ['success' => bool, 'message' => string]
     */
    public function verifyEmail(string $token): array
    {
        $tokenModel = new \App\Models\TokenModel();
        $allTokens = $tokenModel->findAllValidTokensByType(\App\Models\TokenModel::TYPE_EMAIL_VERIFICATION);

        $validToken = null;
        foreach ($allTokens as $tokenData) {
            if (password_verify($token, $tokenData['token'])) {
                $validToken = $tokenData;
                break;
            }
        }

        if (!$validToken) {
            return [
                'success' => false,
                'message' => 'Invalid or expired verification link.'
            ];
        }

        // Mark user as verified
        $this->userModel->markAsVerified($validToken['user_id']);

        // Delete the token
        $tokenModel->deleteToken($validToken['id']);

        return [
            'success' => true,
            'message' => 'Email verified successfully! You can now login.'
        ];
    }
}
