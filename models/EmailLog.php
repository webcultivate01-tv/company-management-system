<?php
require_once __DIR__ . '/BaseModel.php';

class EmailLog extends BaseModel {
    protected string $collectionName = 'emailLogs';

    public function log(array $data): string {
        $data['sentAt'] = date('Y-m-d H:i:s');
        return $this->insertOne($data);
    }

    public function getRecent(int $limit = 50): array {
        return $this->findAll([], ['sort' => ['createdAt' => -1], 'limit' => $limit]);
    }
}
