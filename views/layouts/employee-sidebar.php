<?php
$currentPath = trim($_GET['url'] ?? '', '/');
function empIsActive(string $prefix): string {
    global $currentPath;
    return str_starts_with($currentPath, $prefix) ? 'bg-violet-700 text-white' : 'text-violet-100 hover:bg-violet-700 hover:text-white';
}
?>
<aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-violet-900 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out flex flex-col">
    <div class="flex items-center justify-between h-16 px-4 bg-white border-b border-violet-800">
        <div class="flex items-center gap-2.5">
            <img src="<?= BASE_URL ?>/public/logo.png" alt="Logo" class="h-8 w-8 object-contain flex-shrink-0">
            <div class="leading-tight">
                <span class="text-gray-800 font-bold text-sm block">WebCultivate</span>
                <span class="text-gray-400 text-xs block">Software Solutions</span>
            </div>
        </div>
        <button id="closeSidebar" class="lg:hidden text-violet-300 hover:text-white">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    <nav class="flex-1 px-3 py-4 space-y-1">
        <a href="<?= BASE_URL ?>/employee/dashboard" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors <?= empIsActive('employee/dashboard') ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1" stroke-width="2"/><rect x="14" y="3" width="7" height="7" rx="1" stroke-width="2"/><rect x="3" y="14" width="7" height="7" rx="1" stroke-width="2"/><rect x="14" y="14" width="7" height="7" rx="1" stroke-width="2"/></svg>
            Dashboard
        </a>
        <a href="<?= BASE_URL ?>/employee/attendance" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors <?= empIsActive('employee/attendance') ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            My Attendance
        </a>
        <a href="<?= BASE_URL ?>/employee/leads" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors <?= empIsActive('employee/leads') ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
            My Leads
        </a>
        <a href="<?= BASE_URL ?>/employee/profile" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors <?= empIsActive('employee/profile') ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            My Profile
        </a>
    </nav>

    <div class="p-4 border-t border-violet-800">
        <a href="<?= BASE_URL ?>/employee/profile" class="flex items-center gap-3 group">
            <img src="<?= htmlspecialchars($_SESSION['image'] ?: BASE_URL . '/public/images/avatar.png') ?>"
                 class="w-9 h-9 rounded-full object-cover border-2 border-violet-600"
                 alt="Profile">
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-white truncate"><?= htmlspecialchars($_SESSION['name']) ?></p>
                <p class="text-xs text-violet-400 capitalize"><?= $_SESSION['role'] ?></p>
            </div>
        </a>
        <a href="<?= BASE_URL ?>/logout" class="mt-3 flex items-center gap-2 text-violet-400 hover:text-red-400 text-sm transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            Sign Out
        </a>
    </div>
</aside>
