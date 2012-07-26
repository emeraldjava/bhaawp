<?php
/**
 * http://net.tutsplus.com/tutorials/wordpress/rock-solid-wordpress-3-0-themes-using-custom-post-types/
 * @author oconnellp
 *
 */
class RaceAdmin
{
	function init()
	{
		add_action('save_post',array('save_post'),10,1);
		add_meta_box("race-type-meta", "Race Type", "meta_options", "race", "road", "track");
	}
	
	function save_post($post_id){
		global $post;
		//pdate_post_meta($post->ID, "price", $_POST["price"]);
		echo "saving a BHAA race post";
	}
}
?>