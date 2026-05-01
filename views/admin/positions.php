<?php
$pageTitle = 'Positions';
require_once __DIR__ . '/../layouts/admin-header.php';
?>

<div class="flex items-center justify-between mb-6">
    <p class="text-sm text-gray-500"><?= count($positions) ?> positions</p>
    <a href="<?= BASE_URL ?>/admin/positions/create"
       class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl transition-colors shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Position
    </a>
</div>

<?php if (!empty($flash)): ?>
<div class="mb-4 px-4 py-3 rounded-xl text-sm <?= $flash['type'] === 'success' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-50 text-red-700 border border-red-200' ?>">
    <?= htmlspecialchars($flash['message']) ?>
</div>
<?php endif; ?>

<div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100">
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Title</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Description</th>
                <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            <?php if (empty($positions)): ?>
            <tr>
                <td colspan="3" class="px-6 py-12 text-center text-gray-400 text-sm">No positions yet. <a href="<?= BASE_URL ?>/admin/positions/create" class="text-indigo-600 hover:underline">Add one</a></td>
            </tr>
            <?php else: ?>
            <?php foreach ($positions as $pos): ?>
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 font-medium text-gray-800"><?= htmlspecialchars($pos['title']) ?></td>
                <td class="px-6 py-4 text-gray-500"><?= htmlspecialchars($pos['description'] ?? '—') ?></td>
                <td class="px-6 py-4">
                    <div class="flex items-center justify-end gap-1">
                        <a href="<?= BASE_URL ?>/admin/positions/edit/<?= (string)$pos['_id'] ?>"
                           class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Edit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </a>
                        <a href="<?= BASE_URL ?>/admin/positions/delete/<?= (string)$pos['_id'] ?>"
                           onclick="return confirm('Delete this position?')"
                           class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete">
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

<?php require_once __DIR__ . '/../layouts/admin-footer.php'; ?>
