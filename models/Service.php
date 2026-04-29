<?php
require_once __DIR__ . '/BaseModel.php';

class Service extends BaseModel {
    protected string $collectionName = 'services';

    public function getAllServices(): array {
        return $this->findAll([], ['sort' => ['name' => 1]]);
    }
}
