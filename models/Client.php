<?php
require_once __DIR__ . '/BaseModel.php';

class Client extends BaseModel {
    protected string $collectionName = 'clients';

    public function createClient(array $data): string {
        $data['status'] = in_array($data['status'] ?? '', ['lead','active','completed']) ? $data['status'] : 'lead';
        $data['email']  = strtolower(trim($data['email'] ?? ''));
        return $this->insertOne($data);
    }

    public function getByStatus(string $status): array {
        return $this->findAll(['status' => $status], ['sort' => ['createdAt' => -1]]);
    }

    public function searchClients(string $query): array {
        $regex = new \MongoDB\BSON\Regex($query, 'i');
        return $this->findAll([
            '$or' => [
                ['name'    => $regex],
                ['email'   => $regex],
                ['company' => $regex],
            ]
        ]);
    }

    public function getStats(): array {
        return [
            'total'     => $this->count(),
            'leads'     => $this->count(['status' => 'lead']),
            'active'    => $this->count(['status' => 'active']),
            'completed' => $this->count(['status' => 'completed']),
        ];
    }
}
