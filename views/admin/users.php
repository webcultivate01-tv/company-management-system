<?php
$pageTitle = 'User Management';
require_once __DIR__ . '/../layouts/admin-header.php';
?>

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <p class="text-sm text-gray-500"><?= count($users) ?> team members</p>
    </div>
    <a href="<?= BASE_URL ?>/admin/users/create"
       class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl transition-colors shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add New User
    </a>
</div>

<!-- Users Table -->
<div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">User</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Joined</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php if (empty($users)): ?>
                <tr>
                    <td colspan="5" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            </div>
                            <p class="text-gray-500">No users yet. <a href="<?= BASE_URL ?>/admin/users/create" class="text-indigo-600 hover:underline">Add one</a></p>
                        </div>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($users as $user): ?>
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <img src="<?= htmlspecialchars($user['profileImage'] ?: BASE_URL . '/public/images/avatar.png') ?>"
                                 class="w-9 h-9 rounded-full object-cover border border-gray-200 flex-shrink-0"
                                 alt="<?= htmlspecialchars($user['name']) ?>">
                            <div>
                                <p class="font-medium text-gray-800"><?= htmlspecialchars($user['name']) ?></p>
                                <p class="text-xs text-gray-500"><?= htmlspecialchars($user['email']) ?></p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <?php
                        $roleColors = ['admin' => 'bg-purple-100 text-purple-700', 'employee' => 'bg-blue-100 text-blue-700', 'intern' => 'bg-cyan-100 text-cyan-700'];
                        $rc = $roleColors[$user['role']] ?? 'bg-gray-100 text-gray-700';
                        ?>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $rc ?>">
                            <?= ucfirst($user['role']) ?>
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <?php if ($user['isActive'] ?? true): ?>
                        <span class="inline-flex items-center gap-1.5 text-xs font-medium text-emerald-700">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Active
                        </span>
                        <?php else: ?>
                        <span class="inline-flex items-center gap-1.5 text-xs font-medium text-red-600">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Inactive
                        </span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 text-gray-500 text-xs">
                        <?php
                        $createdAt = $user['createdAt'] ?? null;
                        echo $createdAt instanceof \MongoDB\BSON\UTCDateTime
                            ? $createdAt->toDateTime()->format('d M Y')
                            : (is_string($createdAt) ? date('d M Y', strtotime($createdAt)) : '—');
                        ?>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end gap-1">
                            <a href="<?= BASE_URL ?>/admin/users/edit/<?= (string)$user['_id'] ?>"
                               class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <a href="<?= BASE_URL ?>/admin/users/toggle/<?= (string)$user['_id'] ?>"
                               class="p-2 text-gray-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors"
                               title="<?= ($user['isActive'] ?? true) ? 'Deactivate' : 'Activate' ?>">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                            </a>

                            <!-- Reset Password Modal Trigger -->
                            <button onclick="openResetModal('<?= (string)$user['_id'] ?>', '<?= htmlspecialchars($user['name']) ?>')"
                                    class="p-2 text-gray-400 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-colors" title="Reset Password">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                            </button>

                            <a href="<?= BASE_URL ?>/admin/users/delete/<?= (string)$user['_id'] ?>"
                               onclick="return confirm('Delete this user? This cannot be undone.')"
                               class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Reset Password Modal -->
<div id="resetModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50" onclick="closeResetModal()"></div>
    <div class="relative flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                </div>
                <div>
                    <h3 class="text-base font-semibold text-gray-800">Reset Password</h3>
                    <p class="text-xs text-gray-500" id="resetUserName"></p>
                </div>
            </div>
            <div class="bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 text-xs text-amber-700 mb-5">
                ⚠️ The old password cannot be recovered. Set a new password and share it with the employee.
            </div>
            <form id="resetForm" method="POST" novalidate>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">New Password <span class="text-red-500">*</span></label>
                    <input type="text" name="password" id="newPwd" required minlength="6"
                           placeholder="Min 6 characters"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100">
                    <p class="text-red-500 text-xs mt-1 hidden" id="pwdErr">Password must be at least 6 characters</p>
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="closeResetModal()"
                            class="flex-1 px-4 py-2.5 border border-gray-200 text-gray-600 rounded-xl text-sm hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="flex-1 px-4 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-medium hover:bg-indigo-700">Reset Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openResetModal(id, name) {
    document.getElementById('resetModal').classList.remove('hidden');
    document.getElementById('resetUserName').textContent = name;
    document.getElementById('resetForm').action = '<?= BASE_URL ?>/admin/users/reset-password/' + id;
    document.getElementById('newPwd').value = '';
    document.getElementById('pwdErr').classList.add('hidden');
}
function closeResetModal() {
    document.getElementById('resetModal').classList.add('hidden');
}
document.getElementById('resetForm').addEventListener('submit', function(e) {
    const pwd = document.getElementById('newPwd').value;
    if (pwd.length < 6) {
        e.preventDefault();
        document.getElementById('pwdErr').classList.remove('hidden');
    }
});
</script>

<?php require_once __DIR__ . '/../layouts/admin-footer.php'; ?>
