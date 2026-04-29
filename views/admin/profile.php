<?php
$pageTitle = 'My Profile';
require_once __DIR__ . '/../layouts/admin-header.php';
?>

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl border border-gray-100 p-6">
        <div class="flex items-center gap-5 mb-8 pb-6 border-b border-gray-50">
            <div class="relative">
                <img id="previewImg"
                     src="<?= htmlspecialchars($user['profileImage'] ?: BASE_URL . '/public/images/avatar.png') ?>"
                     class="w-20 h-20 rounded-2xl object-cover border-2 border-indigo-100" alt="Profile">
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-800"><?= htmlspecialchars($user['name']) ?></h2>
                <p class="text-gray-500 text-sm"><?= htmlspecialchars($user['email']) ?></p>
                <span class="mt-1 inline-block px-2.5 py-0.5 bg-purple-100 text-purple-700 text-xs font-medium rounded-full capitalize"><?= $user['role'] ?></span>
            </div>
        </div>

        <form method="POST" enctype="multipart/form-data" class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Full Name</label>
                <input type="text" name="name" required
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100"
                       value="<?= htmlspecialchars($user['name']) ?>">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Email Address</label>
                <input type="email" value="<?= htmlspecialchars($user['email']) ?>" readonly
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-gray-50 text-gray-500 cursor-not-allowed">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Profile Photo</label>
                <label class="flex items-center gap-3 p-4 border-2 border-dashed border-gray-200 rounded-xl cursor-pointer hover:border-indigo-300 transition-colors group">
                    <div class="w-10 h-10 bg-indigo-50 rounded-lg flex items-center justify-center group-hover:bg-indigo-100 transition-colors">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Click to upload new photo</p>
                        <p class="text-xs text-gray-400">Uploaded to Cloudinary · JPG, PNG, GIF</p>
                    </div>
                    <input type="file" name="profileImage" accept="image/*" class="hidden" onchange="previewImage(this)">
                </label>
            </div>

            <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl text-sm transition-colors">
                Save Changes
            </button>
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
