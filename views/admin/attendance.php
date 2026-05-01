<?php
$pageTitle = 'Attendance Management';
require_once __DIR__ . '/../layouts/admin-header.php';
?>

<!-- Filters -->
<div class="bg-white rounded-xl border border-gray-100 p-4 mb-5">
    <form method="GET" action="<?= BASE_URL ?>/admin/attendance" class="flex flex-wrap gap-3">
        <input type="hidden" name="url" value="admin/attendance">
        <div class="flex-1 min-w-36">
            <label class="block text-xs font-medium text-gray-500 mb-1">Month</label>
            <input type="month" name="month" value="<?= $filterMonth ?>"
                   class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-indigo-400">
        </div>
        <div class="flex-1 min-w-36">
            <label class="block text-xs font-medium text-gray-500 mb-1">Employee</label>
            <select name="user" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-indigo-400 bg-white">
                <option value="">All Employees</option>
                <?php foreach ($users as $u): ?>
                <option value="<?= (string)$u['_id'] ?>" <?= $filterUser === (string)$u['_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($u['name']) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="flex-1 min-w-36">
            <label class="block text-xs font-medium text-gray-500 mb-1">Specific Date</label>
            <input type="date" name="date" value="<?= $filterDate ?>"
                   class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-indigo-400">
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors">Filter</button>
            <a href="<?= BASE_URL ?>/admin/attendance" class="px-4 py-2 border border-gray-200 text-gray-600 rounded-lg text-sm hover:bg-gray-50 transition-colors">Reset</a>
        </div>
    </form>
</div>

<!-- Team Hours Summary -->
<?php if (!empty($teamHours)): ?>
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 mb-5">
    <?php foreach ($teamHours as $h):
        $uName = $userMap[$h['_id']] ?? 'Unknown';
    ?>
    <a href="<?= BASE_URL ?>/admin/attendance/user/<?= $h['_id'] ?>?month=<?= $filterMonth ?>"
       class="bg-white rounded-xl border border-gray-100 p-4 hover:border-indigo-300 hover:shadow-sm transition-all block">
        <p class="text-xs font-medium text-gray-500 truncate"><?= htmlspecialchars($uName) ?></p>
        <p class="text-2xl font-bold text-indigo-600 mt-1"><?= round($h['totalHours'], 1) ?>h</p>
        <p class="text-xs text-gray-400"><?= $h['totalDays'] ?> days worked</p>
    </a>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- Attendance Table -->
<div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-50 flex items-center justify-between">
        <h3 class="text-sm font-semibold text-gray-700">Attendance Records</h3>
        <span class="text-xs text-gray-400"><?= count($records) ?> records</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Employee</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Check In</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Check Out</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Total Hours</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php if (empty($records)): ?>
                <tr><td colspan="6" class="px-6 py-12 text-center text-gray-400">No attendance records found</td></tr>
                <?php else: ?>
                <?php foreach ($records as $rec): ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3 font-medium text-gray-800">
                        <?= htmlspecialchars($userMap[$rec['userId']] ?? $rec['userId']) ?>
                    </td>
                    <td class="px-6 py-3 text-gray-600"><?= $rec['date'] ?></td>
                    <?php
                        $aSessions = array_map(fn($s) => (array)$s, (array)($rec['sessions'] ?? []));
                        $firstIn   = $aSessions[0]['in']  ?? null;
                        $lastOut   = null;
                        foreach (array_reverse($aSessions) as $s) {
                            if (!empty($s['out'])) { $lastOut = $s['out']; break; }
                        }
                    ?>
                    <td class="px-6 py-3 text-gray-600"><?= $firstIn  ? htmlspecialchars($firstIn)  : '—' ?></td>
                    <td class="px-6 py-3 text-gray-600"><?= $lastOut  ? htmlspecialchars($lastOut)  : '—' ?></td>
                    <td class="px-6 py-3 font-medium text-gray-700"><?= $rec['totalHours'] ? $rec['totalHours'] . 'h' : '—' ?></td>
                    <td class="px-6 py-3">
                        <?php
                        $statusConfig = [
                            'present' => 'bg-emerald-100 text-emerald-700',
                            'late'    => 'bg-amber-100 text-amber-700',
                            'absent'  => 'bg-red-100 text-red-700',
                        ];
                        $sc = $statusConfig[$rec['status'] ?? 'present'] ?? 'bg-gray-100 text-gray-700';
                        ?>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium <?= $sc ?>">
                            <?= ucfirst($rec['status'] ?? 'present') ?>
                        </span>
                    </td>
                    <td class="px-6 py-3">
                        <a href="<?= BASE_URL ?>/admin/attendance/delete/<?= (string)$rec['_id'] ?>"
                           onclick="return confirm('Delete this record?')"
                           class="text-xs text-red-500 hover:text-red-700">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/admin-footer.php'; ?>
