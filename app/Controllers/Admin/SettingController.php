<?php

namespace App\Controllers\Admin;

use App\Core\View;
use App\Core\Session;
use App\Services\ThemeService;
use App\Repositories\OrganizationRepository;

class SettingController
{
    protected $themeService;
    protected $orgRepo;

    public function __construct()
    {
        $this->themeService = new ThemeService();
        $this->orgRepo = new OrganizationRepository();
    }

    public function index()
    {
        $orgId = Session::get('organization_id');
        $currentSettings = $this->orgRepo->getSettings($orgId);
        $themes = $this->themeService->getAvailableThemes();

        View::render('admin/settings/index', [
            'user_name' => Session::get('user_name'),
            'current_theme' => $currentSettings['theme_id'] ?? 'default',
            'themes' => $themes
        ]);
    }

    public function updateTheme()
    {
        if (!Session::verifyCsrf($_POST['csrf_token'] ?? '')) {
            die("Invalid CSRF");
        }

        $theme = $_POST['theme'];
        $orgId = Session::get('organization_id');

        $available = array_column($this->themeService->getAvailableThemes(), 'slug');
        if (!in_array($theme, $available)) {
            die("Invalid Theme");
        }

        $this->orgRepo->updateTheme($orgId, $theme);
        Session::set('theme', $theme);

        Session::flash('success', 'Theme updated successfully');
        header('Location: /admin/settings');
    }
}
