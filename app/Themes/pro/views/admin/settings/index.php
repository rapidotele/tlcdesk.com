<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Settings - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <div class="hidden md:flex flex-col w-64 bg-blue-900 text-white">
        <div class="flex items-center justify-center h-16 bg-gray-800 font-bold text-xl">Admin Panel</div>
        <div class="flex flex-col flex-1 overflow-y-auto">
            <nav class="flex-1 px-2 py-4 space-y-2">
                <a href="/admin/dashboard" class="flex items-center px-4 py-2 text-gray-400 hover:bg-gray-800 rounded-md"><?= t('dashboard') ?></a>
                <a href="/admin/users" class="flex items-center px-4 py-2 text-gray-400 hover:bg-gray-800 rounded-md"><?= t('users') ?></a>
                <a href="/admin/vehicles" class="flex items-center px-4 py-2 text-gray-400 hover:bg-gray-800 rounded-md"><?= t('vehicles') ?></a>
                <a href="/admin/assignments" class="flex items-center px-4 py-2 text-gray-400 hover:bg-gray-800 rounded-md"><?= t('assignments') ?></a>
                <a href="/admin/settings" class="flex items-center px-4 py-2 text-gray-100 bg-gray-800 rounded-md">Settings</a>
            </nav>
        </div>
    </div>

    <div class="flex flex-col flex-1 w-0 overflow-hidden">
        <header class="flex items-center justify-between px-6 py-4 bg-white border-b border-gray-200">
            <h1 class="text-2xl font-bold">Settings</h1>
            <div class="flex items-center">
                <span class="text-gray-800 text-sm mr-4"><?= e($user_name) ?></span>
                <a href="/logout" class="text-sm text-red-600 hover:text-red-800"><?= t('logout') ?></a>
            </div>
        </header>
        <main class="flex-1 p-6 overflow-y-auto">
            <div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
                <h2 class="text-xl font-bold mb-4">Appearance</h2>

                <?php if ($msg = \App\Core\Session::getFlash('success')): ?>
                    <div class="bg-green-100 text-green-700 p-2 rounded mb-4"><?= e($msg) ?></div>
                <?php endif; ?>

                <form action="/admin/settings/theme" method="POST" class="space-y-4">
                    <?= csrf_field() ?>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Select Theme</label>
                        <select name="theme" class="mt-1 block w-full border border-gray-300 rounded p-2" required>
                            <?php foreach ($themes as $theme): ?>
                                <option value="<?= e($theme['slug']) ?>" <?= $current_theme == $theme['slug'] ? 'selected' : '' ?>>
                                    <?= e($theme['name']) ?> (v<?= e($theme['version']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="flex justify-end pt-4">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save Changes</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
