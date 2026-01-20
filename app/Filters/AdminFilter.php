<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminFilter implements FilterInterface
{
    /**
     * Check if user is authenticated and has admin role
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // Check if user is logged in
        if (!$session->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Please login to access this page');
        }

        // Check if user is admin
        if ($session->get('user_role') !== 'admin') {
            return redirect()->to('/')->with('error', 'Access denied. Admin only.');
        }

        return $request;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing after
    }
}
