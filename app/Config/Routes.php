<?php

use CodeIgniter\Router\RouteCollection;
use App\Controllers\AuthController;
use App\Controllers\Home;
use App\Controllers\Admin\DashboardController;

/**
 * @var RouteCollection $routes
 */

// Public Routes
$routes->get('/', [Home::class, 'index']);

// Authentication (Guest only - redirect if logged in)
$routes->group('', ['filter' => 'guest'], static function ($routes) {
    $routes->get('login', [AuthController::class, 'showLogin']);
    $routes->post('login', [AuthController::class, 'login']);
    $routes->get('signup', [AuthController::class, 'showSignup']);
    $routes->post('signup', [AuthController::class, 'signup']);
    
    // Forgot Password
    $routes->get('forgot-password', [AuthController::class, 'forgotPasswordForm']);
    $routes->post('forgot-password', [AuthController::class, 'sendResetLink']);
    $routes->get('reset-password/(:segment)', [AuthController::class, 'resetPasswordForm/$1']);
    $routes->post('reset-password/(:segment)', [AuthController::class, 'updatePassword/$1']);
});

// Logout (accessible by anyone logged in)
$routes->get('logout', [AuthController::class, 'logout']);

// Admin Routes (Admin only)
$routes->group('admin', ['filter' => 'admin'], static function ($routes) {
    $routes->get('/', [DashboardController::class, 'index']);
    $routes->get('dashboard', [DashboardController::class, 'index']);
});
