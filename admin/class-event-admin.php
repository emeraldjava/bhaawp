<?php
class EventAdmin {

	protected static $instance = null;

	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	private function __construct() {
		add_action("admin_init",array(&$this,"bhaa_event_meta"));
		add_action("save_post",array(&$this,"bhaa_event_meta_save"));
	}

	function bhaa_event_meta() {
		add_meta_box("BHAA", "BHAA", array(&$this,"exportPreRegistered"), "event", "side", "low");
		//add_meta_box("youtube", "YouTube", array(&$this,"exportPreRegistered"), "event", "side", "high");
		//add_meta_box("flickr", "Flickr", array(&$this,"flickr_photoset"), "event", "side", "low");
	}

	function exportPreRegistered() {
		echo implode('<br/>', $this->get_admin_url_links());
	}

	private function get_admin_url_links() {
		global $post;
		$link = admin_url('admin.php?action=bhaa_event_export_prereg&post_id='.$post->ID);
		$url = '<a href='.$link.'>Export Pre-Reg Details</a>';
		return array(
			'bhaa_race_delete_results' => $url
		);
		// TODO add the action hander logic to export the SQL query result
	}

	function youtube() {
		global $post;
		$custom = get_post_custom($post->ID);
		$youtube = $custom["youtube"][0];
	?>
	<p>Enter your video ID</p>
	<input name="youtube" value="<?php echo $youtube; ?>"  />
	<?php
	}

	function flickr_photoset() {
		global $post;
		$custom = get_post_custom($post->ID);
		$flickr_photoset = $custom["flickr_photoset"][0];
		?>
		<p>Enter your flickr photoset ID</p>
		<input name="flickr_photoset" value="<?php echo $flickr_photoset; ?>"  />
		<?php
	}

	function bhaa_event_meta_save() {
		global $post;
		// to prevent metadata or custom fields from disappearing...
		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
			return $post->ID;

		if ( empty( $_POST ) )
			return;

		$post_id = $post->ID;
		update_post_meta($post->ID, "flickr_photoset", $_POST["flickr_photoset"]);
		update_post_meta($post->ID, "youtube", $_POST["youtube"]);
	}
}
?>
