<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Service.php';

class ServiceController extends BaseController {
    private Service $serviceModel;

    public function __construct() {
        $this->serviceModel = new Service();
    }

    public function index(): void {
        $this->requireRole('admin');
        $this->view('admin/services', [
            'services' => $this->serviceModel->getAllServices(),
            'flash'    => $this->getFlash(),
        ]);
    }

    public function create(): void {
        $this->requireRole('admin');
        if ($this->isPost()) {
            $name = $this->sanitize($this->post('name'));
            $description = $this->sanitize($this->post('description'));
            if (empty($name)) {
                $this->view('admin/service-form', ['error' => 'Name is required', 'service' => null, 'action' => 'create']);
                return;
            }
            $this->serviceModel->insertOne(['name' => $name, 'description' => $description]);
            $this->setFlash('success', 'Service added successfully');
            $this->redirect('admin/services');
        }
        $this->view('admin/service-form', ['service' => null, 'action' => 'create', 'error' => null]);
    }

    public function edit(string $id): void {
        $this->requireRole('admin');
        $service = $this->serviceModel->findById($id);
        if (!$service) { $this->redirect('admin/services'); }

        if ($this->isPost()) {
            $this->serviceModel->updateById($id, [
                'name'        => $this->sanitize($this->post('name')),
                'description' => $this->sanitize($this->post('description')),
            ]);
            $this->setFlash('success', 'Service updated');
            $this->redirect('admin/services');
        }
        $this->view('admin/service-form', ['service' => $service, 'action' => 'edit', 'error' => null]);
    }

    public function delete(string $id): void {
        $this->requireRole('admin');
        $this->serviceModel->deleteById($id);
        $this->setFlash('success', 'Service deleted');
        $this->redirect('admin/services');
    }
}
