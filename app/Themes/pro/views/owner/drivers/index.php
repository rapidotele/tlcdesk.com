<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drivers - TLCDesk</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <div class="hidden md:flex flex-col w-64 bg-indigo-900 text-white">
        <div class="flex items-center justify-center h-16 bg-indigo-800 font-bold text-xl">
            TLCDesk Fleet
        </div>
        <div class="flex flex-col flex-1 overflow-y-auto">
             <nav class="flex-1 px-2 py-4 space-y-2">
                <a href="/owner/dashboard" class="flex items-center px-4 py-2 text-indigo-200 hover:bg-indigo-700 rounded-md">
                    Dashboard
                </a>
                <a href="/owner/drivers" class="flex items-center px-4 py-2 text-gray-100 bg-indigo-700 rounded-md">
                    Drivers
                </a>
                <a href="/owner/vehicles" class="flex items-center px-4 py-2 text-indigo-200 hover:bg-indigo-700 rounded-md">
                    Vehicles
                </a>
             </nav>
        </div>
    </div>

    <div class="flex flex-col flex-1 w-0 overflow-hidden">
         <header class="flex items-center justify-between px-6 py-4 bg-white border-b border-gray-200">
            <div></div>
            <div class="flex items-center">
                <span class="text-gray-800 text-sm mr-4"><?= e($user_name) ?></span>
                <a href="/logout" class="text-sm text-red-600 hover:text-red-800">Logout</a>
            </div>
        </header>
        <main class="flex-1 p-6">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold">Drivers</h1>
                <a href="/owner/drivers/create" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Add Driver</a>
            </div>

            <?php if ($msg = \App\Core\Session::getFlash('success')): ?>
                <div class="bg-green-100 text-green-700 p-2 rounded mb-4"><?= e($msg) ?></div>
            <?php endif; ?>
             <?php if ($msg = \App\Core\Session::getFlash('error')): ?>
                <div class="bg-red-100 text-red-700 p-2 rounded mb-4"><?= e($msg) ?></div>
            <?php endif; ?>

            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (empty($drivers)): ?>
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-center text-gray-500">No drivers found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($drivers as $driver): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap"><?= e($driver['name']) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?= e($driver['email']) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?= e($driver['created_at']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
