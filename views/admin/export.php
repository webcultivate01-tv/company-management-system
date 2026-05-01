<?php
$pageTitle = 'Data Export';
require_once __DIR__ . '/../layouts/admin-header.php';
?>

<div class="max-w-4xl mx-auto space-y-6">

    <!-- Clients Export -->
    <div class="bg-white rounded-2xl border border-gray-100 p-6">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-800">Clients</h3>
                <p class="text-xs text-gray-400">Export client list with filters</p>
            </div>
        </div>
        <form class="flex flex-wrap gap-3 items-end">
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                <select name="status" class="px-3 py-2 border border-gray-200 rounded-lg text-sm bg-white focus:outline-none focus:border-indigo-400">
                    <option value="">All Statuses</option>
                    <option value="lead">Lead</option>
                    <option value="active">Active</option>
                    <option value="completed">Completed</option>
                </select>
            </div>
            <div class="flex-1 min-w-48">
                <label class="block text-xs font-medium text-gray-500 mb-1">Search Name / Company</label>
                <input type="text" name="search" placeholder="e.g. Acme Corp"
                       class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-indigo-400">
            </div>
            <button type="button" onclick="doExport('clients','csv',this.closest('form'))"
                    class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-medium">⬇ CSV</button>
            <button type="button" onclick="doExport('clients','pdf',this.closest('form'))"
                    class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-sm font-medium">⬇ PDF</button>
        </form>
    </div>

    <!-- Payments Export -->
    <div class="bg-white rounded-2xl border border-gray-100 p-6">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-800">Payments & Bills</h3>
                <p class="text-xs text-gray-400">Export payment records with filters</p>
            </div>
        </div>
        <form class="flex flex-wrap gap-3 items-end">
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Month</label>
                <input type="month" name="month" value="<?= date('Y-m') ?>"
                       class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-indigo-400">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                <select name="status" class="px-3 py-2 border border-gray-200 rounded-lg text-sm bg-white focus:outline-none focus:border-indigo-400">
                    <option value="">All Statuses</option>
                    <option value="unpaid">Unpaid</option>
                    <option value="paid">Paid</option>
                    <option value="overdue">Overdue</option>
                </select>
            </div>
            <button type="button" onclick="doExport('payments','csv',this.closest('form'))"
                    class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-medium">⬇ CSV</button>
            <button type="button" onclick="doExport('payments','pdf',this.closest('form'))"
                    class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-sm font-medium">⬇ PDF</button>
        </form>
    </div>

    <!-- Employees Export -->
    <div class="bg-white rounded-2xl border border-gray-100 p-6">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-10 h-10 bg-violet-100 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-800">Employees</h3>
                <p class="text-xs text-gray-400">Export all employee data</p>
            </div>
        </div>
        <div class="flex gap-3">
            <a href="<?= BASE_URL ?>/admin/export/employees/csv"
               class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-medium">⬇ CSV</a>
            <a href="<?= BASE_URL ?>/admin/export/employees/pdf" target="_blank"
               class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-sm font-medium">⬇ PDF</a>
        </div>
    </div>

    <!-- Attendance Export -->
    <div class="bg-white rounded-2xl border border-gray-100 p-6">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-800">Attendance</h3>
                <p class="text-xs text-gray-400">Export attendance records with filters</p>
            </div>
        </div>
        <form class="flex flex-wrap gap-3 items-end">
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Month</label>
                <input type="month" name="month" value="<?= date('Y-m') ?>"
                       class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-indigo-400">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Specific Date</label>
                <input type="date" name="date"
                       class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-indigo-400">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Employee</label>
                <select name="user" class="px-3 py-2 border border-gray-200 rounded-lg text-sm bg-white focus:outline-none focus:border-indigo-400">
                    <option value="">All Employees</option>
                    <?php foreach ($users as $u): ?>
                    <option value="<?= (string)$u['_id'] ?>"><?= htmlspecialchars($u['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="button" onclick="doExport('attendance','csv',this.closest('form'))"
                    class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-medium">⬇ CSV</button>
            <button type="button" onclick="doExport('attendance','pdf',this.closest('form'))"
                    class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-sm font-medium">⬇ PDF</button>
        </form>
    </div>

</div>

<script>
function doExport(type, format, form) {
    const params = new URLSearchParams(new FormData(form));
    const url = '<?= BASE_URL ?>/admin/export/' + type + '/' + format + '?' + params.toString();
    format === 'pdf' ? window.open(url, '_blank') : window.location.href = url;
}
</script>

<?php require_once __DIR__ . '/../layouts/admin-footer.php'; ?>
