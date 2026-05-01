<?php
$pageTitle = 'My Profile';
require_once __DIR__ . '/../layouts/employee-header.php';
$u      = $user;
$aadhar = is_array($u['aadhar'] ?? null) ? $u['aadhar'] : [];
$aadharLocked = !empty($aadhar['front']) && !empty($aadhar['back']);

function empFmt($val): string {
    return !empty($val) ? htmlspecialchars($val) : '<span class="text-gray-400">—</span>';
}
function empFmtDate($val): string {
    if (!$val) return '<span class="text-gray-400">—</span>';
    if ($val instanceof \MongoDB\BSON\UTCDateTime) return $val->toDateTime()->format('d M Y');
    return is_string($val) ? htmlspecialchars(date('d M Y', strtotime($val))) : '<span class="text-gray-400">—</span>';
}
?>

<?php if (!empty($flash)): ?>
<div class="mb-5 px-4 py-3 rounded-xl text-sm <?= $flash['type'] === 'success' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-50 text-red-700 border border-red-200' ?>">
    <?= htmlspecialchars($flash['message']) ?>
</div>
<?php endif; ?>

<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header Card -->
    <div class="bg-white rounded-2xl border border-gray-100 p-6">
        <div class="flex items-center gap-5">
            <img src="<?= htmlspecialchars($u['profileImage'] ?: BASE_URL . '/public/images/avatar.png') ?>"
                 class="w-20 h-20 rounded-full object-cover border-2 border-violet-100 flex-shrink-0" alt="Profile">
            <div>
                <h2 class="text-xl font-bold text-gray-800"><?= htmlspecialchars($u['name']) ?></h2>
                <p class="text-gray-500 text-sm"><?= htmlspecialchars($u['email']) ?></p>
                <div class="flex flex-wrap gap-2 mt-1.5">
                    <span class="px-2.5 py-0.5 bg-violet-100 text-violet-700 text-xs font-medium rounded-full capitalize"><?= $u['role'] ?></span>
                    <?php if (!empty($u['position'])): ?>
                    <span class="px-2.5 py-0.5 bg-indigo-100 text-indigo-700 text-xs font-medium rounded-full"><?= htmlspecialchars($u['position']) ?></span>
                    <?php endif; ?>
                    <?php if (!empty($u['department'])): ?>
                    <span class="px-2.5 py-0.5 bg-gray-100 text-gray-600 text-xs font-medium rounded-full"><?= htmlspecialchars($u['department']) ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Locked Info -->
        <div class="bg-white rounded-2xl border border-gray-100 p-6">
            <div class="flex items-center gap-2 mb-4">
                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">Job Information</h3>
                <span class="inline-flex items-center gap-1 text-xs text-gray-400">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    Managed by admin
                </span>
            </div>
            <dl class="space-y-3">
                <div class="flex justify-between text-sm"><dt class="text-gray-500">Full Name</dt><dd class="font-medium text-gray-800"><?= empFmt($u['name'] ?? '') ?></dd></div>
                <div class="flex justify-between text-sm"><dt class="text-gray-500">Email</dt><dd class="font-medium text-gray-800"><?= empFmt($u['email'] ?? '') ?></dd></div>
                <div class="flex justify-between text-sm"><dt class="text-gray-500">Position</dt><dd class="font-medium text-gray-800"><?= empFmt($u['position'] ?? '') ?></dd></div>
                <div class="flex justify-between text-sm"><dt class="text-gray-500">Department</dt><dd class="font-medium text-gray-800"><?= empFmt($u['department'] ?? '') ?></dd></div>
                <div class="flex justify-between text-sm"><dt class="text-gray-500">Joining Date</dt><dd class="font-medium text-gray-800"><?= empFmtDate($u['joiningDate'] ?? null) ?></dd></div>
                <div class="flex justify-between text-sm"><dt class="text-gray-500">Blood Group</dt><dd class="font-medium text-gray-800"><?= empFmt($u['bloodGroup'] ?? '') ?></dd></div>
                <?php if (!empty($u['skills']) && is_array($u['skills'])): ?>
                <div class="text-sm">
                    <dt class="text-gray-500 mb-1.5">Skills</dt>
                    <dd class="flex flex-wrap gap-1.5">
                        <?php foreach ($u['skills'] as $s): ?>
                        <span class="px-2 py-0.5 bg-violet-50 text-violet-700 rounded-full text-xs"><?= htmlspecialchars($s) ?></span>
                        <?php endforeach; ?>
                    </dd>
                </div>
                <?php endif; ?>
                <?php if (!empty($u['bio'])): ?>
                <div class="text-sm"><dt class="text-gray-500 mb-1">Bio</dt><dd class="text-gray-700"><?= htmlspecialchars($u['bio']) ?></dd></div>
                <?php endif; ?>
            </dl>
        </div>

        <!-- Editable Info -->
        <div class="bg-white rounded-2xl border border-gray-100 p-6">
            <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-4">Contact & Address <span class="text-violet-500 text-xs font-normal normal-case">(editable)</span></h3>
            <form method="POST" enctype="multipart/form-data" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Phone Number</label>
                    <input type="text" name="phone"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-violet-400 focus:ring-2 focus:ring-violet-100"
                           value="<?= htmlspecialchars($u['phone'] ?? '') ?>">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Residential Address</label>
                    <textarea name="address" rows="2"
                              class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-violet-400 focus:ring-2 focus:ring-violet-100 resize-none"><?= htmlspecialchars($u['address'] ?? '') ?></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Emergency Contact Name</label>
                    <input type="text" name="emergencyContact"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-violet-400 focus:ring-2 focus:ring-violet-100"
                           value="<?= htmlspecialchars($u['emergencyContact'] ?? '') ?>">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Emergency Contact Phone</label>
                    <input type="text" name="emergencyPhone"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-violet-400 focus:ring-2 focus:ring-violet-100"
                           value="<?= htmlspecialchars($u['emergencyPhone'] ?? '') ?>">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Profile Photo</label>
                    <label class="flex items-center gap-3 p-3 border-2 border-dashed border-gray-200 rounded-xl cursor-pointer hover:border-violet-300 transition-colors">
                        <img id="previewImg" src="<?= htmlspecialchars($u['profileImage'] ?: BASE_URL . '/public/images/avatar.png') ?>" class="w-10 h-10 rounded-full object-cover">
                        <span class="text-sm text-gray-500">Click to change photo</span>
                        <input type="file" name="profileImage" accept="image/*" class="hidden" onchange="previewImage(this)">
                    </label>
                </div>
                <button type="submit" class="w-full py-2.5 bg-violet-600 hover:bg-violet-700 text-white font-medium rounded-xl text-sm transition-colors">
                    Save Changes
                </button>
            </form>
        </div>

    </div>

    <!-- Aadhar Card Section -->
    <div class="bg-white rounded-2xl border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">Aadhar Card</h3>
        <?php if (!empty($aadharLocked)): ?>
        <!-- Locked: view only, no remove -->
        <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-emerald-100 text-emerald-700 text-xs rounded-full font-medium">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            Locked
        </span>
        <?php else: ?>
        <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-amber-100 text-amber-700 text-xs rounded-full font-medium">Pending Upload</span>
        <?php endif; ?>
            </div>
            <?php /* Aadhar is permanently locked for employee/intern — no remove button */ ?>
        </div>

        <?php if ($aadharLocked): ?>
        <!-- Show uploaded images -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <p class="text-xs font-medium text-gray-500 mb-2">Front Side</p>
                <img src="<?= htmlspecialchars($aadhar['front']) ?>"
                     class="w-full rounded-xl border border-gray-200 object-cover cursor-pointer"
                     onclick="openImg(this.src)" alt="Aadhar Front">
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 mb-2">Back Side</p>
                <img src="<?= htmlspecialchars($aadhar['back']) ?>"
                     class="w-full rounded-xl border border-gray-200 object-cover cursor-pointer"
                     onclick="openImg(this.src)" alt="Aadhar Back">
            </div>
        </div>
        <p class="text-xs text-gray-400 mt-3">Click an image to view full size.</p>

        <?php else: ?>
        <!-- Upload form -->
        <form method="POST" enctype="multipart/form-data" class="space-y-4">
            <input type="hidden" name="upload_aadhar" value="1">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Front Side <span class="text-red-500">*</span></label>
                    <label class="flex flex-col items-center justify-center gap-2 p-5 border-2 border-dashed border-gray-200 rounded-xl cursor-pointer hover:border-violet-300 transition-colors" id="frontLabel">
                        <img id="frontPreview" src="" class="hidden w-full rounded-lg object-cover mb-1">
                        <svg id="frontIcon" class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span id="frontText" class="text-xs text-gray-400">Click to upload front</span>
                        <input type="file" name="aadharFront" accept="image/*" class="hidden" onchange="previewAadhar(this,'frontPreview','frontIcon','frontText')">
                    </label>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Back Side <span class="text-red-500">*</span></label>
                    <label class="flex flex-col items-center justify-center gap-2 p-5 border-2 border-dashed border-gray-200 rounded-xl cursor-pointer hover:border-violet-300 transition-colors" id="backLabel">
                        <img id="backPreview" src="" class="hidden w-full rounded-lg object-cover mb-1">
                        <svg id="backIcon" class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span id="backText" class="text-xs text-gray-400">Click to upload back</span>
                        <input type="file" name="aadharBack" accept="image/*" class="hidden" onchange="previewAadhar(this,'backPreview','backIcon','backText')">
                    </label>
                </div>
            </div>
            <div class="bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 text-xs text-amber-700">
                ⚠️ Upload both front and back sides. Once uploaded, your Aadhar card will be <strong>permanently locked</strong> — only admin can remove it.
            </div>
            <button type="submit" class="w-full py-2.5 bg-violet-600 hover:bg-violet-700 text-white font-medium rounded-xl text-sm transition-colors">
                Upload Aadhar Card
            </button>
        </form>
        <?php endif; ?>
    </div>

    <!-- Change Password -->
    <div class="bg-white rounded-2xl border border-gray-100 p-6">
        <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-4">Change Password</h3>
        <form method="POST" id="pwdForm" novalidate class="space-y-4">
            <input type="hidden" name="change_password" value="1">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Current Password</label>
                    <input type="password" name="current_password" id="current_password"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-violet-400 focus:ring-2 focus:ring-violet-100">
                    <p class="text-red-500 text-xs mt-1 hidden" id="err-current">Required</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">New Password</label>
                    <input type="password" name="new_password" id="new_password"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-violet-400 focus:ring-2 focus:ring-violet-100">
                    <p class="text-red-500 text-xs mt-1 hidden" id="err-new">Min 6 characters</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Confirm Password</label>
                    <input type="password" name="confirm_password" id="confirm_password"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-violet-400 focus:ring-2 focus:ring-violet-100">
                    <p class="text-red-500 text-xs mt-1 hidden" id="err-confirm">Passwords do not match</p>
                </div>
            </div>
            <button type="submit" class="px-6 py-2.5 bg-gray-800 hover:bg-gray-900 text-white font-medium rounded-xl text-sm transition-colors">
                Change Password
            </button>
        </form>
    </div>

</div>

<!-- Lightbox -->
<div id="lightbox" class="fixed inset-0 z-50 hidden bg-black/80 flex items-center justify-center p-4" onclick="this.classList.add('hidden')">
    <img id="lightboxImg" src="" class="max-w-full max-h-full rounded-xl shadow-2xl">
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => document.getElementById('previewImg').src = e.target.result;
        reader.readAsDataURL(input.files[0]);
    }
}
function previewAadhar(input, previewId, iconId, textId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.getElementById(previewId);
            img.src = e.target.result;
            img.classList.remove('hidden');
            document.getElementById(iconId).classList.add('hidden');
            document.getElementById(textId).textContent = input.files[0].name;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
function openImg(src) {
    document.getElementById('lightboxImg').src = src;
    document.getElementById('lightbox').classList.remove('hidden');
}
document.getElementById('pwdForm').addEventListener('submit', function(e) {
    let valid = true;
    const show = (id, cond) => { document.getElementById(id).classList.toggle('hidden', !cond); if (cond) valid = false; };
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
