<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Vehicle - TLCDesk</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex h-screen overflow-hidden">
    <div class="hidden md:flex flex-col w-64 bg-indigo-900 text-white">
        <div class="flex items-center justify-center h-16 bg-indigo-800 font-bold text-xl">
            TLCDesk Fleet
        </div>
         <div class="flex flex-col flex-1 overflow-y-auto">
             <nav class="flex-1 px-2 py-4 space-y-2">
                <a href="/owner/dashboard" class="flex items-center px-4 py-2 text-indigo-200 hover:bg-indigo-700 rounded-md">
                    Dashboard
                </a>
                <a href="/owner/vehicles" class="flex items-center px-4 py-2 text-gray-100 bg-indigo-700 rounded-md">
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
            <h1 class="text-2xl font-bold mb-4">Add New Vehicle</h1>
            <div class="bg-white p-6 rounded shadow max-w-lg">
                <form action="/owner/vehicles/store" method="POST" class="space-y-4">
                    <?= csrf_field() ?>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Plate Number</label>
                        <input type="text" name="plate" class="mt-1 block w-full border border-gray-300 rounded p-2" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Year</label>
                        <input type="number" name="year" class="mt-1 block w-full border border-gray-300 rounded p-2" required min="2000" max="<?= date('Y') + 1 ?>">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Make</label>
                            <input type="text" name="make" class="mt-1 block w-full border border-gray-300 rounded p-2" required placeholder="Toyota">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Model</label>
                            <input type="text" name="model" class="mt-1 block w-full border border-gray-300 rounded p-2" required placeholder="Camry">
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <a href="/owner/vehicles" class="mr-4 text-gray-600 hover:underline py-2">Cancel</a>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save Vehicle</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
