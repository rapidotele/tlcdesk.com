<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - TLCDesk</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 h-screen flex justify-center items-center">

    <div class="w-full max-w-md bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-end mb-4 text-sm">
            <a href="/lang/en" class="mx-2 <?= \App\Core\Session::get('locale') == 'en' ? 'font-bold' : '' ?>">EN</a> |
            <a href="/lang/es" class="mx-2 <?= \App\Core\Session::get('locale') == 'es' ? 'font-bold' : '' ?>">ES</a>
        </div>
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">TLCDesk <?= t('login') ?></h2>

        <?php if ($msg = \App\Core\Session::getFlash('error')): ?>
            <div class="bg-red-100 text-red-700 p-2 rounded mb-4"><?= e($msg) ?></div>
        <?php endif; ?>

        <form action="/login" method="POST">
            <?= csrf_field() ?>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email"><?= t('email') ?></label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="email" name="email" type="email" placeholder="<?= t('email') ?>" required>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password"><?= t('password') ?></label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="password" name="password" type="password" placeholder="******************" required>
            </div>
            <div class="flex items-center justify-between">
                <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                    <?= t('sign_in') ?>
                </button>
                <a class="inline-block align-baseline font-bold text-sm text-blue-600 hover:text-blue-800" href="/register">
                    <?= t('create_account') ?>
                </a>
            </div>
        </form>
    </div>

</body>
</html>
