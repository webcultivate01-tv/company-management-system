<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — Page Not Found</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center p-6">
    <div class="text-center">
        <p class="text-8xl font-black text-indigo-100">404</p>
        <h1 class="text-2xl font-bold text-gray-800 mt-2">Page Not Found</h1>
        <p class="text-gray-500 mt-2 mb-6">The page you're looking for doesn't exist.</p>
        <a href="<?= defined('BASE_URL') ? BASE_URL : '/' ?>" class="px-6 py-3 bg-indigo-600 text-white rounded-xl text-sm font-medium hover:bg-indigo-700 transition-colors">
            Go Home
        </a>
    </div>
</body>
</html>
