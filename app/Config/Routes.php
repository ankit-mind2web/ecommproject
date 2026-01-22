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
    
    // Email Verification
    $routes->get('verify-email/(:segment)', [AuthController::class, 'verifyEmail/$1']);
});

// Logout (accessible by anyone logged in)
$routes->get('logout', [AuthController::class, 'logout']);

// Admin Routes (Admin only)
$routes->group('admin', ['filter' => 'admin'], static function ($routes) {
    $routes->get('/', [DashboardController::class, 'index']);
    $routes->get('dashboard', [DashboardController::class, 'index']);
    
    // Products
    $routes->get('products', 'Admin\ProductController::index');
    $routes->get('products/create', 'Admin\ProductController::create');
    $routes->post('products/store', 'Admin\ProductController::store');
    $routes->get('products/edit/(:num)', 'Admin\ProductController::edit/$1');
    $routes->post('products/update/(:num)', 'Admin\ProductController::update/$1');
    $routes->delete('products/delete/(:num)', 'Admin\ProductController::delete/$1');
    $routes->delete('products/delete-image/(:num)', 'Admin\ProductController::deleteImage/$1');
    
    // Customers
    $routes->get('users', 'Admin\CustomerController::index');
    $routes->get('users/edit/(:num)', 'Admin\CustomerController::edit/$1');
    $routes->post('users/update/(:num)', 'Admin\CustomerController::update/$1');
    
    // Categories
    $routes->get('categories', 'Admin\CategoryController::index');
    $routes->get('categories/create', 'Admin\CategoryController::create');
    $routes->post('categories/store', 'Admin\CategoryController::store');
    $routes->get('categories/edit/(:num)', 'Admin\CategoryController::edit/$1');
    $routes->post('categories/update/(:num)', 'Admin\CategoryController::update/$1');
    $routes->delete('categories/delete/(:num)', 'Admin\CategoryController::delete/$1');
});
