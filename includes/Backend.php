<?php namespace App\Includes;

class Backend
{

	public static function form()
	{
		if (isset($_POST['add-record'])) self::process_add();
		if (isset($_POST['update-record'])) self::process_update();

		$title = 'Add New';
		$type = 'add-record'; // Add New Record trigger
		$button_title = 'Add New Record';
		$record_id = '';

		if (isset($_GET['action']) && $_GET['action'] == 'edit') {
			if (isset($_GET['frominsert'])) echo self::alert_message('success', 'Record saved');
			$id = (int) $_GET['record'];
			$input = self::find($id);
			$title = 'Edit Record';
			$type = 'update-record'; // Update Record trigger
			$button_title = 'Update Record';
			$record_id = '<input type="hidden" name="record_id" value="'.$id.'" />';
		}

		echo '<div class="wrap">';
			echo '<h2>'.$title.'</h2>';
			echo '
			<form method="post">
				'.$record_id.'
				<div id="poststuff">
					<div id="post-body" class="metabox-holder columns-2">
						<table class="form-table">
							<tr class="form-field">
								<th scope="row">
									<label for="stats_excel">Name :</label>
								</th>
								<td>
									<input type="text" name="name" value="'.$input->name.'" />
								</td>
							</tr>
							<tr class="form-field">
								<th scope="row">
									<label for="stats_excel">Email :</label>
								</th>
								<td>
									<input type="text" name="email" value="'.$input->email.'" />
								</td>
							</tr>
							<tr class="form-field">
								<th scope="row"></th>
								<td>
									<input type="submit" class="button button-primary" name="'.$type.'" value="'.$button_title.'" />
								</td>
							</tr>
						</table>
					</div>
				</div>
			</form>';
		echo '</div>';
	}

	private static function process_add()
	{
		global $wpdb;
		$table = Plugin_Tables::tables('table2');
		$values = array(null, $_POST['name'], $_POST['email']);
		$query = "INSERT INTO `$table` VALUES (%s,%s,%s)";
		if ($wpdb->query($wpdb->prepare($query, $values))) {
			$record_id = (int) $wpdb->insert_id;
			$record_url = '?page='.esc_attr($_REQUEST['page']).'&action=edit&record='.$record_id.'&frominsert=true';
			wp_redirect($record_url);
			exit;
		}
	}

	private static function process_update()
	{
		global $wpdb;
		$table = Plugin_Tables::tables('table2');
		$values = array(null, $_POST['name'], $_POST['email']);
		$id = (int) $_POST['record_id'];
		$q = $wpdb->update(
			$table,
			array('name' => $_POST['name'], 'email' => $_POST['email']),
			array('id' => $id),
			array('%s', '%s'),
			array('%d')
		);
		if ($q) echo self::alert_message('success', 'Record saved');
	}

	private static function find($id)
	{
		global $wpdb;
		$table = Plugin_Tables::tables('table2');
		$query = "SELECT * FROM `$table` WHERE id=%d";
		return $wpdb->get_results($wpdb->prepare($query, array($id)))[0];
	}

	private static function alert_message($type = 'success', $message = 'Process successfull')
	{
		$class = 'notice-success';
		$type_title = 'Success';
		if ($type == 'error') {
			$class = 'notice-error';
			$type_title = 'Error';
		}
		return "<div class='notice $class is-dismissible'><p><b>{$type_title}:</b> {$message}</p></div>";
	}

}