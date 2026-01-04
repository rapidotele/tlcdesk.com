<?php

namespace App\Core;

class Session
{
    public static function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            // Secure session params
            ini_set('session.cookie_httponly', 1);
            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
                ini_set('session.cookie_secure', 1);
            }
            ini_set('session.use_strict_mode', 1);
            ini_set('session.cookie_samesite', 'Lax');

            // Set save path to project storage if possible, else default
            $savePath = __DIR__ . '/../../storage/sessions';
            if (is_writable($savePath)) {
                session_save_path($savePath);
            }

            session_start();
        }

        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }

    public static function get($key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public static function has($key)
    {
        return isset($_SESSION[$key]);
    }

    public static function forget($key)
    {
        unset($_SESSION[$key]);
    }

    public static function csrfToken()
    {
        return $_SESSION['csrf_token'];
    }

    public static function verifyCsrf($token)
    {
        return hash_equals($_SESSION['csrf_token'], $token);
    }

    public static function flash($key, $value)
    {
        $_SESSION['flash'][$key] = $value;
    }

    public static function getFlash($key)
    {
        $value = $_SESSION['flash'][$key] ?? null;
        unset($_SESSION['flash'][$key]);
        return $value;
    }

    public static function destroy()
    {
        session_destroy();
    }
}
