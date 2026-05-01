<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Payment.php';
require_once __DIR__ . '/../models/Client.php';
require_once __DIR__ . '/../models/RecurringPlan.php';
require_once __DIR__ . '/../models/Invoice.php';
require_once __DIR__ . '/../config/mail.php';

class PaymentController extends BaseController {
    private Payment       $paymentModel;
    private Client        $clientModel;
    private RecurringPlan $planModel;
    private Invoice       $invoiceModel;

    public function __construct() {
        $this->paymentModel = new Payment();
        $this->clientModel  = new Client();
        $this->planModel    = new RecurringPlan();
        $this->invoiceModel = new Invoice();
    }

    public function bills(): void {
        $this->requireRole('admin');
        $this->paymentModel->updateOverdueStatuses();
        $yearMonth = $this->get('month', date('Y-m'));
        $status    = $this->get('status', '');

        $filter = ['billingMonth' => $yearMonth];
        if ($status) $filter['status'] = $status;

        $payments  = $this->paymentModel->findAll($filter, ['sort' => ['dueDate' => 1]]);
        $clients   = $this->clientModel->findAll();
        $clientMap = [];
        foreach ($clients as $c) { $clientMap[(string)$c['_id']] = $c['name']; }
        $stats = $this->paymentModel->getStats($yearMonth);

        $this->view('admin/bills', [
            'payments'  => $payments,
            'clientMap' => $clientMap,
            'stats'     => $stats,
            'yearMonth' => $yearMonth,
            'status'    => $status,
            'flash'     => $this->getFlash(),
        ]);
    }

    public function index(): void {
        $this->requireRole('admin');
        $this->paymentModel->updateOverdueStatuses();
        $yearMonth = $this->get('month', date('Y-m'));
        $status    = $this->get('status', '');

        $filter = ['billingMonth' => $yearMonth];
        if ($status) $filter['status'] = $status;

        $payments  = $this->paymentModel->findAll($filter, ['sort' => ['dueDate' => 1]]);
        $clients   = $this->clientModel->findAll();
        $clientMap = [];
        foreach ($clients as $c) { $clientMap[(string)$c['_id']] = $c['name']; }

        $stats = $this->paymentModel->getStats($yearMonth);

        $this->view('admin/payments', [
            'payments'   => $payments,
            'clientMap'  => $clientMap,
            'stats'      => $stats,
            'yearMonth'  => $yearMonth,
            'status'     => $status,
            'flash'      => $this->getFlash(),
        ]);
    }

    public function create(): void {
        $this->requireRole('admin');
        $clients = $this->clientModel->findAll([], ['sort' => ['name' => 1]]);

        if ($this->isPost()) {
            $clientId = $this->sanitize($this->post('clientId'));
            $plans    = $this->planModel->getByClient($clientId);
            $data = [
                'clientId'         => $clientId,
                'planId'           => $this->sanitize($this->post('planId')),
                'totalProjectCost' => (float)$this->post('totalProjectCost'),
                'receivedAmount'   => (float)$this->post('receivedAmount'),
                'remainingAmount'  => (float)$this->post('remainingAmount'),
                'receivedDate'     => $this->sanitize($this->post('receivedDate')),
            ];
            $id = $this->paymentModel->createPayment($data);
            $this->setFlash('success', 'Payment record created');
            $this->redirect('admin/payments');
        }

        $this->view('admin/payment-form', ['clients' => $clients, 'flash' => $this->getFlash()]);
    }

    public function markPaid(string $id): void {
        $this->requireRole('admin');
        $this->paymentModel->markAsPaid($id);
        $this->setFlash('success', 'Payment marked as paid');
        $this->redirect('admin/payments');
    }

    public function sendInvoice(string $paymentId): void {
        $this->requireRole('admin');
        $payment = $this->paymentModel->findById($paymentId);
        if (!$payment) { $this->redirect('admin/payments'); }

        $client  = $this->clientModel->findById($payment['clientId']);
        $invoice = $this->invoiceModel->getByPayment($paymentId);

        if (!$invoice) {
            // Auto-generate invoice if not exists
            $invoiceData = [
                'paymentId'    => $paymentId,
                'clientId'     => $payment['clientId'],
                'clientName'   => $client['name'] ?? 'N/A',
                'clientEmail'  => $client['email'] ?? '',
                'amount'       => $payment['amount'],
                'billingMonth' => $payment['billingMonth'],
                'dueDate'      => $payment['dueDate'],
                'status'       => $payment['status'],
            ];
            $invoiceId = $this->invoiceModel->generateInvoice($invoiceData);
            $invoice   = $this->invoiceModel->findById($invoiceId);
        }

        if (empty($client['email'])) {
            $this->setFlash('error', 'Client has no email address');
            $this->redirect('admin/payments');
            return;
        }

        // Build invoice HTML
        $html = $this->buildInvoiceHtml($invoice, $client, $payment);

        try {
            $mail = MailConfig::createMailer();
            $mail->addAddress($client['email'], $client['name'] ?? '');
            $mail->Subject = 'Invoice ' . $invoice['invoiceNumber'] . ' — ' . $invoice['billingMonth'];
            $mail->Body    = $html;
            $mail->send();
            $this->setFlash('success', 'Invoice sent to ' . $client['email']);
        } catch (\Exception $e) {
            $this->setFlash('error', 'Failed to send email: ' . $e->getMessage());
        }
        $this->redirect('admin/payments');
    }

