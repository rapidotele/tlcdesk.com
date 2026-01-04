<?php

namespace App\Core;

class View
{
    public static function render($view, $data = [])
    {
        // Determine Theme (Mock logic for now, should come from Organization)
        $theme = 'default';
        if (Session::has('theme')) {
            $theme = Session::get('theme');
        }

        $themePath = __DIR__ . '/../Themes/' . $theme . '/views/';
        $file = $themePath . $view . '.php';

        if (!file_exists($file)) {
            // Fallback to default if pro theme missing file
            $themePath = __DIR__ . '/../Themes/default/views/';
            $file = $themePath . $view . '.php';
            if (!file_exists($file)) {
                die("View {$view} not found.");
            }
        }

        extract($data);
        include $file;
    }
}

// Global helpers
if (!function_exists('e')) {
    function e($string) {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token() {
        return \App\Core\Session::csrfToken();
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field() {
        return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
    }
}
