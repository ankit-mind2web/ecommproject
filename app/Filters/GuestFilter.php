<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class GuestFilter implements FilterInterface
{
    /**
     * Block logged-in users from guest pages (login, signup)
     * Redirect based on role
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // If user is logged in, redirect based on role
        if ($session->get('logged_in')) {
            if ($session->get('user_role') === 'admin') {
                return redirect()->to('/admin/dashboard');
            }
            return redirect()->to('/');
        }

        return $request;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing after
    }
}
