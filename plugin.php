<?php
/**
 * Plugin Name: Start Plugin
 * Plugin URI: http://github.com/jonathannarzo
 * Description: Wordpress plugin skeleton
 * Version: 1.0
 * Author: Atan
 * Author URI: http://github.com/jonathannarzo
 */

DEFINE('PLUGIN_DIR', plugin_dir_path( __FILE__ ));
DEFINE('PLUGIN_URL', plugins_url('/', __FILE__));
DEFINE('PLUGIN_SHORTCODE', 'startplugin');

# to identifying if user is logged-in or not
require_once(ABSPATH . 'wp-includes/pluggable.php');

# includes
spl_autoload_register(function ($class) {
    $parts = explode('\\', $class);
    $file = PLUGIN_DIR .'includes/'. end($parts) . '.php';
    if (file_exists($file)) include $file;
});

# Plugin Activation
register_activation_hook(__FILE__, 'App\Includes\Init_Plugin::activate_plugin');

# Plugin Deactivation
register_deactivation_hook(__FILE__, 'App\Includes\Init_Plugin::deactivate_plugin');

# Start plugin
add_action('plugins_loaded', function () { App\Includes\Init_Plugin::get_instance(); });