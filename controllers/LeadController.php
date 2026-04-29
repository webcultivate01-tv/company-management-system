<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Lead.php';
require_once __DIR__ . '/../models/User.php';

class LeadController extends BaseController {
    private Lead $leadModel;
    private User $userModel;

    public function __construct() {
        $this->leadModel = new Lead();
        $this->userModel = new User();
    }

    // Employee: list own leads
    public function index(): void {
        $this->requireAuth();
        $leads = $this->leadModel->getByUser($_SESSION['user_id']);
        $stats = $this->leadModel->getStatsByUser($_SESSION['user_id']);
        $this->view('employee/leads', [
            'leads' => $leads,
            'stats' => $stats,
            'flash' => $this->getFlash(),
        ]);
    }

    // Employee: create lead
    public function create(): void {
        $this->requireAuth();
        if ($this->isPost()) {
            $name     = $this->sanitize($this->post('name'));
            $mobile   = $this->sanitize($this->post('mobile'));
            $business = $this->sanitize($this->post('business'));
            $notes    = $this->sanitize($this->post('notes'));
            $status   = in_array($this->post('status'), ['interested', 'not_interested']) ? $this->post('status') : '';

            $error = null;
            if (strlen($name) < 2 || !preg_match('/^[a-zA-Z\s]+$/', $name))
                $error = 'Name is required (letters only, min 2 characters)';
            elseif (!preg_match('/^[0-9]{10,15}$/', $mobile))
                $error = 'Enter a valid 10-digit mobile number';
            elseif (strlen($business) > 100)
                $error = 'Business name cannot exceed 100 characters';
            elseif (empty($status))
                $error = 'Please select a status';
            elseif (strlen($notes) > 500)
                $error = 'Notes cannot exceed 500 characters';

            if ($error) {
                $this->view('employee/lead-form', ['error' => $error, 'lead' => $_POST, 'action' => 'create']);
                return;
            }

            $this->leadModel->insertOne([
                'name'        => $name,
                'mobile'      => $mobile,
                'business'    => $business,
                'status'      => $status,
                'notes'       => $notes,
                'addedBy'     => $_SESSION['user_id'],
                'addedByName' => $_SESSION['name'],
            ]);
            $this->setFlash('success', 'Lead added successfully');
            $this->redirect('employee/leads');
        }
        $this->view('employee/lead-form', ['lead' => null, 'action' => 'create', 'error' => null]);
    }

    // Employee: edit lead
    public function edit(string $id): void {
        $this->requireAuth();
        $lead = $this->leadModel->findById($id);
        if (!$lead || $lead['addedBy'] !== $_SESSION['user_id']) { $this->redirect('employee/leads'); }

        if ($this->isPost()) {
            $this->leadModel->updateById($id, [
                'name'     => $this->sanitize($this->post('name')),
                'mobile'   => $this->sanitize($this->post('mobile')),
                'business' => $this->sanitize($this->post('business')),
                'status'   => in_array($this->post('status'), ['interested', 'not_interested']) ? $this->post('status') : 'interested',
                'notes'    => $this->sanitize($this->post('notes')),
            ]);
            $this->setFlash('success', 'Lead updated');
            $this->redirect('employee/leads');
        }
        $this->view('employee/lead-form', ['lead' => $lead, 'action' => 'edit', 'error' => null]);
    }

    // Employee: delete lead
    public function delete(string $id): void {
        $this->requireAuth();
        $lead = $this->leadModel->findById($id);
        if ($lead && $lead['addedBy'] === $_SESSION['user_id']) {
            $this->leadModel->deleteById($id);
        }
        $this->setFlash('success', 'Lead deleted');
        $this->redirect('employee/leads');
    }

    // Admin: view all leads
    public function adminIndex(): void {
        $this->requireRole('admin');
        $users   = $this->userModel->getAllUsers();
        $filterUser = $this->get('user', '');
        $leads   = $filterUser ? $this->leadModel->getByUser($filterUser)
                               : $this->leadModel->findAll([], ['sort' => ['createdAt' => -1]]);
        $stats   = $this->leadModel->getStats();

        // per-user stats
        $userStats = [];
        foreach ($users as $u) {
            $uid = (string)$u['_id'];
            $userStats[$uid] = $this->leadModel->getStatsByUser($uid);
        }

        $this->view('admin/leads', [
            'leads'      => $leads,
            'stats'      => $stats,
            'users'      => $users,
            'userStats'  => $userStats,
            'filterUser' => $filterUser,
            'flash'      => $this->getFlash(),
        ]);
    }
}
