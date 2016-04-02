<?php namespace Includes;

class Init_Plugin
{
	static $instance;

	// customer WP_List_Table object
	public $records_obj;

	public function __construct()
	{
		ob_start(); // Fix header error
		add_filter('set-screen-option', [ __CLASS__, 'set_screen' ], 10, 3);
		add_action('admin_menu', [$this, 'plugin_menu']);
	}

	public static function set_screen($status, $option, $value)
	{
		return $value;
	}

	public function plugin_menu()
	{
		add_menu_page('Plugin Title','Plugin Title','manage_options','pluginunique',[$this, 'plugin_page'], 'dashicons-welcome-widgets-menus');
		$hook = add_submenu_page('pluginunique', 'List of records', 'Rcord list', 'manage_options', 'pluginunique', [$this, 'plugin_page']);
		add_submenu_page('pluginunique', 'Add new record', 'Add New', 'manage_options', 'pluginunique_form', 'Includes\Backend::form');		
		add_action("load-$hook", [$this, 'screen_option']);
	}

	public function plugin_page()
	{
		echo '
		<div class="wrap">
			<h2>Record List</h2>';
			echo '<form action="" method="get">';
			    $this->records_obj->search_box( __( 'Search' ), 'example' ); 
			    foreach ($_GET as $key => $value) if( 's' !== $key ) echo ("<input type='hidden' name='$key' value='$value' />");
			echo '</form>';
			echo'
			<div id="poststuff">
				<div id="post-body" class="metabox-holder">
					<div id="post-body-content">
						<div class="meta-box-sortables ui-sortable">
							<form method="post">';
								$this->records_obj->prepare_items();
								$this->records_obj->display();
						echo '
							</form>
						</div>
					</div>
				</div>
				<br class="clear">
			</div>
		</div>';
	}

	public function screen_option()
	{
		$option = 'per_page';
		$args   = [
			'label'   => 'Records',
			'default' => 5,
			'option'  => 'records_per_page'
		];
		add_screen_option($option, $args);
		$this->records_obj = new Records_List();
	}

	public static function get_instance()
	{
		if (!isset( self::$instance )) self::$instance = new self();
		return self::$instance;
	}

	public static function activate_plugin()
	{
		Plugin_Tables::init_tables();
	}

	public static function deactivate_plugin() {}

	public static function load_scripts()
	{
		wp_enqueue_style('style', PLUGIN_URL.'assets/css/style.css');

		if (is_admin()) {
			# WordPress Media Uploader
			wp_enqueue_media();
			wp_enqueue_script('media-uploader', PLUGIN_URL.'assets/js/media-upload.js', array('jquery'), '', true);
		} else {

		}

		# Ajax Request
		wp_register_script('plugin-ajax', PLUGIN_URL.'assets/js/ajax.js', array('jquery'), '', true);
		wp_localize_script('plugin-ajax', 'ajaxRequest', array(
				'ajaxurl' => admin_url('admin-ajax.php'),
				'ajaxfunction' => 'plugin_ajax',
				'pageadmin' => is_admin(),
				'userid' => get_current_user_id()
			)
		);
		wp_enqueue_script('plugin-ajax');
	}

	public static function start_plugin()
	{
		self::pluginShortcode();
	}

	private function pluginShortcode()
	{
		add_shortcode(PLUGIN_SHORTCODE, 'self::shortcode');
	}

	private function shortcode($args, $content)
	{
		echo 'Plugin content from shortcode';
	}

}