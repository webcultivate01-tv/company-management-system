<?php
$pageTitle = htmlspecialchars($user['name']) . ' — Attendance';
require_once __DIR__ . '/../layouts/admin-header.php';
?>

<!-- Back + Month Selector -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <a href="<?= BASE_URL ?>/admin/attendance" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-indigo-600">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Attendance
    </a>
    <form method="GET" class="flex items-center gap-2">
        <input type="month" name="month" value="<?= $yearMonth ?>"
               class="px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400">
        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm hover:bg-indigo-700 transition-colors">View</button>
    </form>
</div>

<!-- Employee Header -->
<div class="bg-white rounded-2xl border border-gray-100 p-5 mb-6 flex items-center gap-4">
    <img src="<?= htmlspecialchars($user['profileImage'] ?? BASE_URL . '/public/images/avatar.png') ?>"
         class="w-16 h-16 rounded-full object-cover border-2 border-indigo-100" alt="Profile">
    <div>
        <h2 class="text-lg font-bold text-gray-800"><?= htmlspecialchars($user['name']) ?></h2>
        <p class="text-sm text-gray-500"><?= htmlspecialchars($user['email']) ?></p>
        <span class="inline-block mt-1 px-2.5 py-0.5 bg-indigo-50 text-indigo-600 text-xs font-medium rounded-full capitalize"><?= $user['role'] ?></span>
    </div>
    <div class="ml-auto text-right hidden sm:block">
        <p class="text-xs text-gray-400">Report for</p>
        <p class="text-sm font-semibold text-gray-700"><?= date('F Y', strtotime($yearMonth . '-01')) ?></p>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-gray-100 p-4 text-center">
        <p class="text-3xl font-bold text-indigo-600"><?= $stats['totalDays'] ?></p>
        <p class="text-xs text-gray-500 mt-1">Total Days</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4 text-center">
        <p class="text-3xl font-bold text-emerald-600"><?= $presentCount ?></p>
        <p class="text-xs text-gray-500 mt-1">On Time</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4 text-center">
        <p class="text-3xl font-bold text-amber-500"><?= $lateCount ?></p>
        <p class="text-xs text-gray-500 mt-1">Late Days</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4 text-center">
        <p class="text-3xl font-bold text-blue-600"><?= $stats['totalHours'] ?>h</p>
        <p class="text-xs text-gray-500 mt-1">Total Hours</p>
    </div>
</div>

<!-- Second Row Stats -->
<div class="grid grid-cols-2 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-gray-100 p-4 flex items-center gap-4">
        <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center">
            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <p class="text-xs text-gray-500">Avg Hours / Day</p>
            <p class="text-xl font-bold text-gray-800"><?= $avgHours ?>h</p>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4 flex items-center gap-4">
        <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center">
            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
        </div>
        <div>
            <p class="text-xs text-gray-500">Attendance Rate</p>
            <?php
                $daysInMonth = cal_days_in_month(CAL_GREGORIAN, (int)substr($yearMonth,5,2), (int)substr($yearMonth,0,4));
                $rate = $daysInMonth ? round(($stats['totalDays'] / $daysInMonth) * 100) : 0;
            ?>
            <p class="text-xl font-bold text-gray-800"><?= $rate ?>%</p>
        </div>
    </div>
</div>

<!-- Attendance Records Table -->
<div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-50 flex items-center justify-between">
        <h3 class="text-sm font-semibold text-gray-700">Daily Records</h3>
        <span class="text-xs text-gray-400"><?= count($records) ?> records</span>
    </div>
    <?php if (empty($records)): ?>
    <div class="p-12 text-center text-sm text-gray-400">No attendance records for this month.</div>
    <?php else: ?>
    <table class="w-full text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">#</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Date</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Day</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Check In</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Check Out</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Hours</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            <?php foreach (array_reverse($records) as $i => $rec):
                $sc = ['present' => 'bg-emerald-100 text-emerald-700', 'late' => 'bg-amber-100 text-amber-700', 'absent' => 'bg-red-100 text-red-700'];
                $sc = $sc[$rec['status'] ?? 'present'] ?? 'bg-gray-100 text-gray-600';
            ?>
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-3 text-gray-400 text-xs"><?= count($records) - $i ?></td>
                <td class="px-6 py-3 font-medium text-gray-800"><?= $rec['date'] ?></td>
                <td class="px-6 py-3 text-gray-500 text-xs"><?= date('l', strtotime($rec['date'])) ?></td>
                <td class="px-6 py-3 text-gray-700"><?= !empty($rec['checkIn']) ? date('g:i A', strtotime($rec['checkIn'])) : '—' ?></td>
                <td class="px-6 py-3 text-gray-700"><?= !empty($rec['checkOut']) ? date('g:i A', strtotime($rec['checkOut'])) : '—' ?></td>
                <td class="px-6 py-3 font-medium text-gray-700"><?= $rec['totalHours'] ? $rec['totalHours'] . 'h' : '—' ?></td>
                <td class="px-6 py-3">
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium <?= $sc ?>"><?= ucfirst($rec['status'] ?? 'present') ?></span>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layouts/admin-footer.php'; ?>
