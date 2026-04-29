<?php
require_once __DIR__ . '/BaseModel.php';

class Payment extends BaseModel {
    protected string $collectionName = 'payments';

    public function createPayment(array $data): string {
        $data['status']      = 'unpaid';
        $data['paidDate']    = null;
        $data['billingMonth'] = $data['billingMonth'] ?? date('Y-m');
        return $this->insertOne($data);
    }

    public function markAsPaid(string $id): bool {
        return $this->updateById($id, [
            'status'   => 'paid',
            'paidDate' => date('Y-m-d'),
        ]);
    }

    public function updateOverdueStatuses(): void {
        $today = date('Y-m-d');
        $this->collection->updateMany(
            ['status' => 'unpaid', 'dueDate' => ['$lt' => $today]],
            ['$set'   => ['status' => 'overdue', 'updatedAt' => new \MongoDB\BSON\UTCDateTime()]]
        );
    }

    public function getByClient(string $clientId): array {
        return $this->findAll(['clientId' => $clientId], ['sort' => ['dueDate' => -1]]);
    }

    public function getByMonth(string $yearMonth): array {
        return $this->findAll(['billingMonth' => $yearMonth]);
    }

    public function getMonthlyRevenue(string $yearMonth): float {
        $pipeline = [
            ['$match' => ['status' => 'paid', 'billingMonth' => $yearMonth]],
            ['$group' => ['_id' => null, 'total' => ['$sum' => '$amount']]],
        ];
        $result = iterator_to_array($this->collection->aggregate($pipeline), false);
        return (float)($result[0]['total'] ?? 0);
    }

    public function getPendingCount(): int {
        return $this->count(['status' => ['$in' => ['unpaid', 'overdue']]]);
    }

    public function getStats(string $yearMonth): array {
        $this->updateOverdueStatuses();
        return [
            'totalRevenue'   => $this->getMonthlyRevenue($yearMonth),
            'pendingCount'   => $this->getPendingCount(),
            'paidCount'      => $this->count(['status' => 'paid', 'billingMonth' => $yearMonth]),
            'overdueCount'   => $this->count(['status' => 'overdue']),
        ];
    }
}
