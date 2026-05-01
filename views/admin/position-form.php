<?php
$pageTitle = ($action === 'create') ? 'Add Position' : 'Edit Position';
require_once __DIR__ . '/../layouts/admin-header.php';
?>

<div class="max-w-lg mx-auto">
    <div class="mb-6">
        <a href="<?= BASE_URL ?>/admin/positions" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-indigo-600 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Positions
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-6"><?= $pageTitle ?></h2>

        <?php if (!empty($error)): ?>
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm mb-5">
            <?= htmlspecialchars($error) ?>
        </div>
        <?php endif; ?>

        <form method="POST" class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Position Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" required
                       placeholder="e.g. Frontend Developer"
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100"
                       value="<?= htmlspecialchars($position['title'] ?? '') ?>">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Description</label>
                <textarea name="description" rows="3"
                          placeholder="Brief description of this role..."
                          class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 resize-none"><?= htmlspecialchars($position['description'] ?? '') ?></textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <a href="<?= BASE_URL ?>/admin/positions"
                   class="flex-1 text-center px-4 py-2.5 border border-gray-200 text-gray-600 rounded-xl text-sm hover:bg-gray-50 transition-colors">Cancel</a>
                <button type="submit"
                        class="flex-1 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-medium transition-colors">
                    <?= $action === 'create' ? 'Create Position' : 'Save Changes' ?>
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/admin-footer.php'; ?>
