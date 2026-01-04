<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create User - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function toggleDriverFields() {
            var role = document.getElementById('roleSelect').value;
            var driverFields = document.getElementById('driverFields');
            if (role === 'driver') {
                driverFields.classList.remove('hidden');
            } else {
                driverFields.classList.add('hidden');
            }
        }
    </script>
</head>
<body class="bg-gray-100 flex h-screen overflow-hidden">
    <div class="flex flex-col flex-1 w-0 overflow-hidden">
        <main class="flex-1 p-6 overflow-y-auto">
            <div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
                <h2 class="text-xl font-bold mb-4">Create New User</h2>
                <form action="/admin/users/store" method="POST" class="space-y-4">
                    <?= csrf_field() ?>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Organization</label>
                        <select name="organization_id" class="mt-1 block w-full border border-gray-300 rounded p-2" required>
                            <?php foreach ($organizations as $org): ?>
                                <option value="<?= $org['id'] ?>"><?= e($org['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Full Name</label>
                        <input type="text" name="name" class="mt-1 block w-full border border-gray-300 rounded p-2" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" class="mt-1 block w-full border border-gray-300 rounded p-2" required>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Role</label>
                            <select name="role" id="roleSelect" class="mt-1 block w-full border border-gray-300 rounded p-2" required onchange="toggleDriverFields()">
                                <option value="driver">Driver</option>
                                <option value="org_admin">Owner (Org Admin)</option>
                                <option value="superadmin">Super Admin</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" class="mt-1 block w-full border border-gray-300 rounded p-2" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <!-- Driver Fields -->
                    <div id="driverFields" class="border-t pt-4 mt-4">
                        <h3 class="font-medium mb-2">Driver Profile</h3>
                        <div class="grid grid-cols-2 gap-4">
                             <div>
                                <label class="block text-sm font-medium text-gray-700">TLC License</label>
                                <input type="text" name="tlc_license" class="mt-1 block w-full border border-gray-300 rounded p-2">
                             </div>
                             <div>
                                <label class="block text-sm font-medium text-gray-700">DMV License</label>
                                <input type="text" name="dmv_license" class="mt-1 block w-full border border-gray-300 rounded p-2">
                             </div>
                             <div>
                                <label class="block text-sm font-medium text-gray-700">DMV Expiration</label>
                                <input type="date" name="dmv_expiration" class="mt-1 block w-full border border-gray-300 rounded p-2">
                             </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" name="password" class="mt-1 block w-full border border-gray-300 rounded p-2" required>
                    </div>

                    <div class="flex justify-end pt-4">
                        <a href="/admin/users" class="mr-4 text-gray-600 hover:text-gray-900 py-2">Cancel</a>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Create User</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
