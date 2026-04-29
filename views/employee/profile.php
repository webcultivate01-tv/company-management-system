<?php
$pageTitle = 'My Profile';
require_once __DIR__ . '/../layouts/employee-header.php';
?>

<div class="max-w-xl mx-auto">
    <div class="bg-white rounded-2xl border border-gray-100 p-6">
        <div class="flex items-center gap-5 mb-8 pb-6 border-b border-gray-50">
            <div class="relative">
                <img id="previewImg"
                     src="<?= htmlspecialchars($user['profileImage'] ?: BASE_URL . '/public/images/avatar.png') ?>"
                     class="w-20 h-20 rounded-2xl object-cover border-2 border-violet-100" alt="Profile">
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-800"><?= htmlspecialchars($user['name']) ?></h2>
                <p class="text-gray-500 text-sm"><?= htmlspecialchars($user['email']) ?></p>
                <span class="mt-1 inline-block px-2.5 py-0.5 bg-violet-100 text-violet-700 text-xs font-medium rounded-full capitalize"><?= $user['role'] ?></span>
            </div>
        </div>

        <form method="POST" enctype="multipart/form-data" class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Full Name</label>
                <input type="text" name="name" required
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-violet-400 focus:ring-2 focus:ring-violet-100"
                       value="<?= htmlspecialchars($user['name']) ?>">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                <input type="email" value="<?= htmlspecialchars($user['email']) ?>" readonly
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-gray-50 text-gray-500 cursor-not-allowed">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Profile Photo</label>
                <label class="flex items-center gap-3 p-4 border-2 border-dashed border-gray-200 rounded-xl cursor-pointer hover:border-violet-300 transition-colors">
                    <div class="w-10 h-10 bg-violet-50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Upload new photo</p>
                        <p class="text-xs text-gray-400">Uploaded to Cloudinary</p>
                    </div>
                    <input type="file" name="profileImage" accept="image/*" class="hidden" onchange="previewImage(this)">
                </label>
            </div>
            <button type="submit" class="w-full py-3 bg-violet-600 hover:bg-violet-700 text-white font-medium rounded-xl text-sm transition-colors">
                Update Profile
            </button>
        </form>

        <!-- Change Password -->
        <div class="mt-8 pt-6 border-t border-gray-100">
            <h3 class="text-sm font-semibold text-gray-700 mb-4">Change Password</h3>
            <form method="POST" id="pwdForm" novalidate class="space-y-4">
                <input type="hidden" name="change_password" value="1">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Current Password</label>
                    <input type="password" name="current_password" id="current_password"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-violet-400 focus:ring-2 focus:ring-violet-100">
                    <p class="text-red-500 text-xs mt-1 hidden" id="err-current">Current password is required</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">New Password</label>
                    <input type="password" name="new_password" id="new_password"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-violet-400 focus:ring-2 focus:ring-violet-100">
                    <p class="text-red-500 text-xs mt-1 hidden" id="err-new">Password must be at least 6 characters</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Confirm New Password</label>
                    <input type="password" name="confirm_password" id="confirm_password"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-violet-400 focus:ring-2 focus:ring-violet-100">
                    <p class="text-red-500 text-xs mt-1 hidden" id="err-confirm">Passwords do not match</p>
                </div>
                <button type="submit" class="w-full py-3 bg-gray-800 hover:bg-gray-900 text-white font-medium rounded-xl text-sm transition-colors">
                    Change Password
                </button>
            </form>
        </div>

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

document.getElementById('pwdForm').addEventListener('submit', function(e) {
    let valid = true;
    const show = (id, condition) => { document.getElementById(id).classList.toggle('hidden', !condition); if (condition) valid = false; };
    const cur  = document.getElementById('current_password').value;
    const nw   = document.getElementById('new_password').value;
    const conf = document.getElementById('confirm_password').value;
    show('err-current', cur.length === 0);
    show('err-new',     nw.length < 6);
    show('err-confirm', nw !== conf);
    if (!valid) e.preventDefault();
});
</script>

<?php require_once __DIR__ . '/../layouts/employee-footer.php'; ?>
