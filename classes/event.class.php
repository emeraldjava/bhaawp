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
		add_filter('em_booking_output_placeholder',array($this,'bhaa_em_booking_output_placeholder'),1,3);
		add_filter('em_ticket_is_available',array($this,'bhaa_em_ticket_is_available'), 10, 2);
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
		$flickr = $custom["flickr"][0];
		?>
		<p>Enter your flickr ID</p>
		<input name="flickr" value="<?php echo $flickr; ?>"  />
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
	
	/**
	 * Add a custom placeholder to handle the BHAA custom email generation.
	 * http://wp-events-plugin.com/tutorials/create-a-custom-placeholder-for-event-formatting/
	 */
	function bhaa_em_booking_output_placeholder($replace, $EM_Booking, $result){
		global $wp_query, $wp_rewrite;
		switch( $result ){
			case '#_BHAATICKETS':
				ob_start();
				em_locate_template('emails/bhaatickets.php', true, array('EM_Booking'=>$EM_Booking));
				$replace = ob_get_clean();
				$replace = $EM_Booking->output($replace);
				break;
		}
		return $replace;
	}
	
	/**
	 * Determine which tickets to display to which users.
	 * @param unknown_type $result
	 * @param unknown_type $EM_Ticket
	 * @return boolean
	 */
	function bhaa_em_ticket_is_available($result, $EM_Ticket) {

		if (current_user_can( strtolower('administrator') )) {
			return true;
		}

		if ( $EM_Ticket->ticket_name == 'Day Member Ticket') {// && !current_user_can( strtolower($BHAA_Payments[annualname]) ) ) {
			//if you are an ANNUAL MEMBER then this ticket will NOT show up
			if(!is_user_logged_in())
				return true;
			else
				return false;
		}
	
		if ( $EM_Ticket->ticket_name == 'BHAA Member Ticket') {// && current_user_can( strtolower($BHAA_Payments[annualname]) ) ) {
			//if you are an ANNUAL MEMBER then you can see this ticket
			if(is_user_logged_in())
				return true;
			else
				return false;
		}
	}
}
?>