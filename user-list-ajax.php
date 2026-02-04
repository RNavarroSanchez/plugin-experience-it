<?php
/**
 * Plugin Name: User List Ajax
 * Description: Lista paginada de usuarios con búsqueda y AJAX en el backend para eXperience IT Solutions.
 * Version: 1.0.0
 * Author: Roberto Navarro
 */

defined('ABSPATH') || exit;

define('ULA_PATH', plugin_dir_path(__FILE__));
define('ULA_URL', plugin_dir_url(__FILE__));

require_once ULA_PATH . 'src/Core/Autoloader.php';

add_action('plugins_loaded', function () {
    ULA\Core\Autoloader::register();
    $plugin = new ULA\Core\Plugin();
    $plugin->register();
});
