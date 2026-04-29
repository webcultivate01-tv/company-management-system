<?php if (!isset($pageTitle)) $pageTitle = 'Dashboard'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> — WebCultivate</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .fade-in { animation: fadeIn 0.4s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-thumb { background: #ddd6fe; border-radius: 3px; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">

<div id="sidebarOverlay" class="fixed inset-0 z-40 bg-black/50 hidden lg:hidden"></div>

<?php require_once __DIR__ . '/employee-sidebar.php'; ?>

<div class="lg:ml-64 min-h-screen flex flex-col">
    <header class="sticky top-0 z-30 bg-white border-b border-gray-200 h-16 flex items-center justify-between px-4 lg:px-6">
        <div class="flex items-center gap-4">
            <button id="openSidebar" class="lg:hidden text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <h1 class="text-lg font-semibold text-gray-800"><?= htmlspecialchars($pageTitle) ?></h1>
        </div>
        <div class="flex items-center gap-3">
            <span class="hidden sm:block text-sm text-gray-500"><?= date('D, d M Y') ?></span>
            <a href="<?= BASE_URL ?>/employee/profile" class="flex items-center gap-2">
                <img src="<?= htmlspecialchars($_SESSION['image'] ?: BASE_URL . '/public/images/avatar.png') ?>"
                     class="w-8 h-8 rounded-full object-cover border-2 border-violet-200" alt="Profile">
                <span class="hidden sm:block text-sm font-medium text-gray-700"><?= htmlspecialchars($_SESSION['name']) ?></span>
            </a>
        </div>
    </header>

    <?php if (!empty($flash)): ?>
    <div class="mx-4 lg:mx-6 mt-4">
        <div class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium fade-in
            <?= $flash['type'] === 'success' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200'
                : 'bg-red-50 text-red-800 border border-red-200' ?>">
            <?= htmlspecialchars($flash['message']) ?>
        </div>
    </div>
    <?php endif; ?>

    <main class="flex-1 p-4 lg:p-6 fade-in">
