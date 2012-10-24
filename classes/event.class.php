<?php
/**
 * http://www.andrewdodson.net/2011/02/add-video-embed-meta-boxes-to-custom-post-type/
 */
class Event
{
	function Event()
	{
		add_action("admin_init",array(&$this,"bhaa_event_meta"));
		add_action("save_post",array(&$this,"bhaa_event_meta_save"));
	}
	
	function bhaa_event_meta(){
		add_meta_box("youtube", "YouTube", array(&$this,"youtube"), "event", "side", "low");
		add_meta_box("flickr", "Flickr", array(&$this,"flickr"), "event", "side", "low");
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
	
	function flickr()
	{
		global $post;
		$custom = get_post_custom($post->ID);
		$youtube = $custom["flickr"][0];
		?>
		<p>Enter your flickr ID</p>
		<input name="flickr" value="<?php echo flickr; ?>"  />
		<?php
	}
	
	function bhaa_event_meta_save()
	{
		global $post;
		$post_id = $post->ID;
		// to prevent metadata or custom fields from disappearing...
		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
			return $post_id;
		
		if ( empty( $_POST ) )
			return;
		
		update_post_meta($post->ID, "flickr", $_POST["flickr"]);
		update_post_meta($post->ID, "youtube", $_POST["youtube"]);
	}
}
?>