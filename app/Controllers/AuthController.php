<?php

namespace App\Controllers;

use App\Services\AuthService;

class AuthController extends BaseController
{
    protected AuthService $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    /**
     * Display login page
     */
    public function showLogin()
    {
        return view('auth/login', [
            'title'    => 'Login',
            'extraCss' => ['auth.css'],
            'extraJs'  => ['auth.js']
        ]);
    }

    /**
     * Handle login form submission
     */
    public function login()
    {
        // Validate input
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required'
        ];

        $messages = [
            'email' => [
                'required'    => 'Email is required',
                'valid_email' => 'Please enter a valid email address'
            ],
            'password' => [
                'required' => 'Password is required'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }

        // Attempt login
        $result = $this->authService->login(
            $this->request->getPost('email'),
            $this->request->getPost('password')
        );

        if (!$result['success']) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', $result['message']);
        }

        // Redirect based on role
        if ($result['user']['role'] === 'admin') {
            return redirect()->to('/admin/dashboard')->with('success', 'Welcome back, Admin!');
        }

        return redirect()->to('/')->with('success', 'Welcome back!');
    }

    /**
     * Display signup page
     */
    public function showSignup()
    {
        return view('auth/signup', [
            'title'    => 'Create Account',
            'extraCss' => ['auth.css'],
            'extraJs'  => ['auth.js']
        ]);
    }

    /**
     * Handle signup form submission
     */
    public function signup()
    {
        // Validate input
        $rules = [
            'name'             => 'required|min_length[3]|max_length[100]|regex_match[/^[a-zA-Z\s]+$/]',
            'email'            => 'required|valid_email|max_length[255]',
            'password'         => 'required|min_length[8]',
            'confirm_password' => 'required|matches[password]'
        ];

        $messages = [
            'name' => [
                'required'    => 'Name is required',
                'min_length'  => 'Name must be at least 3 characters',
                'max_length'  => 'Name must not exceed 100 characters',
                'regex_match' => 'Name can only contain letters and spaces'
            ],
            'email' => [
                'required'    => 'Email is required',
                'valid_email' => 'Please enter a valid email address',
                'max_length'  => 'Email must not exceed 255 characters'
            ],
            'password' => [
                'required'   => 'Password is required',
                'min_length' => 'Password must be at least 8 characters'
            ],
            'confirm_password' => [
                'required' => 'Please confirm your password',
                'matches'  => 'Passwords do not match'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }

        // Attempt registration
        $result = $this->authService->register([
            'name'     => $this->request->getPost('name'),
            'email'    => $this->request->getPost('email'),
            'password' => $this->request->getPost('password')
        ]);

        if (!$result['success']) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', $result['message'])
                           ->with('errors', $result['errors']);
        }

        return redirect()->to('/login')->with('success', $result['message']);
    }

    /**
     * Handle logout
     */
    public function logout()
    {
        $this->authService->logout();
        return redirect()->to('/login')->with('success', 'You have been logged out');
    }

    /**
     * Display forgot password form
     */
    public function forgotPasswordForm()
    {
        return view('auth/forgot_password', [
            'title'    => 'Forgot Password',
            'extraCss' => ['auth.css'],
            'extraJs'  => ['auth.js']
        ]);
    }

    /**
     * Handle forgot password form submission and send reset link
     */
    public function sendResetLink()
    {
        // Validate email
        $rules = [
            'email' => 'required|valid_email'
        ];

        $messages = [
            'email' => [
                'required'    => 'Email is required',
                'valid_email' => 'Please enter a valid email address'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }

        $email = $this->request->getPost('email');
        $result = $this->authService->sendPasswordResetLink($email);

        if (!$result['success']) {
            return redirect()->back()
                           ->with('error', $result['message']);
        }

        return redirect()->back()
                       ->with('success', $result['message']);
    }

    /**
     * Display reset password form
     */
    public function resetPasswordForm($token)
    {
        $validReset = $this->authService->validateResetToken($token);

        if (!$validReset) {
            return redirect()->to('/forgot-password')
                           ->with('error', 'Invalid or expired reset link. Please request a new one.');
        }

        return view('auth/reset_password', [
            'title'    => 'Reset Password',
            'token'    => $token,
            'extraCss' => ['auth.css'],
            'extraJs'  => ['auth.js']
        ]);
    }

    /**
     * Handle password update
     */
    public function updatePassword($token)
    {
        // Validate passwords
        $rules = [
            'password'         => 'required|min_length[8]',
            'confirm_password' => 'required|matches[password]'
        ];

        $messages = [
            'password' => [
                'required'   => 'Password is required',
                'min_length' => 'Password must be at least 8 characters'
            ],
            'confirm_password' => [
                'required' => 'Please confirm your password',
                'matches'  => 'Passwords do not match'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()
                           ->with('errors', $this->validator->getErrors());
        }

        $newPassword = $this->request->getPost('password');
        $result = $this->authService->resetPassword($token, $newPassword);

        if (!$result['success']) {
            return redirect()->to('/forgot-password')
                           ->with('error', $result['message']);
        }

        return redirect()->to('/login')
                       ->with('success', $result['message']);
    }
}
