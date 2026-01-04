<?php

namespace App\Services;

class ThemeService
{
    protected $themesPath;

    public function __construct()
    {
        $this->themesPath = __DIR__ . '/../Themes/';
    }

    public function getAvailableThemes()
    {
        $themes = [];
        $dirs = array_filter(glob($this->themesPath . '*'), 'is_dir');

        foreach ($dirs as $dir) {
            $manifestPath = $dir . '/manifest.json';
            if (file_exists($manifestPath)) {
                $manifest = json_decode(file_get_contents($manifestPath), true);
                if ($manifest) {
                    $themes[] = $manifest;
                }
            }
        }

        return $themes;
    }
}
