<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Dashboard - TLCDesk</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <div class="hidden md:flex flex-col w-64 bg-gray-900 text-white">
        <div class="flex items-center justify-center h-16 bg-gray-800 font-bold text-xl">
            TLCDesk Admin
        </div>
        <div class="flex flex-col flex-1 overflow-y-auto">
             <nav class="flex-1 px-2 py-4 space-y-2">
                <a href="/admin/dashboard" class="flex items-center px-4 py-2 text-gray-100 bg-gray-800 rounded-md">
                    <?= t('dashboard') ?>
                </a>
                <a href="/admin/users" class="flex items-center px-4 py-2 text-gray-400 hover:bg-gray-800 rounded-md">
                    <?= t('users') ?>
                </a>
                <a href="/admin/vehicles" class="flex items-center px-4 py-2 text-gray-400 hover:bg-gray-800 rounded-md">
                    <?= t('vehicles') ?>
                </a>
                <a href="/admin/assignments" class="flex items-center px-4 py-2 text-gray-400 hover:bg-gray-800 rounded-md">
                    <?= t('assignments') ?>
                </a>
             </nav>
        </div>
    </div>

    <div class="flex flex-col flex-1 w-0 overflow-hidden">
         <header class="flex items-center justify-between px-6 py-4 bg-white border-b border-gray-200">
            <div></div>
            <div class="flex items-center">
                 <div class="mr-6 text-sm">
                    <a href="/lang/en" class="mx-1 <?= \App\Core\Session::get('locale') == 'en' ? 'font-bold' : '' ?>">EN</a>
                    <a href="/lang/es" class="mx-1 <?= \App\Core\Session::get('locale') == 'es' ? 'font-bold' : '' ?>">ES</a>
                </div>
                <span class="text-gray-800 text-sm mr-4"><?= e(t('welcome', ['name' => $user_name])) ?></span>
                <a href="/logout" class="text-sm text-red-600 hover:text-red-800"><?= t('logout') ?></a>
            </div>
        </header>
        <main class="flex-1 p-6">
            <h1 class="text-2xl font-bold mb-4">System Overview</h1>
             <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                 <div class="bg-white p-6 rounded shadow border-l-4 border-blue-500">
                     <div class="text-gray-500">Total Tenants</div>
                     <div class="text-3xl font-bold">1</div>
                 </div>
                 <div class="bg-white p-6 rounded shadow border-l-4 border-green-500">
                     <div class="text-gray-500">System Health</div>
                     <div class="text-xl font-bold text-green-600">Good</div>
                 </div>
            </div>
        </main>
    </div>
</body>
</html>
