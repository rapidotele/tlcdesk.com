<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fleet Owner Dashboard - TLCDesk</title>
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
                <a href="/owner/dashboard" class="flex items-center px-4 py-2 text-gray-100 bg-indigo-700 rounded-md">
                    Dashboard
                </a>
                <a href="/owner/drivers" class="flex items-center px-4 py-2 text-indigo-200 hover:bg-indigo-700 rounded-md">
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
                <span class="text-gray-800 text-sm mr-4"><?= e($user_name) ?> (Owner)</span>
                <a href="/logout" class="text-sm text-red-600 hover:text-red-800">Logout</a>
            </div>
        </header>
        <main class="flex-1 p-6">
            <h1 class="text-2xl font-bold mb-4">Fleet Overview</h1>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                 <div class="bg-white p-6 rounded shadow">
                     <div class="text-gray-500">Active Drivers</div>
                     <div class="text-3xl font-bold"><?= $driver_count ?></div>
                 </div>
                 <div class="bg-white p-6 rounded shadow">
                     <div class="text-gray-500">Vehicles</div>
                     <div class="text-3xl font-bold"><?= $vehicle_count ?></div>
                 </div>
                 <div class="bg-white p-6 rounded shadow">
                     <div class="text-gray-500">Alerts</div>
                     <div class="text-3xl font-bold text-red-500">0</div>
                 </div>
            </div>
        </main>
    </div>
</body>
</html>
