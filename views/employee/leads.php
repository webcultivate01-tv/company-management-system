<?php
$pageTitle = 'My Leads';
require_once __DIR__ . '/../layouts/employee-header.php';
?>

<div class="flex items-center justify-between mb-6">
    <h2 class="text-lg font-semibold text-gray-800">My Leads</h2>
    <a href="<?= BASE_URL ?>/employee/leads/create"
       class="px-4 py-2 bg-violet-600 text-white rounded-xl text-sm font-medium hover:bg-violet-700 transition-colors">
        + Add Lead
    </a>
</div>

<?php if (!empty($flash)): ?>
<div class="mb-4 px-4 py-3 rounded-xl text-sm <?= $flash['type'] === 'success' ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' ?>">
    <?= htmlspecialchars($flash['message']) ?>
</div>
<?php endif; ?>

<!-- Stats -->
<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-gray-100 p-4 text-center">
        <p class="text-2xl font-bold text-violet-600"><?= $stats['total'] ?></p>
        <p class="text-xs text-gray-500 mt-1">Total Leads</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4 text-center">
        <p class="text-2xl font-bold text-emerald-600"><?= $stats['interested'] ?></p>
        <p class="text-xs text-gray-500 mt-1">Interested</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4 text-center">
        <p class="text-2xl font-bold text-red-500"><?= $stats['notInterested'] ?></p>
        <p class="text-xs text-gray-500 mt-1">Not Interested</p>
    </div>
</div>

<!-- Leads List -->
<div class="space-y-3">
    <?php if (empty($leads)): ?>
    <div class="bg-white rounded-xl border border-gray-100 p-12 text-center text-sm text-gray-400">
        No leads yet. Start by adding your first lead.
    </div>
    <?php else: ?>
    <?php foreach ($leads as $lead): ?>
    <?php $interested = $lead['status'] === 'interested'; ?>
    <div class="bg-white rounded-xl border <?= $interested ? 'border-emerald-200' : 'border-red-200' ?> p-4 flex items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-sm <?= $interested ? 'bg-emerald-500' : 'bg-red-400' ?>">
                <?= strtoupper(substr($lead['name'], 0, 1)) ?>
            </div>
            <div>
                <p class="font-semibold text-gray-800"><?= htmlspecialchars($lead['name']) ?></p>
                <p class="text-sm text-gray-500"><?= htmlspecialchars($lead['mobile']) ?> <?= !empty($lead['business']) ? '· ' . htmlspecialchars($lead['business']) : '' ?></p>
                <?php if (!empty($lead['notes'])): ?>
                <p class="text-xs text-gray-400 mt-0.5"><?= htmlspecialchars($lead['notes']) ?></p>
                <?php endif; ?>
            </div>
        </div>
        <div class="flex items-center gap-3 flex-shrink-0">
            <span class="px-3 py-1 rounded-full text-xs font-semibold <?= $interested ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-600' ?>">
                <?= $interested ? 'Interested' : 'Not Interested' ?>
            </span>
            <a href="<?= BASE_URL ?>/employee/leads/edit/<?= (string)$lead['_id'] ?>"
               class="text-xs px-3 py-1.5 border border-gray-200 rounded-lg hover:bg-gray-50">Edit</a>
            <a href="<?= BASE_URL ?>/employee/leads/delete/<?= (string)$lead['_id'] ?>"
               onclick="return confirm('Delete this lead?')"
               class="text-xs px-3 py-1.5 border border-red-200 text-red-500 rounded-lg hover:bg-red-50">Delete</a>
        </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layouts/employee-footer.php'; ?>
