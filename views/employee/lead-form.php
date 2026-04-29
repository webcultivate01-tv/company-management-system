<?php
$pageTitle = ($action === 'create') ? 'Add Lead' : 'Edit Lead';
require_once __DIR__ . '/../layouts/employee-header.php';
?>

<div class="max-w-lg mx-auto">
    <div class="mb-6">
        <a href="<?= BASE_URL ?>/employee/leads" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-violet-600">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Leads
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-6"><?= $pageTitle ?></h2>

        <?php if (!empty($error)): ?>
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm mb-5"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" class="space-y-5" id="leadForm" novalidate>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Contact Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name"
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-violet-400 focus:ring-2 focus:ring-violet-100"
                       value="<?= htmlspecialchars($lead['name'] ?? '') ?>">
                <p class="text-red-500 text-xs mt-1 hidden" id="err-name">Name is required (letters only, min 2 characters)</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Mobile Number <span class="text-red-500">*</span></label>
                <input type="tel" name="mobile" id="mobile"
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-violet-400 focus:ring-2 focus:ring-violet-100"
                       value="<?= htmlspecialchars($lead['mobile'] ?? '') ?>" maxlength="15">
                <p class="text-red-500 text-xs mt-1 hidden" id="err-mobile">Enter a valid 10-digit mobile number</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Business Name</label>
                <input type="text" name="business" id="business"
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-violet-400 focus:ring-2 focus:ring-violet-100"
                       placeholder="e.g. ABC Enterprises"
                       value="<?= htmlspecialchars($lead['business'] ?? '') ?>">
                <p class="text-red-500 text-xs mt-1 hidden" id="err-business">Business name cannot exceed 100 characters</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-2 gap-3">
                    <label>
                        <input type="radio" name="status" value="interested" class="sr-only peer"
                               <?= ($lead['status'] ?? 'interested') === 'interested' ? 'checked' : '' ?>>
                        <div class="cursor-pointer text-center py-3 border-2 border-gray-200 rounded-xl text-sm font-medium text-gray-500 transition-all peer-checked:border-emerald-500 peer-checked:text-emerald-600 peer-checked:bg-emerald-50">
                            ✅ Interested
                        </div>
                    </label>
                    <label>
                        <input type="radio" name="status" value="not_interested" class="sr-only peer"
                               <?= ($lead['status'] ?? '') === 'not_interested' ? 'checked' : '' ?>>
                        <div class="cursor-pointer text-center py-3 border-2 border-gray-200 rounded-xl text-sm font-medium text-gray-500 transition-all peer-checked:border-red-400 peer-checked:text-red-600 peer-checked:bg-red-50">
                            ❌ Not Interested
                        </div>
                    </label>
                </div>
                <p class="text-red-500 text-xs mt-1 hidden" id="err-status">Please select a status</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Notes</label>
                <textarea name="notes" id="notes" rows="3"
                          class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-violet-400 focus:ring-2 focus:ring-violet-100 resize-none"
                          placeholder="Any additional notes about this lead..."><?= htmlspecialchars($lead['notes'] ?? '') ?></textarea>
                <p class="text-red-500 text-xs mt-1 hidden" id="err-notes">Notes cannot exceed 500 characters</p>
            </div>
            <div class="flex gap-3 pt-2">
                <a href="<?= BASE_URL ?>/employee/leads" class="flex-1 text-center px-4 py-2.5 border border-gray-200 text-gray-600 rounded-xl text-sm hover:bg-gray-50">Cancel</a>
                <button type="submit" class="flex-1 px-4 py-2.5 bg-violet-600 hover:bg-violet-700 text-white rounded-xl text-sm font-medium transition-colors">
                    <?= $action === 'create' ? 'Add Lead' : 'Save Changes' ?>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('leadForm').addEventListener('submit', function(e) {
    let valid = true;

    const show = (id, condition) => {
        const el = document.getElementById(id);
        el.classList.toggle('hidden', !condition);
        if (condition) valid = false;
    };

    const name     = document.getElementById('name').value.trim();
    const mobile   = document.getElementById('mobile').value.trim();
    const business = document.getElementById('business').value.trim();
    const notes    = document.getElementById('notes').value.trim();
    const status   = document.querySelector('input[name="status"]:checked');

    show('err-name',     name.length < 2 || !/^[a-zA-Z\s]+$/.test(name));
    show('err-mobile',   !/^[0-9]{10,15}$/.test(mobile));
    show('err-business', business.length > 100);
    show('err-status',   !status);
    show('err-notes',    notes.length > 500);

    if (!valid) e.preventDefault();
});

// Live character counter for notes
document.getElementById('notes').addEventListener('input', function() {
    const remaining = 500 - this.value.length;
    document.getElementById('err-notes').classList.toggle('hidden', remaining >= 0);
});
</script>

<?php require_once __DIR__ . '/../layouts/employee-footer.php'; ?>
