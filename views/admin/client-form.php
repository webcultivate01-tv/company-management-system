<?php
$pageTitle = ($action === 'create') ? 'Add Client' : 'Edit Client';
require_once __DIR__ . '/../layouts/admin-header.php';
?>

<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="<?= BASE_URL ?>/admin/clients" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-indigo-600">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Clients
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-6"><?= $pageTitle ?></h2>

        <?php if (!empty($error)): ?>
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm mb-5"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" class="space-y-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100"
                           value="<?= htmlspecialchars($client['name'] ?? '') ?>">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Company</label>
                    <input type="text" name="company"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100"
                           value="<?= htmlspecialchars($client['company'] ?? '') ?>">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                    <input type="email" name="email"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100"
                           value="<?= htmlspecialchars($client['email'] ?? '') ?>">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Phone</label>
                    <input type="tel" name="phone"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100"
                           value="<?= htmlspecialchars($client['phone'] ?? '') ?>">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Status</label>
                <div class="flex gap-3">
                    <?php foreach (['lead' => 'Lead', 'active' => 'Active', 'completed' => 'Completed'] as $val => $label): ?>
                    <label class="flex-1">
                        <input type="radio" name="status" value="<?= $val ?>" class="sr-only peer"
                               <?= ($client['status'] ?? 'lead') === $val ? 'checked' : '' ?>>
                        <div class="cursor-pointer text-center py-2.5 border-2 border-gray-200 rounded-xl text-sm font-medium text-gray-500 transition-all peer-checked:border-indigo-500 peer-checked:text-indigo-600 peer-checked:bg-indigo-50 hover:border-gray-300">
                            <?= $label ?>
                        </div>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Services</label>
                <div class="grid grid-cols-2 gap-2">
                    <?php foreach ($services as $svc): ?>
                    <?php $svcId = (string)$svc['_id']; ?>
                    <label class="flex items-center gap-2 p-2.5 border border-gray-200 rounded-xl cursor-pointer hover:border-indigo-300 has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50">
                        <input type="checkbox" name="services[]" value="<?= $svcId ?>"
                               <?= in_array($svcId, $client['services'] ?? []) ? 'checked' : '' ?>
                               class="accent-indigo-600">
                        <span class="text-sm text-gray-700"><?= htmlspecialchars($svc['name']) ?></span>
                    </label>
                    <?php endforeach; ?>
                </div>
                <?php if (empty($services)): ?>
                <p class="text-xs text-gray-400 mt-1">No services yet. <a href="<?= BASE_URL ?>/admin/services/create" class="text-indigo-500 hover:underline">Add services first.</a></p>
                <?php endif; ?>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Project Details</label>
                <textarea name="projectDetails" rows="4"
                          class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 resize-none"
                          placeholder="Describe the project scope, requirements, or notes..."><?= htmlspecialchars($client['projectDetails'] ?? '') ?></textarea>
            </div>

            <div class="flex gap-3 pt-2">
                <a href="<?= BASE_URL ?>/admin/clients" class="flex-1 text-center px-4 py-2.5 border border-gray-200 text-gray-600 rounded-xl text-sm hover:bg-gray-50">Cancel</a>
                <button type="submit" class="flex-1 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-medium transition-colors">
                    <?= $action === 'create' ? 'Add Client' : 'Save Changes' ?>
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/admin-footer.php'; ?>
