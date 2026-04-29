<?php if (!isset($pageTitle)) $pageTitle = 'Dashboard'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> — WebCultivate Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .sidebar-overlay { backdrop-filter: blur(4px); }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        .stat-card { transition: transform 0.2s, box-shadow 0.2s; }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1); }
        .fade-in { animation: fadeIn 0.4s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">

<!-- Mobile overlay -->
<div id="sidebarOverlay" class="fixed inset-0 z-40 bg-black/50 sidebar-overlay hidden lg:hidden"></div>

<?php require_once __DIR__ . '/admin-sidebar.php'; ?>

<!-- Main Content Wrapper -->
<div class="lg:ml-64 min-h-screen flex flex-col">
    <!-- Top navbar -->
    <header class="sticky top-0 z-30 bg-white border-b border-gray-200 h-16 flex items-center justify-between px-4 lg:px-6">
        <div class="flex items-center gap-4">
            <button id="openSidebar" class="lg:hidden text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <div>
                <h1 class="text-lg font-semibold text-gray-800"><?= htmlspecialchars($pageTitle) ?></h1>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <span class="hidden sm:block text-sm text-gray-500"><?= date('D, d M Y') ?></span>
            <a href="<?= BASE_URL ?>/admin/profile" class="flex items-center gap-2 hover:opacity-80 transition-opacity">
                <img src="<?= htmlspecialchars($_SESSION['image'] ?: BASE_URL . '/public/images/avatar.png') ?>"
                     class="w-8 h-8 rounded-full object-cover border-2 border-indigo-200" alt="Profile">
                <span class="hidden sm:block text-sm font-medium text-gray-700"><?= htmlspecialchars($_SESSION['name']) ?></span>
            </a>
        </div>
    </header>

    <!-- Flash messages -->
    <?php if (!empty($flash)): ?>
    <div class="mx-4 lg:mx-6 mt-4">
        <div class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium fade-in
            <?= $flash['type'] === 'success' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200'
                : ($flash['type'] === 'error' ? 'bg-red-50 text-red-800 border border-red-200'
                : 'bg-blue-50 text-blue-800 border border-blue-200') ?>">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <?php if ($flash['type'] === 'success'): ?>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                <?php else: ?>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                <?php endif; ?>
            </svg>
            <?= htmlspecialchars($flash['message']) ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Page Content -->
    <main class="flex-1 p-4 lg:p-6 fade-in">
