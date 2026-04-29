<?php
$pageTitle = 'Client: ' . ($client['name'] ?? '');
require_once __DIR__ . '/../layouts/admin-header.php';
?>

<div class="mb-6">
    <a href="<?= BASE_URL ?>/admin/clients" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-indigo-600">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Clients
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
    <!-- Client Info -->
    <div class="lg:col-span-1 space-y-4">
        <div class="bg-white rounded-xl border border-gray-100 p-5">
            <?php
            $initials = strtoupper(substr($client['name'], 0, 2));
            $statusStyles = ['lead'=>'bg-blue-100 text-blue-700','active'=>'bg-emerald-100 text-emerald-700','completed'=>'bg-gray-100 text-gray-600'];
            $sc = $statusStyles[$client['status']] ?? 'bg-gray-100 text-gray-600';
            ?>
            <div class="flex items-center gap-4 mb-5">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-lg">
                    <?= $initials ?>
                </div>
                <div>
                    <h2 class="font-bold text-gray-800 text-lg"><?= htmlspecialchars($client['name']) ?></h2>
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium <?= $sc ?>"><?= ucfirst($client['status']) ?></span>
                </div>
            </div>

            <div class="space-y-3 text-sm">
                <?php if (!empty($client['company'])): ?>
                <div class="flex items-start gap-3">
                    <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
                    <span class="text-gray-700"><?= htmlspecialchars($client['company']) ?></span>
                </div>
                <?php endif; ?>
                <?php if (!empty($client['email'])): ?>
                <div class="flex items-start gap-3">
                    <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <a href="mailto:<?= htmlspecialchars($client['email']) ?>" class="text-indigo-600 hover:underline"><?= htmlspecialchars($client['email']) ?></a>
                </div>
                <?php endif; ?>
                <?php if (!empty($client['phone'])): ?>
                <div class="flex items-start gap-3">
                    <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    <span class="text-gray-700"><?= htmlspecialchars($client['phone']) ?></span>
                </div>
                <?php endif; ?>
            </div>

            <?php if (!empty($client['projectDetails'])): ?>
            <div class="mt-4 pt-4 border-t border-gray-50">
                <p class="text-xs font-semibold text-gray-500 uppercase mb-2">Project Details</p>
                <p class="text-sm text-gray-700 leading-relaxed"><?= nl2br(htmlspecialchars($client['projectDetails'])) ?></p>
            </div>
            <?php endif; ?>

            <div class="mt-4 pt-4 border-t border-gray-50 flex gap-2">
                <a href="<?= BASE_URL ?>/admin/clients/edit/<?= (string)$client['_id'] ?>"
                   class="flex-1 text-center py-2 text-sm text-indigo-600 border border-indigo-200 rounded-lg hover:bg-indigo-50 transition-colors">Edit</a>
                <a href="<?= BASE_URL ?>/admin/plans/create?clientId=<?= (string)$client['_id'] ?>"
                   class="flex-1 text-center py-2 text-sm text-emerald-600 border border-emerald-200 rounded-lg hover:bg-emerald-50 transition-colors">Add Plan</a>
            </div>
        </div>
    </div>

    <!-- Plans & Payments -->
    <div class="lg:col-span-2 space-y-5">
        <!-- Plans -->
        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-50 flex justify-between items-center">
                <h3 class="text-sm font-semibold text-gray-700">Recurring Plans</h3>
                <a href="<?= BASE_URL ?>/admin/plans/create" class="text-xs text-indigo-600 hover:underline">+ Add Plan</a>
            </div>
            <?php if (empty($plans)): ?>
            <div class="p-8 text-center text-sm text-gray-400">No plans assigned yet</div>
            <?php else: ?>
            <div class="divide-y divide-gray-50">
                <?php foreach ($plans as $plan):
                    $pStatus = ['active'=>'bg-emerald-100 text-emerald-700','paused'=>'bg-amber-100 text-amber-700','cancelled'=>'bg-red-100 text-red-700'];
                    $ps = $pStatus[$plan['status']] ?? 'bg-gray-100 text-gray-600';
                ?>
                <div class="px-5 py-4 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-800"><?= htmlspecialchars($plan['serviceName']) ?></p>
                        <p class="text-xs text-gray-500">₹<?= number_format($plan['amount'], 0) ?> / <?= $plan['billingCycle'] ?> · Next: <?= $plan['nextBillingDate'] ?></p>
                    </div>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium <?= $ps ?>"><?= ucfirst($plan['status']) ?></span>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- Payments -->
        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-50 flex justify-between items-center">
                <h3 class="text-sm font-semibold text-gray-700">Payment History</h3>
                <a href="<?= BASE_URL ?>/admin/payments" class="text-xs text-indigo-600 hover:underline">View All</a>
            </div>
            <?php if (empty($payments)): ?>
            <div class="p-8 text-center text-sm text-gray-400">No payment records</div>
            <?php else: ?>
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500">Month</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500">Amount</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500">Due Date</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500">Status</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php foreach ($payments as $pay):
                        $payStatus = ['paid'=>'bg-emerald-100 text-emerald-700','unpaid'=>'bg-amber-100 text-amber-700','overdue'=>'bg-red-100 text-red-700'];
                        $ps = $payStatus[$pay['status']] ?? 'bg-gray-100 text-gray-600';
                    ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3 text-gray-700"><?= $pay['billingMonth'] ?></td>
                        <td class="px-5 py-3 font-medium text-gray-800">₹<?= number_format($pay['amount'], 0) ?></td>
                        <td class="px-5 py-3 text-gray-600"><?= $pay['dueDate'] ?></td>
                        <td class="px-5 py-3">
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium <?= $ps ?>"><?= ucfirst($pay['status']) ?></span>
                        </td>
                        <td class="px-5 py-3 text-right">
                            <?php if ($pay['status'] !== 'paid'): ?>
                            <a href="<?= BASE_URL ?>/admin/payments/mark-paid/<?= (string)$pay['_id'] ?>"
                               class="text-xs text-emerald-600 hover:underline">Mark Paid</a>
                            <?php else: ?>
                            <a href="<?= BASE_URL ?>/admin/payments/invoice/<?= (string)$pay['_id'] ?>"
                               target="_blank" class="text-xs text-indigo-600 hover:underline">Invoice</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/admin-footer.php'; ?>
