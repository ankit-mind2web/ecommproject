<?php

namespace App\Services;

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
        // Find active user by email
        $user = $this->userModel->findActiveByEmail($email);

        if (!$user) {
            return [
                'success' => false,
                'message' => 'Invalid email or password',
                'user'    => null
            ];
        }

        // Verify password
        if (!password_verify($password, $user['password'])) {
            return [
                'success' => false,
                'message' => 'Invalid email or password',
                'user'    => null
            ];
        }

        // Remove password from user data
        unset($user['password']);

        // Set session
        $this->setUserSession($user);

        return [
            'success' => true,
            'message' => 'Login successful',
            'user'    => $user
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

        // Create user
        $userId = $this->userModel->createUser([
            'name'     => trim($data['name']),
            'email'    => trim($data['email']),
            'password' => $data['password'],
            'role'     => 'customer',
            'status'   => 'active'
        ]);

        if (!$userId) {
            $errors = $this->userModel->errors();
            return [
                'success' => false,
                'message' => 'Registration failed. Please try again.',
                'errors'  => $errors
            ];
        }

        return [
            'success' => true,
            'message' => 'Registration successful! Please login.',
            'errors'  => []
        ];
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
        $passwordResetModel = new \App\Models\PasswordResetModel();
        $expiresAt = date('Y-m-d H:i:s', strtotime('+30 minutes'));
        $passwordResetModel->createToken($user['id'], $hashedToken, $expiresAt);

        // Build reset link
        $resetLink = base_url("reset-password/{$token}");

        // Send email
        $mailer = new \App\Libraries\Mailer();
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
        $passwordResetModel = new \App\Models\PasswordResetModel();
        $allTokens = $passwordResetModel->where('expires_at >', date('Y-m-d H:i:s'))->findAll();

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
        $passwordResetModel = new \App\Models\PasswordResetModel();
        $passwordResetModel->deleteToken($validReset['id']);

        return [
            'success' => true,
            'message' => 'Password reset successful! Please login with your new password.'
        ];
    }
}
