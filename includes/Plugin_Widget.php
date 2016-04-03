<?php namespace App\Includes;

use WP_Widget;

class Plugin_Widget extends WP_Widget
{
	public function __construct () {
		$widget_options = array(
			'classname' => '', // css
			'description' => 'Plugin description'
		);
		$this->WP_Widget('new_plugin', 'Start Plugin', $widget_options);
	}

	// Show form
	public function form($instance) {
		global $wpdb;
		$defaults = array(
			'theplugintitle' => '',
			'theplugin' => ''
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		$theplugintitle = esc_attr($instance['theplugintitle']);
		$theplugin = esc_attr($instance['theplugin']);
		echo '<p>Title : <input type="text" class="widefat" name="'.$this->get_field_name('theplugintitle').'" value="'.$theplugintitle.'" /></p>';
		echo '<p>PluginSettings : <input type="text" class="widefat" name="'.$this->get_field_name('theplugin').'" value="'.$theplugin.'" /></p>';
	}

	// Save form
	public function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['theplugintitle'] = strip_tags($new_instance['theplugintitle']);
		$instance['theplugin'] = strip_tags($new_instance['theplugin']);		
		return $instance;
	}

	// Show widget in page
	public function widget($args, $instance) {
		global $wpdb;
		extract($args);
		$theplugintitle = apply_filters('widget_title', $instance['theplugintitle']);
		$theplugin = apply_filters('widget_title', $instance['theplugin']);

		echo $before_widget;
		echo $before_title.$theplugintitle.$after_title;

		/* widget content */
		Frontend::view();

		echo $after_widget;
	}
}