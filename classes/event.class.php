<?php
/**
 * http://www.andrewdodson.net/2011/02/add-video-embed-meta-boxes-to-custom-post-type/
 */
class Event
{
	const ANNUAL_MEMBERSHIP = 'Annual Membership';
	const DAY_MEMBER_TICKET = 'Day Member Ticket';
	const BHAA_MEMBER_TICKET = 'BHAA Member Ticket';
	
	function __construct() {
		add_action("admin_init",array(&$this,"bhaa_event_meta"));
		add_action("save_post",array(&$this,"bhaa_event_meta_save"));
	}
	
	function bhaa_event_meta(){
		add_meta_box("youtube", "YouTube", array(&$this,"youtube"), "event", "side", "low");
		add_meta_box("flickr", "Flickr", array(&$this,"flickr_photoset"), "event", "side", "low");
	}
	
	function youtube()
	{
		global $post;
		$custom = get_post_custom($post->ID);
		$youtube = $custom["youtube"][0];
	?>
	<p>Enter your video ID</p>
	<input name="youtube" value="<?php echo $youtube; ?>"  />
	<?php
	}
	
	function flickr_photoset()
	{
		global $post;
		$custom = get_post_custom($post->ID);
		$flickr_photoset = $custom["flickr_photoset"][0];
		?>
		<p>Enter your flickr photoset ID</p>
		<input name="flickr_photoset" value="<?php echo $flickr_photoset; ?>"  />
		<?php
	}
	
	function bhaa_event_meta_save()
	{
		global $post;
		// to prevent metadata or custom fields from disappearing...
		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
			return $post_id;
		
		if ( empty( $_POST ) )
			return;
		
		$post_id = $post->ID;
		update_post_meta($post->ID, "flickr_photoset", $_POST["flickr_photoset"]);
		update_post_meta($post->ID, "youtube", $_POST["youtube"]);
	}
}
?>