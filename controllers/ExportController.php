<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Client.php';
require_once __DIR__ . '/../models/Payment.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Attendance.php';

class ExportController extends BaseController {
    private Client     $clientModel;
    private Payment    $paymentModel;
    private User       $userModel;
    private Attendance $attendanceModel;

    public function __construct() {
        $this->clientModel     = new Client();
        $this->paymentModel    = new Payment();
        $this->userModel       = new User();
        $this->attendanceModel = new Attendance();
    }

    // ── Clients ──────────────────────────────────────────────
    public function clients(string $format): void {
        $this->requireRole('admin');
        $status  = $this->get('status', '');
        $search  = $this->get('search', '');
        $filter  = [];
        if ($status) $filter['status'] = $status;
        if ($search) $filter['$or'] = [
            ['name'    => ['$regex' => new \MongoDB\BSON\Regex($search, 'i')]],
            ['company' => ['$regex' => new \MongoDB\BSON\Regex($search, 'i')]],
            ['email'   => ['$regex' => new \MongoDB\BSON\Regex($search, 'i')]],
        ];
        $rows = $this->clientModel->findAll($filter, ['sort' => ['name' => 1]]);

        $headers = ['Name', 'Company', 'Email', 'Phone', 'Status', 'Address'];
        $data    = array_map(fn($r) => [
            $r['name']    ?? '',
            $r['company'] ?? '',
            $r['email']   ?? '',
            $r['phone']   ?? '',
            $r['status']  ?? '',
            $r['address'] ?? '',
        ], $rows);

        $format === 'pdf'
            ? $this->renderPdf('Clients', $headers, $data)
            : $this->renderCsv('clients', $headers, $data);
    }

    // ── Payments ─────────────────────────────────────────────
    public function payments(string $format): void {
        $this->requireRole('admin');
        $yearMonth = $this->get('month', date('Y-m'));
        $status    = $this->get('status', '');
        $filter    = ['billingMonth' => $yearMonth];
        if ($status) $filter['status'] = $status;

        $rows    = $this->paymentModel->findAll($filter, ['sort' => ['dueDate' => 1]]);
        $clients = $this->clientModel->findAll();
        $map     = [];
        foreach ($clients as $c) $map[(string)$c['_id']] = $c['name'];

        $headers = ['Client', 'Project Cost (₹)', 'Received (₹)', 'Remaining (₹)', 'Bill Amount (₹)', 'Billing Month', 'Due Date', 'Paid Date', 'Status'];
        $data    = array_map(fn($r) => [
            $map[$r['clientId']] ?? $r['clientId'],
            $r['totalProjectCost'] ?? 0,
            $r['receivedAmount']   ?? 0,
            $r['remainingAmount']  ?? 0,
            $r['amount'],
            $r['billingMonth'],
            $r['dueDate'],
            $r['paidDate'] ?? '—',
            ucfirst($r['status']),
        ], $rows);

        $format === 'pdf'
            ? $this->renderPdf('Payments — ' . $yearMonth, $headers, $data)
            : $this->renderCsv('payments_' . $yearMonth, $headers, $data);
    }

    // ── Employees ────────────────────────────────────────────
    public function employees(string $format): void {
        $this->requireRole('admin');
        $rows    = $this->userModel->getAllUsers();
        $headers = ['Name', 'Email', 'Role', 'Position', 'Phone', 'Status', 'Joined'];
        $data    = array_map(fn($r) => [
            $r['name']     ?? '',
            $r['email']    ?? '',
            ucfirst($r['role'] ?? ''),
            $r['position'] ?? '',
            $r['phone']    ?? '',
            ($r['isActive'] ?? true) ? 'Active' : 'Inactive',
            $this->formatDate($r['createdAt'] ?? null),
        ], $rows);

        $format === 'pdf'
            ? $this->renderPdf('Employees', $headers, $data)
            : $this->renderCsv('employees', $headers, $data);
    }

