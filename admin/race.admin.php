<?php
/**
 * http://net.tutsplus.com/tutorials/wordpress/rock-solid-wordpress-3-0-themes-using-custom-post-types/
 * @author oconnellp
 *
 * http://wordpress.stackexchange.com/questions/50793/add-custom-column-in-custom-post-type-edit-page
 * http://wordpress.stackexchange.com/questions/14973/row-actions-for-custom-post-types
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

		remove_action('save_post',array($this,'save_post'),10,1);

		$post_type = get_post_type($post_id);
		error_log('bhaa post type '.$post_type);
		error_log('bhaa RaceAdmin save_post('.$post_id.')');

		$post_array = array();
		if( !empty($post_id) ){
			$post_array = (array) get_post($post_id);
		}

		// save wp post and continue with meta
		$post_id = wp_insert_post($post_array);

		if(!is_wp_error($post_id))
		{
			$race = $wpdb->query($wpdb->prepare("SELECT id FROM wp_bhaa_race WHERE id = %d",$post_id));
			error_log('bhaa RaceAdmin exists? ('.$post_id.')');
			if(isset($race))
			{
				$wpdb->insert( 'wp_bhaa_race',
						array( 'id' => $post_id,
								'event'=> $post_array['post_title'],
								'distance' => $post_id,
								'unit' => 'km' ) );
//				$sql_post = 'INSERT INTO wp_bhaa_race (id,event,distance,unit) VALUES ('.$post_id.',1,8.7,"km");';
	//			$wpdb->insert($wpdb->prepare($sql_post));//$wpdb->prepare( $post_id,1,8.7,"km"));
//				$new_id = $wpdb->insert_id;
				error_log('bhaa insert race ');
			}
			else
			{
				$wpdb->query('UPDATE wp_bhaa_race set event='.$post_array['post_title'].' where id='.$post_id);
				error_log('bhaa updated race '.$post_id);
			}
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