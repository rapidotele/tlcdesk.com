<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Dashboard - TLCDesk</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1e40af',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden">

    <!-- Sidebar -->
    <div class="hidden md:flex flex-col w-64 bg-gray-800 text-white">
        <div class="flex items-center justify-center h-16 bg-gray-900 font-bold text-xl">
            TLCDesk
        </div>
        <div class="flex flex-col flex-1 overflow-y-auto">
            <nav class="flex-1 px-2 py-4 space-y-2">
                <a href="/driver/dashboard" class="flex items-center px-4 py-2 text-gray-100 bg-gray-700 rounded-md">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Dashboard
                </a>
                <a href="#" class="flex items-center px-4 py-2 text-gray-400 hover:bg-gray-700 hover:text-white rounded-md">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Income/Expenses
                </a>
                <a href="#" class="flex items-center px-4 py-2 text-gray-400 hover:bg-gray-700 hover:text-white rounded-md">
                     <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Reports
                </a>
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex flex-col flex-1 w-0 overflow-hidden">
        <!-- Top Header -->
        <header class="flex items-center justify-between px-6 py-4 bg-white border-b border-gray-200">
            <div class="flex items-center">
                <button class="text-gray-500 focus:outline-none md:hidden">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
            </div>
            <div class="flex items-center">
                <span class="text-gray-800 text-sm mr-4"><?= e(t('welcome', ['name' => $user_name])) ?></span>
                <a href="/logout" class="text-sm text-red-600 hover:text-red-800"><?= t('logout') ?></a>
            </div>
        </header>

        <!-- Main -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
            <h3 class="text-gray-700 text-3xl font-medium"><?= t('dashboard') ?></h3>

            <div class="mt-4">
                <div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-3">
                    <div class="w-full px-4 py-5 bg-white rounded-lg shadow">
                        <div class="text-sm font-medium text-gray-500 truncate"><?= t('income') ?></div>
                        <div class="mt-1 text-3xl font-semibold text-gray-900">$<?= number_format($totals['income'], 2) ?></div>
                    </div>
                    <div class="w-full px-4 py-5 bg-white rounded-lg shadow">
                        <div class="text-sm font-medium text-gray-500 truncate"><?= t('expense') ?></div>
                        <div class="mt-1 text-3xl font-semibold text-gray-900">$<?= number_format($totals['expense'], 2) ?></div>
                    </div>
                    <div class="w-full px-4 py-5 bg-white rounded-lg shadow">
                        <div class="text-sm font-medium text-gray-500 truncate"><?= t('profit') ?></div>
                        <div class="mt-1 text-3xl font-semibold <?= $totals['profit'] >= 0 ? 'text-green-600' : 'text-red-600' ?>">$<?= number_format($totals['profit'], 2) ?></div>
                    </div>
                </div>
            </div>

            <!-- Quick Action: Add Transaction -->
            <div class="mt-8 bg-white p-6 rounded shadow">
                <h4 class="text-lg font-bold mb-4"><?= t('add_transaction') ?></h4>
                <form action="/driver/bookkeeping/add" method="POST" class="grid grid-cols-1 gap-4 md:grid-cols-5">
                    <?= csrf_field() ?>
                    <div>
                        <label class="block text-sm font-bold text-gray-700"><?= t('date') ?></label>
                        <input type="date" name="date" class="w-full border p-2 rounded" required value="<?= date('Y-m-d') ?>">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700"><?= t('type') ?></label>
                        <select name="type" class="w-full border p-2 rounded">
                            <option value="income"><?= t('income') ?></option>
                            <option value="expense"><?= t('expense') ?></option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700"><?= t('amount') ?></label>
                        <input type="number" step="0.01" name="amount" class="w-full border p-2 rounded" required placeholder="0.00">
                    </div>
                    <div>
                         <label class="block text-sm font-bold text-gray-700"><?= t('description') ?></label>
                         <input type="text" name="description" class="w-full border p-2 rounded" placeholder="Fuel, Fare, etc.">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700"><?= t('category') ?></label>
                        <select name="category_id" class="w-full border p-2 rounded" required>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>"><?= e($cat['name']) ?> (<?= e($cat['type']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="bg-blue-600 text-white p-2 rounded w-full hover:bg-blue-700"><?= t('add') ?></button>
                    </div>
                </form>
            </div>

            <div class="flex flex-col mt-8">
                <div class="py-2 -my-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                    <div class="inline-block min-w-full overflow-hidden align-middle border-b border-gray-200 shadow sm:rounded-lg">
                        <table class="min-w-full">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase bg-gray-50">Date</th>
                                    <th class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase bg-gray-50">Category</th>
                                    <th class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase bg-gray-50">Description</th>
                                    <th class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase bg-gray-50">Amount</th>
                                    <th class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase bg-gray-50">Type</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white">
                                <tr>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500" colspan="5">
                                        No recent transactions found.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

</body>
</html>
