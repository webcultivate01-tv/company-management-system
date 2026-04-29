<?php
$pageTitle = 'Services';
require_once __DIR__ . '/../layouts/admin-header.php';
?>

<div class="flex items-center justify-between mb-6">
    <h2 class="text-lg font-semibold text-gray-800">Services</h2>
    <a href="<?= BASE_URL ?>/admin/services/create"
       class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm font-medium hover:bg-indigo-700 transition-colors">
        + Add Service
    </a>
</div>

<?php if (!empty($flash)): ?>
<div class="mb-4 px-4 py-3 rounded-xl text-sm <?= $flash['type'] === 'success' ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' ?>">
    <?= htmlspecialchars($flash['message']) ?>
</div>
<?php endif; ?>

<div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
    <?php if (empty($services)): ?>
    <div class="p-12 text-center text-sm text-gray-400">No services yet. Add your first service.</div>
    <?php else: ?>
    <table class="w-full text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Service Name</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Description</th>
                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            <?php foreach ($services as $s): ?>
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-3 font-medium text-gray-800"><?= htmlspecialchars($s['name']) ?></td>
                <td class="px-6 py-3 text-gray-500"><?= htmlspecialchars($s['description'] ?? '—') ?></td>
                <td class="px-6 py-3 text-right flex justify-end gap-2">
                    <a href="<?= BASE_URL ?>/admin/services/edit/<?= (string)$s['_id'] ?>"
                       class="px-3 py-1.5 text-xs border border-gray-200 rounded-lg hover:bg-gray-50">Edit</a>
                    <a href="<?= BASE_URL ?>/admin/services/delete/<?= (string)$s['_id'] ?>"
                       onclick="return confirm('Delete this service?')"
                       class="px-3 py-1.5 text-xs border border-red-200 text-red-600 rounded-lg hover:bg-red-50">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layouts/admin-footer.php'; ?>
