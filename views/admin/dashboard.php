<?php
$pageTitle = 'Dashboard';
require_once __DIR__ . '/../layouts/admin-header.php';
?>

<!-- Stats Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-6">
    <div class="stat-card bg-white rounded-xl p-5 border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="w-11 h-11 bg-indigo-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
            <span class="text-xs font-medium text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-full">Active Team</span>
        </div>
        <p class="text-3xl font-bold text-gray-800"><?= $totalUsers ?></p>
        <p class="text-sm text-gray-500 mt-1">Total Employees</p>
    </div>

    <div class="stat-card bg-white rounded-xl p-5 border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="w-11 h-11 bg-emerald-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <span class="text-xs font-medium text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full"><?= $clientStats['active'] ?> Active</span>
        </div>
        <p class="text-3xl font-bold text-gray-800"><?= $clientStats['total'] ?></p>
        <p class="text-sm text-gray-500 mt-1">Total Clients</p>
    </div>

    <div class="stat-card bg-white rounded-xl p-5 border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="w-11 h-11 bg-amber-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span class="text-xs font-medium text-amber-600 bg-amber-50 px-2.5 py-1 rounded-full"><?= $yearMonth ?></span>
        </div>
        <p class="text-3xl font-bold text-gray-800">₹<?= number_format($paymentStats['totalRevenue'], 0) ?></p>
        <p class="text-sm text-gray-500 mt-1">Monthly Revenue</p>
    </div>

    <div class="stat-card bg-white rounded-xl p-5 border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="w-11 h-11 bg-red-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <span class="text-xs font-medium text-red-600 bg-red-50 px-2.5 py-1 rounded-full"><?= $paymentStats['overdueCount'] ?> Overdue</span>
        </div>
        <p class="text-3xl font-bold text-gray-800"><?= $paymentStats['pendingCount'] ?></p>
        <p class="text-sm text-gray-500 mt-1">Pending Payments</p>
    </div>
</div>

<!-- Second Row -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-6">
    <div class="bg-white rounded-xl p-5 border border-gray-100">
        <h3 class="text-sm font-semibold text-gray-700 mb-4">Today's Attendance</h3>
        <div class="flex items-center justify-center py-4">
            <div class="relative w-32 h-32">
                <svg class="w-32 h-32 transform -rotate-90" viewBox="0 0 120 120">
                    <circle cx="60" cy="60" r="50" fill="none" stroke="#e2e8f0" stroke-width="10"/>
                    <?php $pct = $totalUsers > 0 ? ($todayPresent / $totalUsers) * 314 : 0; ?>
                    <circle cx="60" cy="60" r="50" fill="none" stroke="#6366f1" stroke-width="10"
                            stroke-dasharray="<?= $pct ?> 314" stroke-linecap="round"/>
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <span class="text-2xl font-bold text-gray-800"><?= $todayPresent ?></span>
                    <span class="text-xs text-gray-500">of <?= $totalUsers ?></span>
                </div>
            </div>
        </div>
        <p class="text-center text-sm text-gray-500"><?= $todayPresent ?> checked in today</p>
        <a href="<?= BASE_URL ?>/admin/attendance" class="mt-3 block text-center text-xs text-indigo-600 hover:underline">View all →</a>
    </div>

    <div class="bg-white rounded-xl p-5 border border-gray-100">
        <h3 class="text-sm font-semibold text-gray-700 mb-4">Client Pipeline</h3>
        <div class="space-y-3">
            <?php foreach ([['Leads','leads','blue'],['Active','active','emerald'],['Completed','completed','gray']] as [$label,$key,$col]): ?>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-<?= $col ?>-400"></div>
                    <span class="text-sm text-gray-600"><?= $label ?></span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="h-2 bg-<?= $col ?>-100 rounded-full overflow-hidden w-24">
                        <div class="h-full bg-<?= $col ?>-400 rounded-full" style="width:<?= $clientStats['total'] > 0 ? ($clientStats[$key]/$clientStats['total']*100) : 0 ?>%"></div>
                    </div>
                    <span class="text-sm font-medium text-gray-700 w-6 text-right"><?= $clientStats[$key] ?></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <a href="<?= BASE_URL ?>/admin/clients" class="mt-4 block text-center text-xs text-indigo-600 hover:underline">Manage clients →</a>
    </div>

    <div class="bg-white rounded-xl p-5 border border-gray-100">
        <h3 class="text-sm font-semibold text-gray-700 mb-4">Team Hours This Month</h3>
        <div class="flex items-center justify-center py-6">
            <div class="text-center">
                <p class="text-4xl font-bold text-indigo-600"><?= $totalTeamHours ?></p>
                <p class="text-sm text-gray-500 mt-1">Total Hours Logged</p>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-3 mt-2">
            <div class="text-center bg-indigo-50 rounded-lg p-3">
                <p class="text-lg font-bold text-indigo-700"><?= $paymentStats['paidCount'] ?></p>
                <p class="text-xs text-gray-500">Paid Invoices</p>
            </div>
            <div class="text-center bg-amber-50 rounded-lg p-3">
                <p class="text-lg font-bold text-amber-700"><?= $paymentStats['pendingCount'] ?></p>
                <p class="text-xs text-gray-500">Pending Bills</p>
            </div>
        </div>
    </div>
</div>

<!-- Top Performers + Quick Actions -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
    <div class="lg:col-span-1">
        <?php $accentColor = 'indigo'; require __DIR__ . '/../layouts/top-performers.php'; ?>
    </div>
    <div class="lg:col-span-2 bg-white rounded-xl p-5 border border-gray-100">
        <h3 class="text-sm font-semibold text-gray-700 mb-4">Quick Actions</h3>
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
            <?php
            $actions = [
                ['label' => 'Add Employee', 'href' => 'admin/users/create',   'color' => 'indigo',  'icon' => 'M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z'],
                ['label' => 'Add Client',   'href' => 'admin/clients/create', 'color' => 'emerald', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                ['label' => 'Add Plan',     'href' => 'admin/plans/create',   'color' => 'purple',  'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                ['label' => 'Payments',     'href' => 'admin/payments',       'color' => 'amber',   'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['label' => 'Attendance',   'href' => 'admin/attendance',     'color' => 'cyan',    'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'],
                ['label' => 'Send Email',   'href' => 'admin/email',          'color' => 'rose',    'icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
            ];
            $colorMap = [
                'indigo'  => 'bg-indigo-50 hover:bg-indigo-100 text-indigo-700',
                'emerald' => 'bg-emerald-50 hover:bg-emerald-100 text-emerald-700',
                'purple'  => 'bg-purple-50 hover:bg-purple-100 text-purple-700',
                'amber'   => 'bg-amber-50 hover:bg-amber-100 text-amber-700',
                'cyan'    => 'bg-cyan-50 hover:bg-cyan-100 text-cyan-700',
                'rose'    => 'bg-rose-50 hover:bg-rose-100 text-rose-700',
            ];
            foreach ($actions as $action):
                $cls = $colorMap[$action['color']];
            ?>
            <a href="<?= BASE_URL ?>/<?= $action['href'] ?>"
               class="flex flex-col items-center gap-2 p-4 rounded-xl <?= $cls ?> transition-colors text-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?= $action['icon'] ?>"/>
                </svg>
                <span class="text-xs font-medium"><?= $action['label'] ?></span>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/admin-footer.php'; ?>
