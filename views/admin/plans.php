<?php
$pageTitle = 'Recurring Plans';
require_once __DIR__ . '/../layouts/admin-header.php';
?>

<div class="flex justify-end mb-5">
    <a href="<?= BASE_URL ?>/admin/plans/create"
       class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        New Plan
    </a>
</div>

<div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Client</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Service</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Cycle</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Next Billing</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php if (empty($plans)): ?>
                <tr><td colspan="7" class="px-6 py-12 text-center text-gray-400">No recurring plans yet</td></tr>
                <?php else: ?>
                <?php foreach ($plans as $plan):
                    $statusColors = ['active'=>'bg-emerald-100 text-emerald-700','paused'=>'bg-amber-100 text-amber-700','cancelled'=>'bg-red-100 text-red-700'];
                    $sc = $statusColors[$plan['status']] ?? 'bg-gray-100 text-gray-600';
                    $clientName = $clientMap[$plan['clientId']] ?? 'Unknown';
                ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3 font-medium text-gray-800"><?= htmlspecialchars($clientName) ?></td>
                    <td class="px-6 py-3 text-gray-700"><?= htmlspecialchars($plan['serviceName']) ?></td>
                    <td class="px-6 py-3 font-semibold text-gray-800">₹<?= number_format($plan['amount'], 0) ?></td>
                    <td class="px-6 py-3 text-gray-600 capitalize"><?= $plan['billingCycle'] ?></td>
                    <td class="px-6 py-3 text-gray-600"><?= $plan['nextBillingDate'] ?></td>
                    <td class="px-6 py-3">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium <?= $sc ?>"><?= ucfirst($plan['status']) ?></span>
                    </td>
                    <td class="px-6 py-3 text-right">
                        <div class="flex items-center justify-end gap-1">
                            <a href="<?= BASE_URL ?>/admin/plans/edit/<?= (string)$plan['_id'] ?>"
                               class="p-1.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <a href="<?= BASE_URL ?>/admin/plans/delete/<?= (string)$plan['_id'] ?>"
                               onclick="return confirm('Delete this plan?')"
                               class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </a>
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
