<?php
$pageTitle = 'Client Management';
require_once __DIR__ . '/../layouts/admin-header.php';
?>

<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-5">
    <form method="GET" action="<?= BASE_URL ?>/admin/clients" class="flex gap-2 flex-1 max-w-md">
        <input type="hidden" name="url" value="admin/clients">
        <div class="relative flex-1">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" name="search" placeholder="Search clients..."
                   class="w-full pl-9 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400"
                   value="<?= htmlspecialchars($search) ?>">
        </div>
        <button type="submit" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm transition-colors">Search</button>
    </form>

    <div class="flex items-center gap-2">
        <!-- Status filter pills -->
        <?php $statuses = ['' => 'All', 'lead' => 'Leads', 'active' => 'Active', 'completed' => 'Completed']; ?>
        <?php foreach ($statuses as $sv => $sl): ?>
        <a href="<?= BASE_URL ?>/admin/clients?url=admin/clients&status=<?= $sv ?>"
           class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors
           <?= $status === $sv ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' ?>">
            <?= $sl ?>
        </a>
        <?php endforeach; ?>
        <a href="<?= BASE_URL ?>/admin/clients/create"
           class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Client
        </a>
    </div>
</div>

<!-- Client Cards Grid -->
<?php if (empty($clients)): ?>
<div class="bg-white rounded-xl border border-gray-100 p-16 text-center">
    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
    </div>
    <p class="text-gray-500">No clients found. <a href="<?= BASE_URL ?>/admin/clients/create" class="text-indigo-600 hover:underline">Add your first client</a></p>
</div>
<?php else: ?>
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
    <?php
    $statusStyles = [
        'lead'      => ['bg' => 'bg-blue-100 text-blue-700',    'dot' => 'bg-blue-500'],
        'active'    => ['bg' => 'bg-emerald-100 text-emerald-700', 'dot' => 'bg-emerald-500'],
        'completed' => ['bg' => 'bg-gray-100 text-gray-600',    'dot' => 'bg-gray-400'],
    ];
    foreach ($clients as $client):
        $st = $statusStyles[$client['status'] ?? 'lead'] ?? $statusStyles['lead'];
        $initials = strtoupper(substr($client['name'], 0, 2));
        $colors = ['from-indigo-500 to-purple-600', 'from-emerald-500 to-teal-600', 'from-orange-500 to-amber-600', 'from-pink-500 to-rose-600', 'from-cyan-500 to-blue-600'];
        $color = $colors[crc32($client['name']) % count($colors)];
    ?>
    <div class="bg-white rounded-xl border border-gray-100 p-5 hover:shadow-md transition-shadow">
        <div class="flex items-start justify-between mb-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br <?= $color ?> flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                    <?= $initials ?>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800 text-sm"><?= htmlspecialchars($client['name']) ?></h3>
                    <p class="text-xs text-gray-500"><?= htmlspecialchars($client['company'] ?? '') ?></p>
                </div>
            </div>
            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium <?= $st['bg'] ?>">
                <span class="w-1.5 h-1.5 rounded-full <?= $st['dot'] ?>"></span>
                <?= ucfirst($client['status']) ?>
            </span>
        </div>

        <div class="space-y-1.5 mb-4 text-xs text-gray-500">
            <?php if (!empty($client['email'])): ?>
            <div class="flex items-center gap-2">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8"/></svg>
                <?= htmlspecialchars($client['email']) ?>
            </div>
            <?php endif; ?>
            <?php if (!empty($client['phone'])): ?>
            <div class="flex items-center gap-2">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                <?= htmlspecialchars($client['phone']) ?>
            </div>
            <?php endif; ?>
        </div>

        <div class="flex items-center gap-2 pt-3 border-t border-gray-50">
            <a href="<?= BASE_URL ?>/admin/clients/view/<?= (string)$client['_id'] ?>"
               class="flex-1 text-center py-1.5 text-xs font-medium text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors">View</a>
            <a href="<?= BASE_URL ?>/admin/clients/edit/<?= (string)$client['_id'] ?>"
               class="flex-1 text-center py-1.5 text-xs font-medium text-gray-600 hover:bg-gray-50 rounded-lg transition-colors">Edit</a>
            <a href="<?= BASE_URL ?>/admin/clients/delete/<?= (string)$client['_id'] ?>"
               onclick="return confirm('Delete this client?')"
               class="flex-1 text-center py-1.5 text-xs font-medium text-red-600 hover:bg-red-50 rounded-lg transition-colors">Delete</a>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<?php require_once __DIR__ . '/../layouts/admin-footer.php'; ?>
