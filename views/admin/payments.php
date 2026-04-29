<?php
$pageTitle = 'Payments';
require_once __DIR__ . '/../layouts/admin-header.php';
?>

<!-- Stats -->
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-5">
    <div class="bg-white rounded-xl border border-gray-100 p-4">
        <p class="text-xs text-gray-500 mb-1">Revenue</p>
        <p class="text-xl font-bold text-emerald-600">₹<?= number_format($stats['totalRevenue'], 0) ?></p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4">
        <p class="text-xs text-gray-500 mb-1">Paid</p>
        <p class="text-xl font-bold text-indigo-600"><?= $stats['paidCount'] ?></p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4">
        <p class="text-xs text-gray-500 mb-1">Pending</p>
        <p class="text-xl font-bold text-amber-600"><?= $stats['pendingCount'] ?></p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4">
        <p class="text-xs text-gray-500 mb-1">Overdue</p>
        <p class="text-xl font-bold text-red-600"><?= $stats['overdueCount'] ?></p>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-xl border border-gray-100 p-4 mb-5 flex flex-wrap gap-3 items-end">
    <form method="GET" action="<?= BASE_URL ?>/admin/payments" class="flex flex-wrap gap-3 items-end w-full">
        <input type="hidden" name="url" value="admin/payments">
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
        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm hover:bg-indigo-700 transition-colors">Filter</button>
        <a href="<?= BASE_URL ?>/admin/payments/create" class="ml-auto px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm hover:bg-emerald-700 transition-colors">+ Add Payment</a>
    </form>
</div>

<!-- Payments Table -->
<div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Client</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Month</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Due Date</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Paid Date</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php if (empty($payments)): ?>
                <tr><td colspan="7" class="px-6 py-12 text-center text-gray-400">No payment records found</td></tr>
                <?php else: ?>
                <?php foreach ($payments as $pay):
                    $pStatus = ['paid'=>'bg-emerald-100 text-emerald-700','unpaid'=>'bg-amber-100 text-amber-700','overdue'=>'bg-red-100 text-red-700'];
                    $ps = $pStatus[$pay['status']] ?? 'bg-gray-100 text-gray-600';
                    $clientName = $clientMap[$pay['clientId']] ?? 'Unknown';
                ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3 font-medium text-gray-800"><?= htmlspecialchars($clientName) ?></td>
                    <td class="px-6 py-3 font-semibold text-gray-800">₹<?= number_format($pay['amount'], 0) ?></td>
                    <td class="px-6 py-3 text-gray-600"><?= $pay['billingMonth'] ?></td>
                    <td class="px-6 py-3 text-gray-600 <?= ($pay['status'] === 'overdue') ? 'text-red-600 font-medium' : '' ?>"><?= $pay['dueDate'] ?></td>
                    <td class="px-6 py-3 text-gray-600"><?= $pay['paidDate'] ?? '—' ?></td>
                    <td class="px-6 py-3">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium <?= $ps ?>"><?= ucfirst($pay['status']) ?></span>
                    </td>
                    <td class="px-6 py-3 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <?php if ($pay['status'] !== 'paid'): ?>
                            <a href="<?= BASE_URL ?>/admin/payments/mark-paid/<?= (string)$pay['_id'] ?>"
                               class="text-xs font-medium text-emerald-600 hover:text-emerald-700 hover:underline">Mark Paid</a>
                            <?php else: ?>
                            <a href="<?= BASE_URL ?>/admin/payments/invoice/<?= (string)$pay['_id'] ?>"
                               target="_blank"
                               class="text-xs font-medium text-indigo-600 hover:text-indigo-700 hover:underline">Invoice</a>
                            <?php endif; ?>
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
