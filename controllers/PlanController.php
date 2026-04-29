<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/RecurringPlan.php';
require_once __DIR__ . '/../models/Client.php';

class PlanController extends BaseController {
    private RecurringPlan $planModel;
    private Client        $clientModel;

    public function __construct() {
        $this->planModel   = new RecurringPlan();
        $this->clientModel = new Client();
    }

    public function index(): void {
        $this->requireRole('admin');
        $plans   = $this->planModel->findAll([], ['sort' => ['createdAt' => -1]]);
        $clients = $this->clientModel->findAll();
        $clientMap = [];
        foreach ($clients as $c) { $clientMap[(string)$c['_id']] = $c['name']; }
        $this->view('admin/plans', ['plans' => $plans, 'clientMap' => $clientMap, 'flash' => $this->getFlash()]);
    }

    public function create(): void {
        $this->requireRole('admin');
        $clients = $this->clientModel->findAll([], ['sort' => ['name' => 1]]);

        if ($this->isPost()) {
            $data = [
                'clientId'     => $this->sanitize($this->post('clientId')),
                'serviceName'  => $this->sanitize($this->post('serviceName')),
                'amount'       => (float)$this->post('amount'),
                'billingCycle' => $this->sanitize($this->post('billingCycle')),
                'startDate'    => $this->sanitize($this->post('startDate')),
                'status'       => 'active',
            ];
            if (empty($data['clientId']) || empty($data['serviceName'])) {
                $this->view('admin/plan-form', ['error' => 'All fields required', 'plan' => $data, 'clients' => $clients, 'action' => 'create']);
                return;
            }
            $this->planModel->createPlan($data);
            $this->setFlash('success', 'Plan created');
            $this->redirect('admin/plans');
        }
        $this->view('admin/plan-form', ['plan' => null, 'clients' => $clients, 'action' => 'create', 'error' => null]);
    }

    public function edit(string $id): void {
        $this->requireRole('admin');
        $plan    = $this->planModel->findById($id);
        $clients = $this->clientModel->findAll([], ['sort' => ['name' => 1]]);
        if (!$plan) { $this->redirect('admin/plans'); }

        if ($this->isPost()) {
            $data = [
                'serviceName'  => $this->sanitize($this->post('serviceName')),
                'amount'       => (float)$this->post('amount'),
                'billingCycle' => $this->sanitize($this->post('billingCycle')),
                'status'       => $this->sanitize($this->post('status')),
            ];
            $this->planModel->updateById($id, $data);
            $this->setFlash('success', 'Plan updated');
            $this->redirect('admin/plans');
        }
        $this->view('admin/plan-form', ['plan' => $plan, 'clients' => $clients, 'action' => 'edit', 'error' => null]);
    }

    public function delete(string $id): void {
        $this->requireRole('admin');
        $this->planModel->deleteById($id);
        $this->setFlash('success', 'Plan deleted');
        $this->redirect('admin/plans');
    }
}
