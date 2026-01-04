<?php

namespace App\Controllers;

use App\Core\Session;
use App\Repositories\UserRepository;

class LangController
{
    public function switch($locale)
    {
        $available = ['en', 'es'];
        if (!in_array($locale, $available)) {
            $locale = 'en';
        }

        Session::set('locale', $locale);
        \App\Core\I18n::setLocale($locale);

        if (Session::has('user_id')) {
            $repo = new UserRepository();
            $repo->updateLocale(Session::get('user_id'), $locale);
        }

        // Redirect back
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        header("Location: $referer");
        exit;
    }
}
