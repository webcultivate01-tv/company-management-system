<?php
$pageTitle = 'Bills & Invoices';
require_once __DIR__ . '/../layouts/admin-header.php';
?>

<?php if (!empty($flash)): ?>
<div class="mb-5 px-4 py-3 rounded-xl text-sm <?= $flash['type'] === 'success' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-50 text-red-700 border border-red-200' ?>">
    <?= htmlspecialchars($flash['message']) ?>
</div>
<?php endif; ?>

<!-- Stats -->
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-gray-100 p-5">
        <p class="text-xs text-gray-500 mb-1">Total Project Cost</p>
        <p class="text-2xl font-bold text-indigo-600">₹<?= number_format(array_sum(array_column($payments, 'totalProjectCost')), 0) ?></p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-5">
        <p class="text-xs text-gray-500 mb-1">Total Received</p>
        <p class="text-2xl font-bold text-emerald-600">₹<?= number_format(array_sum(array_column($payments, 'receivedAmount')), 0) ?></p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-5">
        <p class="text-xs text-gray-500 mb-1">Total Remaining</p>
        <p class="text-2xl font-bold text-amber-500">₹<?= number_format(array_sum(array_column($payments, 'remainingAmount')), 0) ?></p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-5">
        <p class="text-xs text-gray-500 mb-1">Overdue</p>
        <p class="text-2xl font-bold text-red-600"><?= $stats['overdueCount'] ?></p>
    </div>
</div>

<!-- Filters + Add -->
<div class="bg-white rounded-xl border border-gray-100 p-4 mb-5">
    <form method="GET" action="<?= BASE_URL ?>/admin/bills" class="flex flex-wrap gap-3 items-end">
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Month</label>
            <input type="month" name="month" value="<?= $yearMonth ?>"
                   class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-indigo-400">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
            <select name="status" class="px-3 py-2 border border-gray-200 rounded-lg text-sm bg-white focus:outline-none focus:border-indigo-400">
                <option value="">All</option>
                <option value="unpaid"  <?= $status === 'unpaid'  ? 'selected' : '' ?>>Unpaid</option>
                <option value="paid"    <?= $status === 'paid'    ? 'selected' : '' ?>>Paid</option>
                <option value="overdue" <?= $status === 'overdue' ? 'selected' : '' ?>>Overdue</option>
            </select>
        </div>
        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm hover:bg-indigo-700">Filter</button>
        <a href="<?= BASE_URL ?>/admin/payments/create" class="ml-auto px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm hover:bg-emerald-700">+ Add Bill</a>
    </form>
</div>

<!-- Bills Table -->
<div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-50 flex items-center justify-between">
        <h3 class="text-sm font-semibold text-gray-700">All Bills</h3>
        <span class="text-xs text-gray-400"><?= count($payments) ?> records</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Client</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Project Cost</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Received</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Remaining</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Received Date</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php if (empty($payments)): ?>
                <tr><td colspan="7" class="px-6 py-12 text-center text-gray-400">No bills found for this period</td></tr>
                <?php else: ?>
                <?php foreach ($payments as $pay):
                    $pStatus = ['paid' => 'bg-emerald-100 text-emerald-700', 'unpaid' => 'bg-amber-100 text-amber-700', 'overdue' => 'bg-red-100 text-red-700'];
                    $ps = $pStatus[$pay['status']] ?? 'bg-gray-100 text-gray-600';
                    $clientName = $clientMap[$pay['clientId']] ?? 'Unknown';
                ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3 font-medium text-gray-800"><?= htmlspecialchars($clientName) ?></td>
                    <td class="px-6 py-3 font-semibold text-indigo-700">₹<?= number_format($pay['totalProjectCost'] ?? 0, 0) ?></td>
                    <td class="px-6 py-3 font-semibold text-emerald-700">₹<?= number_format($pay['receivedAmount'] ?? 0, 0) ?></td>
                    <td class="px-6 py-3 font-semibold text-amber-600">₹<?= number_format($pay['remainingAmount'] ?? 0, 0) ?></td>
                    <td class="px-6 py-3 text-gray-600"><?= $pay['receivedDate'] ?? ($pay['dueDate'] ?? '—') ?></td>
                    <td class="px-6 py-3">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium <?= $ps ?>"><?= ucfirst($pay['status']) ?></span>
                    </td>
                    <td class="px-6 py-3">
                        <div class="flex items-center justify-end gap-2">
                            <?php if ($pay['status'] !== 'paid'): ?>
                            <a href="<?= BASE_URL ?>/admin/payments/mark-paid/<?= (string)$pay['_id'] ?>"
                               class="text-xs px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg hover:bg-emerald-100">Mark Paid</a>
                            <?php endif; ?>
                            <a href="<?= BASE_URL ?>/admin/payments/invoice/<?= (string)$pay['_id'] ?>"
                               target="_blank"
                               class="text-xs px-2.5 py-1 bg-indigo-50 text-indigo-700 border border-indigo-200 rounded-lg hover:bg-indigo-100">Invoice</a>
                            <a href="<?= BASE_URL ?>/admin/payments/send-invoice/<?= (string)$pay['_id'] ?>"
                               onclick="return confirm('Send invoice to client email?')"
                               class="text-xs px-2.5 py-1 bg-violet-50 text-violet-700 border border-violet-200 rounded-lg hover:bg-violet-100">📧 Send</a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/admin-footer.php'; ?>
