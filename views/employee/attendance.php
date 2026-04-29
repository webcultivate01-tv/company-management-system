<?php
$pageTitle = 'My Attendance';
require_once __DIR__ . '/../layouts/employee-header.php';
?>

<!-- Month Selector -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-5">
    <form method="GET" action="<?= BASE_URL ?>/employee/attendance" class="flex items-center gap-2">
        <input type="hidden" name="url" value="employee/attendance">
        <input type="month" name="month" value="<?= $yearMonth ?>"
               class="px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-violet-400">
        <button type="submit" class="px-4 py-2 bg-violet-600 text-white rounded-xl text-sm hover:bg-violet-700 transition-colors">View</button>
    </form>

    <div class="flex gap-2">
        <?php if (empty($today)): ?>
        <a href="<?= BASE_URL ?>/employee/check-in"
           class="px-4 py-2 bg-violet-600 text-white rounded-xl text-sm font-medium hover:bg-violet-700 transition-colors">
            Check In Now
        </a>
        <?php elseif (empty($today['checkOut'])): ?>
        <a href="<?= BASE_URL ?>/employee/check-out"
           class="px-4 py-2 bg-emerald-600 text-white rounded-xl text-sm font-medium hover:bg-emerald-700 transition-colors">
            Check Out Now
        </a>
        <?php else: ?>
        <span class="px-4 py-2 bg-gray-100 text-gray-500 rounded-xl text-sm">Completed for today</span>
        <?php endif; ?>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-3 gap-4 mb-5">
    <div class="bg-white rounded-xl border border-gray-100 p-4 text-center">
        <p class="text-2xl font-bold text-violet-600"><?= $stats['totalDays'] ?></p>
        <p class="text-xs text-gray-500 mt-1">Days Present</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4 text-center">
        <p class="text-2xl font-bold text-indigo-600"><?= $stats['totalHours'] ?>h</p>
        <p class="text-xs text-gray-500 mt-1">Total Hours</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4 text-center">
        <p class="text-2xl font-bold text-amber-600"><?= $stats['lateCount'] ?></p>
        <p class="text-xs text-gray-500 mt-1">Late Days</p>
    </div>
</div>

<!-- Attendance Table -->
<div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-50">
        <h3 class="text-sm font-semibold text-gray-700">
            Attendance — <?= date('F Y', strtotime($yearMonth . '-01')) ?>
        </h3>
    </div>
    <?php if (empty($records)): ?>
    <div class="p-12 text-center">
        <div class="w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
            <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </div>
        <p class="text-gray-400 text-sm">No attendance records for this month</p>
    </div>
    <?php else: ?>
    <table class="w-full text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Date</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Day</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Check In</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Check Out</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Hours</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            <?php foreach (array_reverse($records) as $rec):
                $sc = ['present'=>'bg-emerald-100 text-emerald-700','late'=>'bg-amber-100 text-amber-700','absent'=>'bg-red-100 text-red-700'];
                $sc = $sc[$rec['status']??'present'] ?? 'bg-gray-100 text-gray-600';
                $dayName = date('D', strtotime($rec['date']));
            ?>
            <tr class="hover:bg-gray-50 <?= $rec['date'] === date('Y-m-d') ? 'bg-violet-50 border-l-2 border-l-violet-500' : '' ?>">
                <td class="px-5 py-3 font-medium text-gray-800"><?= $rec['date'] ?></td>
                <td class="px-5 py-3 text-gray-500 text-xs"><?= $dayName ?></td>
                <td class="px-5 py-3 text-gray-700"><?= !empty($rec['checkIn']) ? date('g:i A', strtotime($rec['checkIn'])) : '—' ?></td>
                <td class="px-5 py-3 text-gray-700"><?= !empty($rec['checkOut']) ? date('g:i A', strtotime($rec['checkOut'])) : '—' ?></td>
                <td class="px-5 py-3 font-medium text-gray-700"><?= $rec['totalHours'] ? $rec['totalHours'].'h' : '—' ?></td>
                <td class="px-5 py-3">
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium <?= $sc ?>"><?= ucfirst($rec['status']??'present') ?></span>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layouts/employee-footer.php'; ?>
