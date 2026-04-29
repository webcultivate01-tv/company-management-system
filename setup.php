<?php
/**
 * One-time setup script — run once to create the admin user
 * Access: http://localhost/company-management-system/setup.php
 * DELETE this file after running!
 */
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/models/User.php';

$message = '';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($name) || empty($email) || empty($password)) {
        $error = 'All fields are required';
    } else {
        $userModel = new User();
        $existing  = $userModel->findByEmail($email);

        if ($existing) {
            $error = 'Admin with this email already exists';
        } else {
            $id = $userModel->createUser([
                'name'     => $name,
                'email'    => $email,
                'password' => $password,
                'role'     => 'admin',
            ]);
            if ($id) {
                $message = 'Admin account created! ID: ' . $id . ' — Delete setup.php now!';
            } else {
                $error = 'Failed to create admin';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CompanyMS Setup</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center p-6">
    <div class="w-full max-w-md bg-white rounded-2xl border border-gray-100 p-8 shadow-sm">
        <div class="text-center mb-6">
            <div class="w-12 h-12 bg-indigo-600 rounded-xl flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
            </div>
            <h1 class="text-xl font-bold text-gray-800">CompanyMS Setup</h1>
            <p class="text-sm text-gray-500 mt-1">Create the initial admin account</p>
        </div>

        <?php if ($message): ?>
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-xl text-sm mb-5 font-medium">
            ✅ <?= htmlspecialchars($message) ?>
        </div>
        <div class="text-center">
            <a href="<?= BASE_URL ?>/login" class="inline-block px-6 py-3 bg-indigo-600 text-white rounded-xl text-sm font-medium hover:bg-indigo-700">
                Go to Login →
            </a>
        </div>
        <?php elseif ($error): ?>
        <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-xl text-sm mb-5"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if (!$message): ?>
        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Admin Name</label>
                <input type="text" name="name" required
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400"
                       value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Email Address</label>
                <input type="email" name="email" required
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400"
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                <input type="password" name="password" required minlength="6"
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400">
            </div>
            <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl text-sm transition-colors">
                Create Admin Account
            </button>
        </form>
        <p class="text-center text-xs text-red-500 mt-4">⚠ Delete this file after setup is complete!</p>
        <?php endif; ?>
    </div>
</body>
</html>
