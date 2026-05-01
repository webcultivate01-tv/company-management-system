<?php
$pageTitle = htmlspecialchars($user['name'] ?? 'Employee Detail');
require_once __DIR__ . '/../layouts/admin-header.php';
$u = $user;

function fmtDate($val): string {
    if (!$val) return '—';
    if ($val instanceof \MongoDB\BSON\UTCDateTime) return $val->toDateTime()->format('d M Y');
    return is_string($val) ? date('d M Y', strtotime($val)) : '—';
}
function fmtVal($val): string {
    return !empty($val) ? htmlspecialchars($val) : '<span class="text-gray-400">—</span>';
}

$aadhar       = is_array($u['aadhar'] ?? null) ? $u['aadhar'] : [];
$aadharLocked = !empty($aadhar['front']) && !empty($aadhar['back']);
?>

<!-- Top Bar -->
<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <a href="<?= BASE_URL ?>/admin/users" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-indigo-600 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Employees
    </a>
    <div class="flex items-center gap-2">
        <a href="<?= BASE_URL ?>/admin/users/edit/<?= (string)$u['_id'] ?>"
           class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Edit
        </a>
        <a href="<?= BASE_URL ?>/admin/users/delete/<?= (string)$u['_id'] ?>"
           onclick="return confirm('Delete this employee permanently? All their data will be removed.')"
           class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-xl transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            Delete
        </a>
    </div>
</div>

<?php if (!empty($flash)): ?>
<div class="mb-4 px-4 py-3 rounded-xl text-sm <?= $flash['type'] === 'success' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-50 text-red-700 border border-red-200' ?>">
    <?= htmlspecialchars($flash['message']) ?>
</div>
<?php endif; ?>

