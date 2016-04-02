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
foreach (glob(PLUGIN_DIR.'/includes/*.php') as $include) require_once $include;

# Plugin Activation
register_activation_hook(__FILE__, 'Includes\Init_Plugin::activate_plugin');

# Plugin Deactivation
register_deactivation_hook(__FILE__, 'Includes\Init_Plugin::deactivate_plugin');

# Backend
add_action('plugins_loaded', function () { Includes\Init_Plugin::get_instance(); });

add_action('init', 'Includes\Init_Plugin::start_plugin');

# Load styles and scripts
if (is_admin()) add_action('admin_enqueue_scripts', 'Includes\Init_Plugin::load_scripts');
else add_action('wp', 'Includes\Init_Plugin::load_scripts');

# Ajax
if (is_user_logged_in()) add_action('wp_ajax_plugin_ajax', 'Includes\Ajax_Request::process_ajax');
else add_action('wp_ajax_nopriv_plugin_ajax', 'Includes\Ajax_Request::process_ajax');