<?php
$pageTitle = 'Send Email';
require_once __DIR__ . '/../layouts/admin-header.php';
?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
    <!-- Email Compose -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl border border-gray-100 p-6">
            <h3 class="text-base font-semibold text-gray-800 mb-5">Compose Email</h3>
            <form method="POST" action="<?= BASE_URL ?>/admin/email" class="space-y-4">

                <!-- Recipients -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Recipients</label>
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition-colors">
                            <input type="radio" name="recipient" value="all" class="text-indigo-600" id="recpAll">
                            <div>
                                <p class="text-sm font-medium text-gray-700">All Employees</p>
                                <p class="text-xs text-gray-400"><?= count($users) ?> active team members</p>
                            </div>
                        </label>
                        <div class="border border-gray-200 rounded-xl p-3">
                            <p class="text-xs font-medium text-gray-500 mb-2">Select specific employees:</p>
                            <div class="space-y-2 max-h-40 overflow-y-auto">
                                <?php foreach ($users as $u): ?>
                                <label class="flex items-center gap-2 cursor-pointer hover:bg-gray-50 p-1 rounded">
                                    <input type="checkbox" name="recipient[]" value="<?= (string)$u['_id'] ?>"
                                           class="rounded text-indigo-600" onchange="clearRadio()">
                                    <img src="<?= htmlspecialchars($u['profileImage'] ?: BASE_URL . '/public/images/avatar.png') ?>"
                                         class="w-6 h-6 rounded-full object-cover">
                                    <span class="text-sm text-gray-700"><?= htmlspecialchars($u['name']) ?></span>
                                    <span class="text-xs text-gray-400">(<?= $u['role'] ?>)</span>
                                </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Subject</label>
                    <input type="text" name="subject" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100"
                           placeholder="Email subject...">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Message</label>
                    <textarea name="message" rows="7" required
                              class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 resize-none"
                              placeholder="Write your message here..."></textarea>
                </div>

                <button type="submit"
                        class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl text-sm transition-colors flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    Send Email
                </button>
            </form>
        </div>
    </div>

    <!-- Email Logs -->
    <div class="bg-white rounded-xl border border-gray-100 p-5">
        <h3 class="text-base font-semibold text-gray-800 mb-4">Recent Sent</h3>
        <?php if (empty($logs)): ?>
        <p class="text-sm text-gray-400 text-center py-8">No emails sent yet</p>
        <?php else: ?>
        <div class="space-y-3 max-h-96 overflow-y-auto">
            <?php foreach ($logs as $log): ?>
            <div class="border border-gray-100 rounded-xl p-3">
                <p class="text-sm font-medium text-gray-800 truncate"><?= htmlspecialchars($log['subject']) ?></p>
                <p class="text-xs text-gray-500 mt-0.5">
                    To: <?= count($log['recipients'] ?? []) ?> recipients
                    <span class="text-emerald-600">· <?= $log['success'] ?? 0 ?> sent</span>
                    <?php if (!empty($log['failed'])): ?>
                    <span class="text-red-500">· <?= $log['failed'] ?> failed</span>
                    <?php endif; ?>
                </p>
                <p class="text-xs text-gray-400 mt-1">by <?= htmlspecialchars($log['sentByName'] ?? '') ?> · <?= $log['sentAt'] ?? '' ?></p>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
function clearRadio() {
    document.getElementById('recpAll').checked = false;
}
</script>

<?php require_once __DIR__ . '/../layouts/admin-footer.php'; ?>