<!-- Profile Header -->
<div class="bg-white rounded-2xl border border-gray-100 p-6 mb-6">
    <div class="flex items-start gap-5">
        <img src="<?= htmlspecialchars($u['profileImage'] ?: BASE_URL . '/public/images/avatar.png') ?>"
             class="w-20 h-20 rounded-full object-cover border-2 border-indigo-100 flex-shrink-0" alt="Profile">
        <div class="flex-1 min-w-0">
            <div class="flex flex-wrap items-center gap-3 mb-1">
                <h2 class="text-xl font-bold text-gray-800"><?= htmlspecialchars($u['name']) ?></h2>
                <?php
                $roleColors = ['admin' => 'bg-purple-100 text-purple-700', 'employee' => 'bg-blue-100 text-blue-700', 'intern' => 'bg-cyan-100 text-cyan-700'];
                $rc = $roleColors[$u['role']] ?? 'bg-gray-100 text-gray-700';
                ?>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $rc ?>"><?= ucfirst($u['role']) ?></span>
                <?php if ($u['isActive'] ?? true): ?>
                <span class="inline-flex items-center gap-1.5 text-xs font-medium text-emerald-700"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>Active</span>
                <?php else: ?>
                <span class="inline-flex items-center gap-1.5 text-xs font-medium text-red-600"><span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>Inactive</span>
                <?php endif; ?>
            </div>
            <?php if (!empty($u['position'])): ?>
            <p class="text-indigo-600 font-medium text-sm"><?= htmlspecialchars($u['position']) ?></p>
            <?php endif; ?>
            <?php if (!empty($u['department'])): ?>
            <p class="text-gray-500 text-sm"><?= htmlspecialchars($u['department']) ?> Department</p>
            <?php endif; ?>
            <?php if (!empty($u['bio'])): ?>
            <p class="text-gray-600 text-sm mt-2"><?= htmlspecialchars($u['bio']) ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    <!-- Basic Info -->
    <div class="bg-white rounded-2xl border border-gray-100 p-6">
        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">Basic Information</h3>
        <dl class="space-y-3">
            <div class="flex justify-between text-sm"><dt class="text-gray-500">Full Name</dt><dd class="font-medium text-gray-800"><?= fmtVal($u['name'] ?? '') ?></dd></div>
            <div class="flex justify-between text-sm"><dt class="text-gray-500">Email</dt><dd class="font-medium text-gray-800"><?= fmtVal($u['email'] ?? '') ?></dd></div>
            <div class="flex justify-between text-sm"><dt class="text-gray-500">Phone</dt><dd class="font-medium text-gray-800"><?= fmtVal($u['phone'] ?? '') ?></dd></div>
            <div class="flex justify-between text-sm"><dt class="text-gray-500">Blood Group</dt><dd class="font-medium text-gray-800"><?= fmtVal($u['bloodGroup'] ?? '') ?></dd></div>
            <div class="flex justify-between text-sm"><dt class="text-gray-500">Joining Date</dt><dd class="font-medium text-gray-800"><?= fmtDate($u['joiningDate'] ?? null) ?></dd></div>
            <div class="flex justify-between text-sm"><dt class="text-gray-500">Registered On</dt><dd class="font-medium text-gray-800"><?= fmtDate($u['createdAt'] ?? null) ?></dd></div>
        </dl>
    </div>

    <!-- Job Details -->
    <div class="bg-white rounded-2xl border border-gray-100 p-6">
        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">Job Details</h3>
        <dl class="space-y-3">
            <div class="flex justify-between text-sm"><dt class="text-gray-500">Position</dt><dd class="font-medium text-gray-800"><?= fmtVal($u['position'] ?? '') ?></dd></div>
            <div class="flex justify-between text-sm"><dt class="text-gray-500">Department</dt><dd class="font-medium text-gray-800"><?= fmtVal($u['department'] ?? '') ?></dd></div>
            <div class="flex justify-between text-sm"><dt class="text-gray-500">Monthly Salary</dt><dd class="font-medium text-gray-800"><?= !empty($u['salary']) ? '₹' . number_format((float)$u['salary'], 2) : '<span class="text-gray-400">—</span>' ?></dd></div>
            <div class="text-sm">
                <dt class="text-gray-500 mb-1.5">Skills</dt>
                <dd>
                    <?php if (!empty($u['skills']) && is_array($u['skills'])): ?>
                    <div class="flex flex-wrap gap-1.5">
                        <?php foreach ($u['skills'] as $skill): ?>
                        <span class="px-2.5 py-0.5 bg-indigo-50 text-indigo-700 rounded-full text-xs font-medium"><?= htmlspecialchars($skill) ?></span>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?><span class="text-gray-400">—</span><?php endif; ?>
                </dd>
            </div>
        </dl>
    </div>

    <!-- Address & Identity -->
    <div class="bg-white rounded-2xl border border-gray-100 p-6">
        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">Address & Identity</h3>
        <dl class="space-y-3">
            <div class="text-sm"><dt class="text-gray-500 mb-1">Residential Address</dt><dd class="font-medium text-gray-800"><?= fmtVal($u['address'] ?? '') ?></dd></div>
            <div class="flex justify-between text-sm"><dt class="text-gray-500">National ID / Aadhar No.</dt><dd class="font-medium text-gray-800"><?= fmtVal($u['nationalId'] ?? '') ?></dd></div>
            <div class="flex justify-between text-sm"><dt class="text-gray-500">Bank Account</dt><dd class="font-medium text-gray-800"><?= fmtVal($u['bankAccount'] ?? '') ?></dd></div>
        </dl>
    </div>

    <!-- Emergency Contact -->
    <div class="bg-white rounded-2xl border border-gray-100 p-6">
        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">Emergency Contact</h3>
        <dl class="space-y-3">
            <div class="flex justify-between text-sm"><dt class="text-gray-500">Contact Name</dt><dd class="font-medium text-gray-800"><?= fmtVal($u['emergencyContact'] ?? '') ?></dd></div>
            <div class="flex justify-between text-sm"><dt class="text-gray-500">Contact Phone</dt><dd class="font-medium text-gray-800"><?= fmtVal($u['emergencyPhone'] ?? '') ?></dd></div>
        </dl>
    </div>

    <!-- Aadhar Card — full width -->
    <div class="bg-white rounded-2xl border border-gray-100 p-6 lg:col-span-2">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Aadhar Card</h3>
                <?php if ($aadharLocked): ?>
                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-emerald-100 text-emerald-700 text-xs rounded-full font-medium">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Uploaded
                </span>
                <?php else: ?>
                <span class="px-2 py-0.5 bg-amber-100 text-amber-700 text-xs rounded-full font-medium">Not Uploaded</span>
                <?php endif; ?>
            </div>
            <?php if ($aadharLocked): ?>
            <a href="<?= BASE_URL ?>/admin/users/delete-aadhar/<?= (string)$u['_id'] ?>"
               onclick="return confirm('Remove this employee\'s Aadhar card?')"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-red-600 hover:bg-red-50 border border-red-200 rounded-lg transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                Remove Aadhar
            </a>
            <?php endif; ?>
        </div>

        <?php if ($aadharLocked): ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <p class="text-xs font-medium text-gray-500 mb-2">Front Side</p>
                <img src="<?= htmlspecialchars($aadhar['front']) ?>"
                     class="w-full rounded-xl border border-gray-200 object-cover cursor-pointer hover:opacity-90 transition-opacity"
                     onclick="openImg(this.src)" alt="Aadhar Front">
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 mb-2">Back Side</p>
                <img src="<?= htmlspecialchars($aadhar['back']) ?>"
                     class="w-full rounded-xl border border-gray-200 object-cover cursor-pointer hover:opacity-90 transition-opacity"
                     onclick="openImg(this.src)" alt="Aadhar Back">
            </div>
        </div>
        <p class="text-xs text-gray-400 mt-3">Click image to view full size. Admin can remove and ask employee to re-upload.</p>
        <?php else: ?>
        <p class="text-sm text-gray-400 py-4">This employee has not uploaded their Aadhar card yet.</p>
        <?php endif; ?>
    </div>

</div>

<!-- Lightbox -->
<div id="lightbox" class="fixed inset-0 z-50 hidden bg-black/80 flex items-center justify-center p-4" onclick="this.classList.add('hidden')">
    <img id="lightboxImg" src="" class="max-w-full max-h-full rounded-xl shadow-2xl">
</div>

<script>
function openImg(src) {
    document.getElementById('lightboxImg').src = src;
    document.getElementById('lightbox').classList.remove('hidden');
}
</script>

<?php require_once __DIR__ . '/../layouts/admin-footer.php'; ?>
