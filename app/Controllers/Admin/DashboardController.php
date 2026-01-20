<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class DashboardController extends BaseController
{
    /**
     * Display admin dashboard
     */
    public function index()
    {
        // Get statistics (placeholder values for now)
        $data = [
            'title'     => 'Dashboard',
            'pageTitle' => 'Dashboard',
            'stats'     => [
                'totalUsers'    => 0,
                'totalOrders'   => 0,
                'totalProducts' => 0,
                'totalRevenue'  => 0
            ],
            'recentOrders' => []
        ];

        return view('admin/dashboard', $data);
    }
}
