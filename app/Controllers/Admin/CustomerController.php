<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class CustomerController extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Display customers list
     */
    public function index()
    {
        $customers = $this->userModel->getCustomers();

        return view('admin/customers/index', [
            'title'     => 'Customers',
            'pageTitle' => 'Customers',
            'customers' => $customers
        ]);
    }

    /**
     * Show edit customer form
     */
    public function edit($id)
    {
        $customer = $this->userModel->getCustomer($id);

        if (!$customer) {
            return redirect()->to('/admin/users')
                           ->with('error', 'Customer not found');
        }

        return view('admin/customers/edit', [
            'title'     => 'Edit Customer',
            'pageTitle' => 'Edit Customer',
            'customer'  => $customer,
            'extraJs'   => ['admin-customers.js']
        ]);
    }

    /**
     * Update customer
     */
    public function update($id)
    {
        $customer = $this->userModel->getCustomer($id);

        if (!$customer) {
            return redirect()->to('/admin/users')
                           ->with('error', 'Customer not found');
        }

        $rules = [
            'name'   => 'required|min_length[3]|max_length[100]|regex_match[/^[a-zA-Z\s]+$/]',
            'email'  => "required|valid_email|max_length[255]|is_unique[users.email,id,{$id}]",
            'status' => 'required|in_list[active,inactive]'
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
                'max_length'  => 'Email must not exceed 255 characters',
                'is_unique'   => 'This email is already registered'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name'        => $this->request->getPost('name'),
            'email'       => $this->request->getPost('email'),
            'status'      => $this->request->getPost('status'),
            'is_verified' => $this->request->getPost('is_verified') ? 1 : 0
        ];

        if ($this->userModel->updateCustomer($id, $data)) {
            return redirect()->to('/admin/users')
                           ->with('success', 'Customer updated successfully');
        }

        return redirect()->back()
                       ->withInput()
                       ->with('error', 'Failed to update customer');
    }
}
