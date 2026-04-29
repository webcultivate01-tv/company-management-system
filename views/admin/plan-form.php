<?php
$pageTitle = ($action === 'create') ? 'New Recurring Plan' : 'Edit Plan';
require_once __DIR__ . '/../layouts/admin-header.php';
?>

<div class="max-w-xl mx-auto">
    <div class="mb-6">
        <a href="<?= BASE_URL ?>/admin/plans" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-indigo-600">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Plans
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-6"><?= $pageTitle ?></h2>

        <?php if (!empty($error)): ?>
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm mb-5"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" class="space-y-5">
            <?php if ($action === 'create'): ?>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Client</label>
                <select name="clientId" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-white focus:outline-none focus:border-indigo-400">
                    <option value="">Select client...</option>
                    <?php foreach ($clients as $c): ?>
                    <option value="<?= (string)$c['_id'] ?>" <?= ($plan['clientId'] ?? '') === (string)$c['_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c['name']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php endif; ?>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Service Name</label>
                <input type="text" name="serviceName" required
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400"
                       placeholder="e.g., Website Maintenance, SEO Service"
                       value="<?= htmlspecialchars($plan['serviceName'] ?? '') ?>">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Amount (₹)</label>
                    <input type="number" name="amount" step="0.01" min="0" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400"
                           value="<?= $plan['amount'] ?? '' ?>">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Billing Cycle</label>
                    <select name="billingCycle" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-white focus:outline-none focus:border-indigo-400">
                        <?php foreach (['monthly', 'weekly', 'yearly'] as $cycle): ?>
                        <option value="<?= $cycle ?>" <?= ($plan['billingCycle'] ?? 'monthly') === $cycle ? 'selected' : '' ?>>
                            <?= ucfirst($cycle) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <?php if ($action === 'create'): ?>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Start Date</label>
                <input type="date" name="startDate" required value="<?= date('Y-m-d') ?>"
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400">
            </div>
            <?php else: ?>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Status</label>
                <select name="status" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-white focus:outline-none focus:border-indigo-400">
                    <?php foreach (['active', 'paused', 'cancelled'] as $s): ?>
                    <option value="<?= $s ?>" <?= ($plan['status'] ?? 'active') === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php endif; ?>

            <div class="flex gap-3 pt-2">
                <a href="<?= BASE_URL ?>/admin/plans" class="flex-1 text-center px-4 py-2.5 border border-gray-200 text-gray-600 rounded-xl text-sm hover:bg-gray-50">Cancel</a>
                <button type="submit" class="flex-1 px-4 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-medium hover:bg-indigo-700 transition-colors">
                    <?= $action === 'create' ? 'Create Plan' : 'Save Changes' ?>
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/admin-footer.php'; ?>
