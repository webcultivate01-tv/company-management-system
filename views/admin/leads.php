<?php
$pageTitle = 'Sales & Leads';
require_once __DIR__ . '/../layouts/admin-header.php';
?>

<!-- Stats -->
<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-gray-100 p-5 text-center">
        <p class="text-3xl font-bold text-indigo-600"><?= $stats['total'] ?></p>
        <p class="text-sm text-gray-500 mt-1">Total Leads</p>
    </div>
    <div class="bg-white rounded-xl border border-emerald-100 p-5 text-center">
        <p class="text-3xl font-bold text-emerald-600"><?= $stats['interested'] ?></p>
        <p class="text-sm text-gray-500 mt-1">Interested</p>
    </div>
    <div class="bg-white rounded-xl border border-red-100 p-5 text-center">
        <p class="text-3xl font-bold text-red-500"><?= $stats['notInterested'] ?></p>
        <p class="text-sm text-gray-500 mt-1">Not Interested</p>
    </div>
</div>

<!-- Per Employee Stats -->
<div class="bg-white rounded-xl border border-gray-100 overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-gray-50">
        <h3 class="text-sm font-semibold text-gray-700">Who Called How Many People</h3>
    </div>
    <?php
        $maxCalls = max(array_map(fn($u) => $userStats[(string)$u['_id']]['total'] ?? 0, $users) ?: [1]);
    ?>
    <div class="divide-y divide-gray-50">
        <?php foreach ($users as $u):
            $uid = (string)$u['_id'];
            $us  = $userStats[$uid] ?? ['total' => 0, 'interested' => 0, 'notInterested' => 0];
            $pct = $maxCalls > 0 ? round(($us['total'] / $maxCalls) * 100) : 0;
        ?>
        <div class="px-6 py-4">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xs">
                        <?= strtoupper(substr($u['name'], 0, 1)) ?>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-800"><?= htmlspecialchars($u['name']) ?></p>
                        <p class="text-xs text-gray-400 capitalize"><?= $u['role'] ?></p>
                    </div>
                </div>
                <div class="flex items-center gap-4 text-xs">
                    <span class="font-bold text-indigo-600"><?= $us['total'] ?> calls</span>
                    <span class="text-emerald-600 font-medium">✅ <?= $us['interested'] ?></span>
                    <span class="text-red-500 font-medium">❌ <?= $us['notInterested'] ?></span>
                    <?php if ($us['total'] > 0): ?>
                    <a href="<?= BASE_URL ?>/admin/leads?user=<?= $uid ?>"
                       class="px-3 py-1 border border-indigo-200 text-indigo-600 rounded-lg hover:bg-indigo-50">View</a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-2">
                <div class="h-2 rounded-full bg-indigo-500 transition-all" style="width: <?= $pct ?>%"></div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- All Leads -->
<div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-50 flex items-center justify-between flex-wrap gap-3">
        <h3 class="text-sm font-semibold text-gray-700">All Leads <?= $filterUser ? '— ' . htmlspecialchars(array_values(array_filter($users, fn($u) => (string)$u['_id'] === $filterUser))[0]['name'] ?? '') : '' ?></h3>
        <div class="flex gap-2">
            <?php if ($filterUser): ?>
            <a href="<?= BASE_URL ?>/admin/leads" class="text-xs px-3 py-1.5 border border-gray-200 rounded-lg hover:bg-gray-50">Show All</a>
            <?php endif; ?>
            <span class="text-xs text-gray-400 self-center"><?= count($leads) ?> records</span>
        </div>
    </div>
    <?php if (empty($leads)): ?>
    <div class="p-12 text-center text-sm text-gray-400">No leads found.</div>
    <?php else: ?>
    <div class="divide-y divide-gray-50">
        <?php foreach ($leads as $lead):
            $interested = $lead['status'] === 'interested';
        ?>
        <div class="px-6 py-4 flex items-center justify-between gap-4 hover:bg-gray-50">
            <div class="flex items-center gap-4">
                <div class="w-9 h-9 rounded-full flex items-center justify-center text-white font-bold text-sm <?= $interested ? 'bg-emerald-500' : 'bg-red-400' ?>">
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
                <span class="text-xs text-gray-400"><?= htmlspecialchars($lead['addedByName'] ?? '') ?></span>
                <span class="px-3 py-1 rounded-full text-xs font-semibold <?= $interested ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-600' ?>">
                    <?= $interested ? 'Interested' : 'Not Interested' ?>
                </span>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layouts/admin-footer.php'; ?>
