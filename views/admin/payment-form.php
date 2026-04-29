<?php
$pageTitle = 'Create Payment';
require_once __DIR__ . '/../layouts/admin-header.php';
?>

<div class="max-w-xl mx-auto">
    <div class="mb-6">
        <a href="<?= BASE_URL ?>/admin/payments" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-indigo-600">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Payments
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-6">New Payment Record</h2>
        <form method="POST" class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Client</label>
                <select name="clientId" required id="clientSelect"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-white focus:outline-none focus:border-indigo-400">
                    <option value="">Select client...</option>
                    <?php foreach ($clients as $c): ?>
                    <option value="<?= (string)$c['_id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Plan ID (optional)</label>
                <input type="text" name="planId"
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400"
                       placeholder="Enter recurring plan ID if applicable">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Amount (₹)</label>
                    <input type="number" name="amount" step="0.01" required min="0"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Billing Month</label>
                    <input type="month" name="billingMonth" value="<?= date('Y-m') ?>" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Due Date</label>
                <input type="date" name="dueDate" required
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400"
                       value="<?= date('Y-m-d', strtotime('+7 days')) ?>">
            </div>
            <div class="flex gap-3 pt-2">
                <a href="<?= BASE_URL ?>/admin/payments" class="flex-1 text-center px-4 py-2.5 border border-gray-200 text-gray-600 rounded-xl text-sm hover:bg-gray-50">Cancel</a>
                <button type="submit" class="flex-1 px-4 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-medium hover:bg-indigo-700 transition-colors">Create Payment</button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/admin-footer.php'; ?>
