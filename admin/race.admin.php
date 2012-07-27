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
		error_log("bhaa RaceAdmin init()");
		add_action('save_post',array($this,'save_post'),10,1);
		
		error_log("bhaa RaceAdmin init() done");
		//add_meta_box("race-type-meta", "Race Type", "meta_options", "race", "road", "track");
		//add_action( 'right_now_content_table_end', 'post_type_totals_rightnow' );
	}
	
	function post_type_totals_rightnow() {
		$post_types = get_post_types( array( '_builtin' => false ), 'race' );
		if (count($post_types) > 0)
			foreach( $post_types as $pt => $args ) {
			$url = 'edit.php?post_type='.$pt;
			echo '<tr><td class="b"><a href="'. $url .'">'. wp_count_posts( $pt )->publish .'</a></td><td class="t"><a href="'. $url .'">'. $args->labels->name .'</a></td></tr>';
		}
	}
	
	function save_post($post_id){
		global $wpdb;
		error_log('RaceAdmin save_post('.$post_id.')');
		
		$post_type = get_post_type($post_id);
		error_log('post type '.$post_type);
		
		$race = $wpdb->query($wpdb->prepare("SELECT id FROM wp_bhaa_race WHERE id = %d",$post_id));
		if($race==0)
		{
			$sql_post = "INSERT INTO wp_bhaa_race (id,distance) VALUES(%s, %d)";
			$wpdb->query($wpdb->prepare($post_id, "update message"));
			$new_id = $wpdb->insert_id;
			error_log('bhaa insert race '.$new_id);
		}	
		else	
		{
			$wpdb->query('UPDATE wp_bhaa_race set distance="test" where id='.$post_id);
			error_log('bhaa updated race '.$id);
		}
// 		if( $post->post_type == "race" )
// 		{
// 			echo "saving a BHAA race post";
// 		}
		//pdate_post_meta($post->ID, "price", $_POST["price"]);
		return $post_id;
	}
}
?>