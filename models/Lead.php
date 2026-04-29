<?php
require_once __DIR__ . '/BaseModel.php';

class Lead extends BaseModel {
    protected string $collectionName = 'leads';

    public function getByUser(string $userId): array {
        return $this->findAll(['addedBy' => $userId], ['sort' => ['createdAt' => -1]]);
    }

    public function getStats(): array {
        return [
            'total'        => $this->count(),
            'interested'   => $this->count(['status' => 'interested']),
            'notInterested'=> $this->count(['status' => 'not_interested']),
        ];
    }

    public function getStatsByUser(string $userId): array {
        return [
            'total'        => $this->count(['addedBy' => $userId]),
            'interested'   => $this->count(['addedBy' => $userId, 'status' => 'interested']),
            'notInterested'=> $this->count(['addedBy' => $userId, 'status' => 'not_interested']),
        ];
    }
}
