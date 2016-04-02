<?php
if (!defined('WP_UNINSTALL_PLUGIN')) exit();
require_once('includes/Plugin_Tables.php');
App\Includes\Plugin_Tables::drop_tables();
?>