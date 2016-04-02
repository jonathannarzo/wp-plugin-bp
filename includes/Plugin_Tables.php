<?php namespace Includes;

class Plugin_Tables
{

	public static function tables($key = '')
	{
		global $wpdb;
		$tables = array(
			'table1' => "{$wpdb->prefix}table1",
			'table2' => "{$wpdb->prefix}table2"
		);
		return empty($key) ? $tables : $tables[$key];
	}

	private function get_create_tbl_query($table, $key)
	{
		$queries = array();
		$queries['table1'] = "CREATE TABLE `$table` (
			`id` INT (11) NOT NULL AUTO_INCREMENT,
			`code` VARCHAR(100) NOT NULL UNIQUE,
			PRIMARY KEY (id)
		);";

		$queries['table2'] = "CREATE TABLE `$table` (
			`id` INT (11) NOT NULL AUTO_INCREMENT,
			`name` VARCHAR(100) NOT NULL,
			`email` VARCHAR(100) NOT NULL,
			PRIMARY KEY (id)
		);";

		return isset($queries[$key]) ? $queries[$key] : null;
	}

	public static function init_tables()
	{
		global $wpdb;
		$tables = self::tables();
		if (empty($tables)) die('No database tables found for the plugin.');

		foreach ($tables as $key => $table) {
			if ($wpdb->get_var("SHOW TABLES LIKE '$table'") != $table) {
				$table_query = self::get_create_tbl_query($table, $key);
				if ($table_query !== null) self::create_table($table_query);
				else die("Create table query for $table is not defined.");
			}
		}
	}

	private function create_table($sql)
	{
		require_once (ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}

	public static function drop_tables()
	{
		global $wpdb;
		$tables = self::tables();
		foreach ($tables as $table) {
			if ($wpdb->get_var("SHOW TABLES LIKE '$table'") == $table) {
				$sql = "DROP TABLE `$table`;";
				$wpdb->query($sql);
			}
		}
	}

}