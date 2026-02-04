<?php

namespace ULA\Admin;

use ULA\Services\UserService;

class AdminPage
{
    private UserService $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function registerMenu(): void
    {
        add_menu_page(
            'User List Ajax',
            'User List Ajax',
            'manage_options',
            'ula-user-list',
            [$this, 'renderPage'],
            'dashicons-admin-users',
            1
        );
    }

    public function enqueueAssets(string $hook): void
    {
        if ($hook !== 'toplevel_page_ula-user-list') {
            return;
        }

        wp_enqueue_style(
            'ula-admin-css',
            ULA_URL . 'assets/admin.css',
            [],
            '1.0.0'
        );

        wp_enqueue_script(
            'ula-admin-js',
            ULA_URL . 'assets/admin.js',
            ['jquery'],
            '1.0.0',
            true
        );

        wp_localize_script('ula-admin-js', 'ulaAjax', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('ula_fetch_users'),
            'perPage' => 5,
        ]);
    }

    public function renderPage(): void
    {
        ?>
        <div class="wrap">
            <h1>User List Ajax</h1>

            <form id="ula-search-form" class="ula-search-form">
                <div class="ula-field">
                    <label for="ula-name">Nombre</label>
                    <input type="text" id="ula-name" name="name" />
                </div>
                <div class="ula-field">
                    <label for="ula-surname">Apellidos</label>
                    <input type="text" id="ula-surname" name="surname" />
                </div>
                <div class="ula-field">
                    <label for="ula-email">Email</label>
                    <input type="text" id="ula-email" name="email" />
                </div>
                <div class="ula-actions">
                    <button type="button" id="ula-clear" class="button">Limpiar</button>
                </div>
            </form>

            <div id="ula-results">
                <table class="wp-list-table widefat fixed striped ula-table">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Nombre</th>
                            <th>Apellido 1</th>
                            <th>Apellido 2</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody id="ula-table-body">
                        <tr>
                            <td colspan="5">Cargando...</td>
                        </tr>
                    </tbody>
                </table>

                <div id="ula-pagination" class="ula-pagination"></div>
            </div>
        </div>
        <?php
    }
}