    // ── Attendance ───────────────────────────────────────────
    public function attendance(string $format): void {
        $this->requireRole('admin');
        $yearMonth  = $this->get('month', date('Y-m'));
        $filterUser = $this->get('user', '');
        $filterDate = $this->get('date', '');

        $filter = [];
        if ($filterDate)       $filter['date']   = $filterDate;
        elseif ($yearMonth)    $filter['date']   = ['$regex' => new \MongoDB\BSON\Regex('^' . preg_quote($yearMonth), '')];
        if ($filterUser)       $filter['userId'] = $filterUser;

        $rows  = $this->attendanceModel->getAllAttendance($filter);
        $users = $this->userModel->getAllUsers();
        $map   = [];
        foreach ($users as $u) $map[(string)$u['_id']] = $u['name'];

        $headers = ['Employee', 'Date', 'Check In', 'Check Out', 'Total Hours', 'Status'];
        $data    = array_map(function($r) use ($map) {
            $sessions = array_map(fn($s) => (array)$s, (array)($r['sessions'] ?? []));
            $firstIn  = $sessions[0]['in'] ?? '—';
            $lastOut  = '—';
            foreach (array_reverse($sessions) as $s) {
                if (!empty($s['out'])) { $lastOut = $s['out']; break; }
            }
            return [
                $map[$r['userId']] ?? $r['userId'],
                $r['date'],
                $firstIn,
                $lastOut,
                ($r['totalHours'] ?? 0) . 'h',
                ucfirst($r['status'] ?? ''),
            ];
        }, $rows);

        $format === 'pdf'
            ? $this->renderPdf('Attendance — ' . ($filterDate ?: $yearMonth), $headers, $data)
            : $this->renderCsv('attendance_' . ($filterDate ?: $yearMonth), $headers, $data);
    }

    // ── Helpers ──────────────────────────────────────────────
    private function renderCsv(string $filename, array $headers, array $rows): void {
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '_' . date('Ymd') . '.csv"');
        header('Pragma: no-cache');
        $out = fopen('php://output', 'w');
        fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF)); // UTF-8 BOM for Excel
        fputcsv($out, $headers);
        foreach ($rows as $row) fputcsv($out, $row);
        fclose($out);
        exit;
    }

    private function renderPdf(string $title, array $headers, array $rows): void {
        header('Content-Type: text/html; charset=UTF-8');
        $th = implode('', array_map(fn($h) => '<th style="padding:8px 12px;background:#4f46e5;color:#fff;text-align:left;font-size:12px;white-space:nowrap">' . htmlspecialchars($h) . '</th>', $headers));
        $tbody = '';
        foreach ($rows as $i => $row) {
            $bg = $i % 2 === 0 ? '#fff' : '#f9fafb';
            $td = implode('', array_map(fn($c) => '<td style="padding:7px 12px;font-size:12px;border-bottom:1px solid #e5e7eb">' . htmlspecialchars((string)$c) . '</td>', $row));
            $tbody .= '<tr style="background:' . $bg . '">' . $td . '</tr>';
        }
        echo '<!DOCTYPE html><html><head><meta charset="UTF-8">
        <title>' . htmlspecialchars($title) . '</title>
        <style>body{font-family:Segoe UI,sans-serif;padding:24px;color:#111}
        h2{color:#4f46e5;margin-bottom:4px}
        p.sub{color:#6b7280;font-size:12px;margin-bottom:16px}
        table{border-collapse:collapse;width:100%}
        @media print{.no-print{display:none}}
        </style></head><body>
        <div class="no-print" style="margin-bottom:16px">
            <button onclick="window.print()" style="padding:8px 20px;background:#4f46e5;color:#fff;border:none;border-radius:8px;cursor:pointer;font-size:13px">🖨 Print / Save PDF</button>
            <button onclick="history.back()" style="padding:8px 20px;background:#f3f4f6;color:#374151;border:none;border-radius:8px;cursor:pointer;font-size:13px;margin-left:8px">← Back</button>
        </div>
        <h2>' . htmlspecialchars($title) . '</h2>
        <p class="sub">Generated on ' . date('d M Y H:i') . ' &nbsp;|&nbsp; ' . count($rows) . ' records</p>
        <table><thead><tr>' . $th . '</tr></thead><tbody>' . $tbody . '</tbody></table>
        <script>window.onload=function(){window.print()}</script>
        </body></html>';
        exit;
    }

    private function formatDate(mixed $val): string {
        if (!$val) return '—';
        if ($val instanceof \MongoDB\BSON\UTCDateTime) return $val->toDateTime()->format('d M Y');
        return is_string($val) ? date('d M Y', strtotime($val)) : '—';
    }
}
