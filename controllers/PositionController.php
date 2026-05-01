<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Position.php';

class PositionController extends BaseController {
    private Position $positionModel;

    public function __construct() {
        $this->positionModel = new Position();
    }

    public function index(): void {
        $this->requireRole('admin');
        $this->view('admin/positions', [
            'positions' => $this->positionModel->getAllPositions(),
            'flash'     => $this->getFlash(),
        ]);
    }

    public function create(): void {
        $this->requireRole('admin');

        if ($this->isPost()) {
            $title       = trim($this->sanitize($this->post('title')));
            $description = trim($this->sanitize($this->post('description')));

            if (empty($title)) {
                $this->view('admin/position-form', ['error' => 'Title is required', 'position' => null, 'action' => 'create']);
                return;
            }
            if ($this->positionModel->findByTitle($title)) {
                $this->view('admin/position-form', ['error' => 'Position already exists', 'position' => ['title' => $title, 'description' => $description], 'action' => 'create']);
                return;
            }
            $this->positionModel->insertOne(['title' => $title, 'description' => $description]);
            $this->setFlash('success', 'Position created successfully');
            $this->redirect('admin/positions');
        }

        $this->view('admin/position-form', ['position' => null, 'action' => 'create', 'error' => null]);
    }

    public function edit(string $id): void {
        $this->requireRole('admin');
        $position = $this->positionModel->findById($id);
        if (!$position) { $this->redirect('admin/positions'); }

        if ($this->isPost()) {
            $title       = trim($this->sanitize($this->post('title')));
            $description = trim($this->sanitize($this->post('description')));

            if (empty($title)) {
                $this->view('admin/position-form', ['error' => 'Title is required', 'position' => $position, 'action' => 'edit']);
                return;
            }
            $this->positionModel->updateById($id, ['title' => $title, 'description' => $description]);
            $this->setFlash('success', 'Position updated successfully');
            $this->redirect('admin/positions');
        }

        $this->view('admin/position-form', ['position' => $position, 'action' => 'edit', 'error' => null]);
    }

    public function delete(string $id): void {
        $this->requireRole('admin');
        $this->positionModel->deleteById($id);
        $this->setFlash('success', 'Position deleted');
        $this->redirect('admin/positions');
    }
}
