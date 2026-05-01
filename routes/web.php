<?php
// Route definitions
// Format: 'METHOD:path' => ['Controller', 'method', [optional_params]]

$routes = [
    // Auth
    'GET:login'    => ['AuthController', 'login'],
    'POST:login'   => ['AuthController', 'login'],
    'GET:logout'   => ['AuthController', 'logout'],

    // Admin Dashboard
    'GET:admin/dashboard' => ['AdminController', 'dashboard'],

    // Users
    'GET:admin/users'               => ['UserController', 'index'],
    'GET:admin/users/detail/{id}'       => ['UserController', 'detail'],
    'GET:admin/users/delete-aadhar/{id}' => ['UserController', 'deleteAadhar'],
    'GET:admin/users/create'        => ['UserController', 'create'],
    'POST:admin/users/create'       => ['UserController', 'create'],
    'GET:admin/users/edit/{id}'     => ['UserController', 'edit'],
    'POST:admin/users/edit/{id}'    => ['UserController', 'edit'],
    'GET:admin/users/delete/{id}'   => ['UserController', 'delete'],
    'GET:admin/users/toggle/{id}'   => ['UserController', 'toggleActive'],
    'POST:admin/users/reset-password/{id}' => ['UserController', 'resetPassword'],

    // Positions
    'GET:admin/positions'              => ['PositionController', 'index'],
    'GET:admin/positions/create'       => ['PositionController', 'create'],
    'POST:admin/positions/create'      => ['PositionController', 'create'],
    'GET:admin/positions/edit/{id}'    => ['PositionController', 'edit'],
    'POST:admin/positions/edit/{id}'   => ['PositionController', 'edit'],
    'GET:admin/positions/delete/{id}'  => ['PositionController', 'delete'],

    // Admin Profile
    'GET:admin/profile'  => ['UserController', 'profile'],
    'POST:admin/profile' => ['UserController', 'profile'],

    // Attendance - Admin
    'GET:admin/attendance'                    => ['AttendanceController', 'adminAttendance'],
    'GET:admin/attendance/user/{id}'          => ['AttendanceController', 'userAttendance'],
    'GET:admin/attendance/delete/{id}'        => ['AttendanceController', 'deleteRecord'],

    // Clients
    'GET:admin/clients'              => ['ClientController', 'index'],
    'GET:admin/clients/create'       => ['ClientController', 'create'],
    'POST:admin/clients/create'      => ['ClientController', 'create'],
    'GET:admin/clients/edit/{id}'    => ['ClientController', 'edit'],
    'POST:admin/clients/edit/{id}'   => ['ClientController', 'edit'],
    'GET:admin/clients/delete/{id}'  => ['ClientController', 'delete'],
    'GET:admin/clients/view/{id}'    => ['ClientController', 'show'],

    // Services
    'GET:admin/services'              => ['ServiceController', 'index'],
    'GET:admin/services/create'       => ['ServiceController', 'create'],
    'POST:admin/services/create'      => ['ServiceController', 'create'],
    'GET:admin/services/edit/{id}'    => ['ServiceController', 'edit'],
    'POST:admin/services/edit/{id}'   => ['ServiceController', 'edit'],
    'GET:admin/services/delete/{id}'  => ['ServiceController', 'delete'],

    // Plans
    'GET:admin/plans'              => ['PlanController', 'index'],
    'GET:admin/plans/create'       => ['PlanController', 'create'],
    'POST:admin/plans/create'      => ['PlanController', 'create'],
    'GET:admin/plans/edit/{id}'    => ['PlanController', 'edit'],
    'POST:admin/plans/edit/{id}'   => ['PlanController', 'edit'],
    'GET:admin/plans/delete/{id}'  => ['PlanController', 'delete'],

    // Bills
    'GET:admin/bills' => ['PaymentController', 'bills'],

    // Payments
    'GET:admin/payments'                         => ['PaymentController', 'index'],
    'GET:admin/payments/create'                  => ['PaymentController', 'create'],
    'POST:admin/payments/create'                 => ['PaymentController', 'create'],
    'GET:admin/payments/mark-paid/{id}'          => ['PaymentController', 'markPaid'],
    'GET:admin/payments/invoice/{id}'            => ['PaymentController', 'generateInvoice'],
    'GET:admin/payments/send-invoice/{id}'       => ['PaymentController', 'sendInvoice'],

    // Email
    'GET:admin/email'  => ['EmailController', 'index'],
    'POST:admin/email' => ['EmailController', 'send'],

    // Leads
    'GET:employee/leads'              => ['LeadController', 'index'],
    'GET:employee/leads/create'       => ['LeadController', 'create'],
    'POST:employee/leads/create'      => ['LeadController', 'create'],
    'GET:employee/leads/edit/{id}'    => ['LeadController', 'edit'],
    'POST:employee/leads/edit/{id}'   => ['LeadController', 'edit'],
    'GET:employee/leads/delete/{id}'  => ['LeadController', 'delete'],

    // Admin Leads
    'GET:admin/leads' => ['LeadController', 'adminIndex'],

    // Employee
    'GET:employee/dashboard'   => ['EmployeeController', 'dashboard'],
    'GET:employee/attendance'  => ['AttendanceController', 'myAttendance'],
    'GET:employee/check-in'    => ['AttendanceController', 'checkIn'],
    'GET:employee/check-out'   => ['AttendanceController', 'checkOut'],
    'GET:employee/profile'     => ['UserController', 'profile'],
    'POST:employee/profile'    => ['UserController', 'profile'],
];

return $routes;
