<?php
require_once __DIR__ . '/BaseModel.php';

class Position extends BaseModel {
    protected string $collectionName = 'positions';

    public function getAllPositions(): array {
        return $this->findAll([], ['sort' => ['title' => 1]]);
    }

    public function findByTitle(string $title): ?array {
        $result = $this->collection->findOne(['title' => $title]);
        return $result ? (array)$result : null;
    }
}
