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
}
