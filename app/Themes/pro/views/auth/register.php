<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - TLCDesk</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 h-screen flex justify-center items-center">

    <div class="w-full max-w-md bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-end mb-4 text-sm">
            <a href="/lang/en" class="mx-2 <?= \App\Core\Session::get('locale') == 'en' ? 'font-bold' : '' ?>">EN</a> |
            <a href="/lang/es" class="mx-2 <?= \App\Core\Session::get('locale') == 'es' ? 'font-bold' : '' ?>">ES</a>
        </div>
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6"><?= t('create_account') ?></h2>

        <form action="/register" method="POST">
            <?= csrf_field() ?>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2"><?= t('full_name') ?></label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="name" type="text" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2"><?= t('email') ?></label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="email" type="email" required>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2"><?= t('password') ?></label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="password" type="password" required>
            </div>
            <div class="flex items-center justify-between">
                <button class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                    <?= t('register') ?>
                </button>
                <a class="inline-block align-baseline font-bold text-sm text-blue-600 hover:text-blue-800" href="/login">
                    <?= t('have_account') ?>
                </a>
            </div>
        </form>
    </div>

</body>
</html>
