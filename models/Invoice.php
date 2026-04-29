<?php
require_once __DIR__ . '/BaseModel.php';

class Invoice extends BaseModel {
    protected string $collectionName = 'invoices';

    public function generateInvoice(array $data): string {
        $invoiceNumber = 'INV-' . strtoupper(uniqid());
        $data['invoiceNumber'] = $invoiceNumber;
        $data['generatedAt']   = date('Y-m-d');
        $data['status']        = $data['status'] ?? 'pending';
        return $this->insertOne($data);
    }

    public function getByClient(string $clientId): array {
        return $this->findAll(['clientId' => $clientId], ['sort' => ['generatedAt' => -1]]);
    }

    public function getByPayment(string $paymentId): ?array {
        $result = $this->collection->findOne(['paymentId' => $paymentId]);
        return $result ? (array)$result : null;
    }
}
