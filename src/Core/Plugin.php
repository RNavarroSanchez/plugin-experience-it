<?php

namespace ULA\Core;

use ULA\Admin\AdminPage;
use ULA\Controllers\AjaxController;
use ULA\Controllers\RequestValidator;
use ULA\Services\UserService;

class Plugin
{
    private AdminPage $adminPage;
    private AjaxController $ajaxController;

    public function __construct()
    {
        $paginator = new Paginator();
        $service = new UserService($paginator);
        $validator = new RequestValidator();


        $this->adminPage = new AdminPage($service);
        $this->ajaxController = new AjaxController($service, $validator);
    }

    public function register(): void
    {
        add_action('admin_menu', [$this->adminPage, 'registerMenu']);
        add_action('admin_enqueue_scripts', [$this->adminPage, 'enqueueAssets']);
        add_action('wp_ajax_ula_fetch_users', [$this->ajaxController, 'handleFetchUsers']);
    }
}
