<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Public Routes
$routes->get('/', 'Home::index');

// Authentication (Guest only - redirect if logged in)
$routes->group('', ['filter' => 'guest'], static function ($routes) {
    $routes->get('login', 'AuthController::showLogin');
    $routes->post('login', 'AuthController::login');
    $routes->get('signup', 'AuthController::showSignup');
    $routes->post('signup', 'AuthController::signup');
});

// Logout (accessible by anyone logged in)
$routes->get('logout', 'AuthController::logout');

// Admin Routes (Admin only)
$routes->group('admin', ['filter' => 'admin'], static function ($routes) {
    $routes->get('/', 'Admin\DashboardController::index');
    $routes->get('dashboard', 'Admin\DashboardController::index');
});