    private function buildInvoiceHtml(array $invoice, array $client, array $payment): string {
        $status       = ucfirst($invoice['status']);
        $statusColor  = $invoice['status'] === 'paid' ? '#059669' : ($invoice['status'] === 'overdue' ? '#dc2626' : '#d97706');
        $amount       = '&#8377;' . number_format($invoice['amount'], 2);
        return '
        <!DOCTYPE html><html><head><meta charset="UTF-8"></head>
        <body style="font-family:Segoe UI,sans-serif;background:#f3f4f6;padding:32px;">
        <div style="max-width:600px;margin:0 auto;background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.08);">
            <div style="background:linear-gradient(135deg,#4f46e5,#7c3aed);padding:40px 32px;">
                <h1 style="color:#fff;margin:0;font-size:24px;">CompanyMS</h1>
                <p style="color:#c7d2fe;margin:4px 0 0;">Invoice ' . htmlspecialchars($invoice['invoiceNumber']) . '</p>
            </div>
            <div style="padding:32px;">
                <table width="100%" style="margin-bottom:24px;">
                    <tr>
                        <td>
                            <p style="color:#6b7280;font-size:12px;margin:0 0 4px;">BILLED TO</p>
                            <p style="font-weight:600;color:#111827;margin:0;">' . htmlspecialchars($client['name'] ?? '') . '</p>
                            <p style="color:#6b7280;font-size:14px;margin:2px 0;">' . htmlspecialchars($client['company'] ?? '') . '</p>
                            <p style="color:#6b7280;font-size:14px;margin:2px 0;">' . htmlspecialchars($client['email'] ?? '') . '</p>
                        </td>
                        <td style="text-align:right;">
                            <p style="color:#6b7280;font-size:12px;margin:0 0 4px;">INVOICE DETAILS</p>
                            <p style="font-size:14px;color:#374151;margin:2px 0;">Date: ' . $invoice['generatedAt'] . '</p>
                            <p style="font-size:14px;color:#374151;margin:2px 0;">Due: ' . $invoice['dueDate'] . '</p>
                            <p style="font-size:14px;color:#374151;margin:2px 0;">Month: ' . $invoice['billingMonth'] . '</p>
                        </td>
                    </tr>
                </table>
                <table width="100%" style="border:1px solid #e5e7eb;border-radius:8px;overflow:hidden;border-collapse:collapse;margin-bottom:24px;">
                    <thead>
                        <tr style="background:#f9fafb;">
                            <th style="padding:12px 16px;text-align:left;font-size:12px;color:#6b7280;">DESCRIPTION</th>
                            <th style="padding:12px 16px;text-align:right;font-size:12px;color:#6b7280;">AMOUNT</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="border-top:1px solid #e5e7eb;">
                            <td style="padding:16px;"><strong>Service Payment</strong><br><span style="font-size:12px;color:#9ca3af;">Billing period: ' . $invoice['billingMonth'] . '</span></td>
                            <td style="padding:16px;text-align:right;font-weight:600;">' . $amount . '</td>
                        </tr>
                        <tr style="background:#f9fafb;border-top:1px solid #e5e7eb;">
                            <td style="padding:16px;font-weight:600;">Total</td>
                            <td style="padding:16px;text-align:right;font-size:20px;font-weight:700;color:#4f46e5;">' . $amount . '</td>
                        </tr>
                    </tbody>
                </table>
                <div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;padding:16px;display:flex;justify-content:space-between;">
                    <span style="font-weight:600;">Payment Status</span>
                    <span style="font-weight:700;color:' . $statusColor . ';">' . $status . '</span>
                </div>
                <p style="text-align:center;color:#9ca3af;font-size:12px;margin-top:24px;">Thank you for your business. For queries, contact your account manager.</p>
            </div>
        </div>
        </body></html>';
    }

    public function generateInvoice(string $paymentId): void {
        $this->requireRole('admin');
        $payment = $this->paymentModel->findById($paymentId);
        if (!$payment) { $this->redirect('admin/payments'); }

        $existing = $this->invoiceModel->getByPayment($paymentId);
        if ($existing) {
            $this->setFlash('info', 'Invoice already exists: ' . $existing['invoiceNumber']);
            $this->redirect('admin/payments');
        }

        $client = $this->clientModel->findById($payment['clientId']);
        $invoiceData = [
            'paymentId'    => $paymentId,
            'clientId'     => $payment['clientId'],
            'clientName'   => $client['name'] ?? 'N/A',
            'clientEmail'  => $client['email'] ?? '',
            'amount'       => $payment['amount'],
            'billingMonth' => $payment['billingMonth'],
            'dueDate'      => $payment['dueDate'],
            'status'       => $payment['status'],
        ];
        $invoiceId = $this->invoiceModel->generateInvoice($invoiceData);
        $invoice   = $this->invoiceModel->findById($invoiceId);

        // Render invoice as HTML for printing
        $this->view('admin/invoice-print', ['invoice' => $invoice, 'client' => $client, 'payment' => $payment]);
    }
}
