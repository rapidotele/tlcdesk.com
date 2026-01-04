<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Vehicles - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <div class="hidden md:flex flex-col w-64 bg-gray-900 text-white">
        <div class="flex items-center justify-center h-16 bg-gray-800 font-bold text-xl">Admin Panel</div>
        <div class="flex flex-col flex-1 overflow-y-auto">
            <nav class="flex-1 px-2 py-4 space-y-2">
                <a href="/admin/dashboard" class="flex items-center px-4 py-2 text-gray-400 hover:bg-gray-800 rounded-md"><?= t('dashboard') ?></a>
                <a href="/admin/users" class="flex items-center px-4 py-2 text-gray-400 hover:bg-gray-800 rounded-md"><?= t('users') ?></a>
                <a href="/admin/vehicles" class="flex items-center px-4 py-2 text-gray-100 bg-gray-800 rounded-md"><?= t('vehicles') ?></a>
                <a href="/admin/assignments" class="flex items-center px-4 py-2 text-gray-400 hover:bg-gray-800 rounded-md"><?= t('assignments') ?></a>
            </nav>
        </div>
    </div>

    <div class="flex flex-col flex-1 w-0 overflow-hidden">
        <header class="flex items-center justify-between px-6 py-4 bg-white border-b border-gray-200">
            <h1 class="text-2xl font-bold"><?= t('vehicles') ?> (Global)</h1>
            <div class="flex items-center">
                <span class="text-gray-800 text-sm mr-4"><?= e($user_name) ?></span>
                <a href="/logout" class="text-sm text-red-600 hover:text-red-800"><?= t('logout') ?></a>
            </div>
        </header>
        <main class="flex-1 p-6 overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold">All Vehicles</h2>
                <a href="/admin/vehicles/create" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Add Vehicle</a>
            </div>

             <?php if ($msg = \App\Core\Session::getFlash('success')): ?>
                <div class="bg-green-100 text-green-700 p-2 rounded mb-4"><?= e($msg) ?></div>
            <?php endif; ?>

            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Plate</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Make/Model</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Year</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Organization</th>
                            <th class="px-6 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($vehicles as $v): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap"><?= e($v['plate']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= e($v['make'] . ' ' . $v['model']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= e($v['year']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= e($v['organization_name'] ?? '-') ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="/admin/vehicles/edit/<?= $v['id'] ?>" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
