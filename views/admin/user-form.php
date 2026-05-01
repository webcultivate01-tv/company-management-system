<?php
$pageTitle = ($action === 'create') ? 'Register Employee' : 'Edit Employee';
require_once __DIR__ . '/../layouts/admin-header.php';
$u = $user ?? [];
$skills = is_array($u['skills'] ?? null) ? implode(', ', $u['skills']) : ($u['skills'] ?? '');
?>

<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="<?= BASE_URL ?>/admin/users" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-indigo-600 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Employees
        </a>
    </div>

    <?php if (!empty($error)): ?>
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm mb-5">
        <?= htmlspecialchars($error) ?>
    </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="space-y-6">

        <!-- Profile Photo + Basic Info -->
        <div class="bg-white rounded-2xl border border-gray-100 p-6">
            <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-5">Basic Information</h3>
            <div class="flex items-start gap-6 mb-6">
                <div class="relative flex-shrink-0">
                    <img id="previewImg"
                         src="<?= htmlspecialchars($u['profileImage'] ?? BASE_URL . '/public/images/avatar.png') ?>"
                         class="w-24 h-24 rounded-full object-cover border-2 border-indigo-100" alt="Profile">
                    <label for="profileImage" class="absolute -bottom-1 -right-1 w-7 h-7 bg-indigo-600 rounded-full flex items-center justify-center cursor-pointer shadow-md hover:bg-indigo-700 transition-colors">
                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </label>
                    <input type="file" id="profileImage" name="profileImage" accept="image/*" class="hidden" onchange="previewImage(this)">
                </div>
                <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100"
                               value="<?= htmlspecialchars($u['name'] ?? '') ?>">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Email Address <span class="text-red-500">*</span></label>
                        <input type="email" name="email" required
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 <?= $action === 'edit' ? 'bg-gray-50' : '' ?>"
                               value="<?= htmlspecialchars($u['email'] ?? '') ?>"
                               <?= $action === 'edit' ? 'readonly' : '' ?>>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Phone Number</label>
                        <input type="text" name="phone"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100"
                               value="<?= htmlspecialchars($u['phone'] ?? '') ?>">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Blood Group</label>
                        <select name="bloodGroup" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 bg-white">
                            <option value="">Select</option>
                            <?php foreach (['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg): ?>
                            <option value="<?= $bg ?>" <?= ($u['bloodGroup'] ?? '') === $bg ? 'selected' : '' ?>><?= $bg ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <?php if ($action === 'create'): ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" required minlength="6"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100"
                           placeholder="Minimum 6 characters">
                </div>
            </div>
            <?php endif; ?>

            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Bio / About</label>
                <textarea name="bio" rows="2"
                          class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 resize-none"
                          placeholder="Short bio..."><?= htmlspecialchars($u['bio'] ?? '') ?></textarea>
            </div>
        </div>

        <!-- Job Details -->
        <div class="bg-white rounded-2xl border border-gray-100 p-6">
            <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-5">Job Details</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Role <span class="text-red-500">*</span></label>
                    <select name="role" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 bg-white">
                        <option value="employee" <?= ($u['role'] ?? '') === 'employee' ? 'selected' : '' ?>>Employee</option>
                        <option value="intern"   <?= ($u['role'] ?? '') === 'intern'   ? 'selected' : '' ?>>Intern</option>
                        <option value="admin"    <?= ($u['role'] ?? '') === 'admin'    ? 'selected' : '' ?>>Admin</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Position</label>
                    <select name="position" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 bg-white">
                        <option value="">— Select Position —</option>
                        <?php foreach ($positions as $pos): ?>
                        <option value="<?= htmlspecialchars($pos['title']) ?>"
                            <?= ($u['position'] ?? '') === $pos['title'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($pos['title']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Department</label>
                    <input type="text" name="department"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100"
                           placeholder="e.g. Engineering"
                           value="<?= htmlspecialchars($u['department'] ?? '') ?>">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Joining Date</label>
                    <input type="date" name="joiningDate"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100"
                           value="<?= htmlspecialchars($u['joiningDate'] ?? '') ?>">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Monthly Salary (₹)</label>
                    <input type="number" name="salary" min="0" step="0.01"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100"
                           value="<?= htmlspecialchars($u['salary'] ?? '') ?>">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Skills <span class="text-xs text-gray-400">(comma separated)</span></label>
                    <input type="text" name="skills"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100"
                           placeholder="PHP, React, MongoDB"
                           value="<?= htmlspecialchars($skills) ?>">
                </div>
            </div>
        </div>

        <!-- Address & Identity -->
        <div class="bg-white rounded-2xl border border-gray-100 p-6">
            <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-5">Address & Identity</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Residential Address</label>
                    <textarea name="address" rows="2"
                              class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 resize-none"
                              placeholder="Full address..."><?= htmlspecialchars($u['address'] ?? '') ?></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">National ID / Aadhar</label>
                    <input type="text" name="nationalId"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100"
                           value="<?= htmlspecialchars($u['nationalId'] ?? '') ?>">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Bank Account Number</label>
                    <input type="text" name="bankAccount"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100"
                           value="<?= htmlspecialchars($u['bankAccount'] ?? '') ?>">
                </div>
            </div>
        </div>

        <!-- Emergency Contact -->
        <div class="bg-white rounded-2xl border border-gray-100 p-6">
            <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-5">Emergency Contact</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Contact Name</label>
                    <input type="text" name="emergencyContact"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100"
                           placeholder="e.g. Parent / Spouse"
                           value="<?= htmlspecialchars($u['emergencyContact'] ?? '') ?>">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Contact Phone</label>
                    <input type="text" name="emergencyPhone"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100"
                           value="<?= htmlspecialchars($u['emergencyPhone'] ?? '') ?>">
                </div>
            </div>
        </div>

        <div class="flex gap-3">
            <a href="<?= BASE_URL ?>/admin/users"
               class="flex-1 text-center px-4 py-2.5 border border-gray-200 text-gray-600 rounded-xl text-sm hover:bg-gray-50 transition-colors">Cancel</a>
            <button type="submit"
                    class="flex-1 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-medium transition-colors">
                <?= $action === 'create' ? 'Register Employee' : 'Save Changes' ?>
            </button>
        </div>
    </form>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => document.getElementById('previewImg').src = e.target.result;
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php require_once __DIR__ . '/../layouts/admin-footer.php'; ?>
