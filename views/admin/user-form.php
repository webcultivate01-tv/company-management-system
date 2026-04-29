<?php
$pageTitle = ($action === 'create') ? 'Add New User' : 'Edit User';
require_once __DIR__ . '/../layouts/admin-header.php';
?>

<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="<?= BASE_URL ?>/admin/users" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-indigo-600 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Users
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-6"><?= $pageTitle ?></h2>

        <?php if (!empty($error)): ?>
        <div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm mb-5">
            <?= htmlspecialchars($error) ?>
        </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="space-y-5">
            <!-- Profile Image -->
            <div class="flex items-center gap-5">
                <div class="relative">
                    <img id="previewImg"
                         src="<?= htmlspecialchars($user['profileImage'] ?? BASE_URL . '/public/images/avatar.png') ?>"
                         class="w-20 h-20 rounded-full object-cover border-2 border-indigo-100"
                         alt="Profile">
                    <label for="profileImage" class="absolute -bottom-1 -right-1 w-7 h-7 bg-indigo-600 rounded-full flex items-center justify-center cursor-pointer shadow-md hover:bg-indigo-700 transition-colors">
                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </label>
                    <input type="file" id="profileImage" name="profileImage" accept="image/*" class="hidden" onchange="previewImage(this)">
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-700">Profile Photo</p>
                    <p class="text-xs text-gray-500 mt-0.5">JPG, PNG, GIF up to 5MB<br>Uploaded to Cloudinary</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100"
                           value="<?= htmlspecialchars($user['name'] ?? '') ?>">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Email Address <span class="text-red-500">*</span></label>
                    <input type="email" name="email" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100"
                           value="<?= htmlspecialchars($user['email'] ?? '') ?>"
                           <?= ($action === 'edit') ? 'readonly class="bg-gray-50"' : '' ?>>
                </div>
            </div>

            <?php if ($action === 'create'): ?>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Password <span class="text-red-500">*</span></label>
                <input type="password" name="password" required minlength="6"
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100"
                       placeholder="Minimum 6 characters">
            </div>
            <?php endif; ?>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Role <span class="text-red-500">*</span></label>
                <select name="role" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 bg-white">
                    <option value="employee" <?= ($user['role'] ?? '') === 'employee' ? 'selected' : '' ?>>Employee</option>
                    <option value="intern"   <?= ($user['role'] ?? '') === 'intern'   ? 'selected' : '' ?>>Intern</option>
                    <option value="admin"    <?= ($user['role'] ?? '') === 'admin'    ? 'selected' : '' ?>>Admin</option>
                </select>
            </div>

            <div class="flex gap-3 pt-2">
                <a href="<?= BASE_URL ?>/admin/users"
                   class="flex-1 text-center px-4 py-2.5 border border-gray-200 text-gray-600 rounded-xl text-sm hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
                <button type="submit"
                        class="flex-1 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-medium transition-colors">
                    <?= $action === 'create' ? 'Create User' : 'Save Changes' ?>
                </button>
            </div>
        </form>
    </div>
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
