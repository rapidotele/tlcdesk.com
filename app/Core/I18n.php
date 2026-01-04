<?php

namespace App\Core;

class I18n
{
    protected static $locale = 'en';
    protected static $translations = [];
    protected static $loaded = false;

    public static function setLocale($locale)
    {
        self::$locale = $locale;
    }

    public static function getLocale()
    {
        return self::$locale;
    }

    public static function load()
    {
        if (self::$loaded) return;

        // Load File
        $file = __DIR__ . '/../i18n/locales/' . self::$locale . '.php';
        if (file_exists($file)) {
            self::$translations = require $file;
        }

        // Load overrides from DB (Cache this in production)
        $db = Database::getInstance();
        try {
            // Check if table exists first? It should.
            $stmt = $db->getConnection()->prepare("SELECT t_key, t_value FROM translations WHERE locale_code = ?");
            $stmt->execute([self::$locale]);
            while ($row = $stmt->fetch()) {
                self::$translations[$row['t_key']] = $row['t_value'];
            }
        } catch (\Exception $e) {
            // Ignore DB errors during load (e.g. during install)
        }

        self::$loaded = true;
    }

    public static function t($key, $params = [])
    {
        if (!self::$loaded) self::load();

        $text = self::$translations[$key] ?? $key;

        foreach ($params as $k => $v) {
            $text = str_replace(':' . $k, $v, $text);
        }

        return $text;
    }
}

// Helper
if (!function_exists('t')) {
    function t($key, $params = []) {
        return \App\Core\I18n::t($key, $params);
    }
}
