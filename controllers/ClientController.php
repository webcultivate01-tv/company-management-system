<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Client.php';
require_once __DIR__ . '/../models/RecurringPlan.php';
require_once __DIR__ . '/../models/Payment.php';
require_once __DIR__ . '/../models/Service.php';

class ClientController extends BaseController {
    private Client        $clientModel;
    private RecurringPlan $planModel;
    private Payment       $paymentModel;
    private Service       $serviceModel;

    public function __construct() {
        $this->clientModel  = new Client();
        $this->planModel    = new RecurringPlan();
        $this->paymentModel = new Payment();
        $this->serviceModel = new Service();
    }

    public function index(): void {
        $this->requireRole('admin');
        $status  = $this->get('status', '');
        $search  = $this->get('search', '');
        $clients = $search ? $this->clientModel->searchClients($search)
                           : ($status ? $this->clientModel->getByStatus($status)
                                      : $this->clientModel->findAll([], ['sort' => ['createdAt' => -1]]));

        $this->view('admin/clients', [
            'clients' => $clients,
            'flash'   => $this->getFlash(),
            'status'  => $status,
            'search'  => $search,
        ]);
    }

    public function create(): void {
        $this->requireRole('admin');
        if ($this->isPost()) {
            $data = [
                'name'           => $this->sanitize($this->post('name')),
                'email'          => $this->sanitize($this->post('email')),
                'phone'          => $this->sanitize($this->post('phone')),
                'company'        => $this->sanitize($this->post('company')),
                'projectDetails' => $this->sanitize($this->post('projectDetails')),
                'status'         => $this->sanitize($this->post('status')),
                'services'       => $_POST['services'] ?? [],
            ];
            if (empty($data['name'])) {
                $this->view('admin/client-form', ['error' => 'Name is required', 'client' => $data, 'action' => 'create', 'services' => $this->serviceModel->getAllServices()]);
                return;
            }
            $this->clientModel->createClient($data);
            $this->setFlash('success', 'Client added successfully');
            $this->redirect('admin/clients');
        }
        $this->view('admin/client-form', ['client' => null, 'action' => 'create', 'error' => null, 'services' => $this->serviceModel->getAllServices()]);
    }

    public function edit(string $id): void {
        $this->requireRole('admin');
        $client = $this->clientModel->findById($id);
        if (!$client) { $this->redirect('admin/clients'); }

        if ($this->isPost()) {
            $data = [
                'name'           => $this->sanitize($this->post('name')),
                'email'          => $this->sanitize($this->post('email')),
                'phone'          => $this->sanitize($this->post('phone')),
                'company'        => $this->sanitize($this->post('company')),
                'projectDetails' => $this->sanitize($this->post('projectDetails')),
                'status'         => $this->sanitize($this->post('status')),
                'services'       => $_POST['services'] ?? [],
            ];
            $this->clientModel->updateById($id, $data);
            $this->setFlash('success', 'Client updated successfully');
            $this->redirect('admin/clients');
        }
        $this->view('admin/client-form', ['client' => $client, 'action' => 'edit', 'error' => null, 'services' => $this->serviceModel->getAllServices()]);
    }

    public function delete(string $id): void {
        $this->requireRole('admin');
        $this->clientModel->deleteById($id);
        $this->setFlash('success', 'Client deleted');
        $this->redirect('admin/clients');
    }

    public function view(string $viewPath, array $data = []): void {
        // Override needed because we have a show() method
        extract($data);
        $fullPath = __DIR__ . '/../views/' . $viewPath . '.php';
        if (!file_exists($fullPath)) { die('View not found'); }
        require_once $fullPath;
    }

    public function show(string $id): void {
        $this->requireRole('admin');
        $client   = $this->clientModel->findById($id);
        if (!$client) { $this->redirect('admin/clients'); }
        $plans    = $this->planModel->getByClient($id);
        $payments = $this->paymentModel->getByClient($id);
        $this->view('admin/client-detail', [
            'client'   => $client,
            'plans'    => $plans,
            'payments' => $payments,
            'flash'    => $this->getFlash(),
        ]);
    }
}
