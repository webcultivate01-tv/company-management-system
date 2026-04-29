<?php
require_once __DIR__ . '/BaseModel.php';

class RecurringPlan extends BaseModel {
    protected string $collectionName = 'recurringPlans';

    public function createPlan(array $data): string {
        $data['status']          = $data['status'] ?? 'active';
        $data['billingCycle']    = in_array($data['billingCycle'] ?? '', ['monthly','weekly','yearly'])
                                    ? $data['billingCycle'] : 'monthly';
        $data['nextBillingDate'] = $this->computeNextBillingDate($data['startDate'], $data['billingCycle']);
        return $this->insertOne($data);
    }

    private function computeNextBillingDate(string $startDate, string $cycle): string {
        $date = new \DateTime($startDate);
        match ($cycle) {
            'weekly'  => $date->modify('+1 week'),
            'yearly'  => $date->modify('+1 year'),
            default   => $date->modify('+1 month'),
        };
        return $date->format('Y-m-d');
    }

    public function getByClient(string $clientId): array {
        return $this->findAll(['clientId' => $clientId], ['sort' => ['createdAt' => -1]]);
    }

    public function getActivePlans(): array {
        return $this->findAll(['status' => 'active']);
    }

    public function getDuePlans(): array {
        $today = date('Y-m-d');
        return $this->findAll([
            'status'          => 'active',
            'nextBillingDate' => ['$lte' => $today],
        ]);
    }
}
