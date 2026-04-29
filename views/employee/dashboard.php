<?php
$pageTitle = 'My Dashboard';
require_once __DIR__ . '/../layouts/employee-header.php';
$canCheckIn  = empty($today);
$canCheckOut = !empty($today) && empty($today['checkOut']);
?>

<!-- Welcome Banner -->
<div class="bg-gradient-to-r from-violet-600 to-purple-700 rounded-2xl p-6 mb-6 text-white">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-violet-200 text-sm">Good <?= (int)date('H') < 12 ? 'morning' : ((int)date('H') < 17 ? 'afternoon' : 'evening') ?>,</p>
            <h2 class="text-2xl font-bold mt-0.5"><?= htmlspecialchars($_SESSION['name']) ?> 👋</h2>
            <p class="text-violet-200 text-sm mt-1"><?= date('l, d F Y') ?></p>
        </div>
        <img src="<?= htmlspecialchars($_SESSION['image'] ?: BASE_URL . '/public/images/avatar.png') ?>"
             class="w-16 h-16 rounded-2xl object-cover border-2 border-white/30 hidden sm:block" alt="Profile">
    </div>
</div>

<!-- Check In/Out Card -->
<div class="bg-white rounded-xl border border-gray-100 p-5 mb-6">
    <h3 class="text-sm font-semibold text-gray-700 mb-4">Today's Attendance</h3>

    <?php if (!empty($today)): ?>
    <div class="grid grid-cols-3 gap-4 mb-4">
        <div class="text-center p-3 bg-violet-50 rounded-xl">
            <p class="text-xs text-gray-500 mb-1">Check In</p>
            <p class="text-lg font-bold text-violet-700"><?= !empty($today['checkIn']) ? date('g:i A', strtotime($today['checkIn'])) : '—' ?></p>
        </div>
        <div class="text-center p-3 bg-emerald-50 rounded-xl">
            <p class="text-xs text-gray-500 mb-1">Check Out</p>
            <p class="text-lg font-bold text-emerald-700"><?= !empty($today['checkOut']) ? date('g:i A', strtotime($today['checkOut'])) : '—' ?></p>
        </div>
        <div class="text-center p-3 bg-blue-50 rounded-xl">
            <p class="text-xs text-gray-500 mb-1">Total Hours</p>
            <p class="text-lg font-bold text-blue-700"><?= $today['totalHours'] ? $today['totalHours'] . 'h' : '—' ?></p>
        </div>
    </div>
    <?php endif; ?>

    <div class="flex gap-3">
        <?php if ($canCheckIn): ?>
        <a href="<?= BASE_URL ?>/employee/check-in"
           class="flex-1 flex items-center justify-center gap-2 py-3 bg-violet-600 hover:bg-violet-700 text-white font-medium rounded-xl text-sm transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
            Check In
        </a>
        <?php else: ?>
        <div class="flex-1 flex items-center justify-center gap-2 py-3 bg-gray-100 text-gray-400 font-medium rounded-xl text-sm cursor-not-allowed">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <?= !empty($today) ? 'Checked In' : 'Check In' ?>
        </div>
        <?php endif; ?>

        <?php if ($canCheckOut): ?>
        <a href="<?= BASE_URL ?>/employee/check-out"
           class="flex-1 flex items-center justify-center gap-2 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-xl text-sm transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            Check Out
        </a>
        <?php else: ?>
        <div class="flex-1 flex items-center justify-center gap-2 py-3 bg-gray-100 text-gray-400 font-medium rounded-xl text-sm cursor-not-allowed">
            Check Out
        </div>
        <?php endif; ?>
    </div>

    <?php if (!empty($today) && !empty($today['status'])): ?>
    <div class="mt-3 text-center">
        <?php
        $statusConfig = ['present' => 'text-emerald-600 bg-emerald-50', 'late' => 'text-amber-600 bg-amber-50'];
        $sc = $statusConfig[$today['status']] ?? 'text-gray-600 bg-gray-50';
        ?>
        <span class="inline-block px-3 py-1 rounded-full text-xs font-medium <?= $sc ?>">
            <?= ucfirst($today['status']) ?> today
        </span>
    </div>
    <?php endif; ?>
</div>

<!-- Monthly Stats -->
<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-gray-100 p-4 text-center">
        <p class="text-2xl font-bold text-violet-600"><?= $stats['totalDays'] ?></p>
        <p class="text-xs text-gray-500 mt-1">Days Worked</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4 text-center">
        <p class="text-2xl font-bold text-indigo-600"><?= $stats['totalHours'] ?></p>
        <p class="text-xs text-gray-500 mt-1">Total Hours</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4 text-center">
        <p class="text-2xl font-bold text-amber-600"><?= $stats['lateCount'] ?></p>
        <p class="text-xs text-gray-500 mt-1">Late Arrivals</p>
    </div>
</div>

<!-- Recent Attendance -->
<div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-50 flex justify-between items-center">
        <h3 class="text-sm font-semibold text-gray-700">Recent Attendance</h3>
        <a href="<?= BASE_URL ?>/employee/attendance" class="text-xs text-violet-600 hover:underline">View all →</a>
    </div>
    <?php if (empty($recent)): ?>
    <div class="p-8 text-center text-sm text-gray-400">No attendance records this month</div>
    <?php else: ?>
    <table class="w-full text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500">Date</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500">In</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500">Out</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500">Hours</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            <?php foreach (array_reverse($recent) as $rec):
                $sc = ['present'=>'bg-emerald-100 text-emerald-700','late'=>'bg-amber-100 text-amber-700','absent'=>'bg-red-100 text-red-700'];
                $sc = $sc[$rec['status']??'present'] ?? 'bg-gray-100 text-gray-600';
            ?>
            <tr class="hover:bg-gray-50">
                <td class="px-5 py-3 text-gray-600"><?= $rec['date'] ?></td>
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
