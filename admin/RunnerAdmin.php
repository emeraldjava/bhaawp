<?php
class RunnerAdmin
{
	function __construct()
	{
		// display the admin status column
		add_filter('manage_users_columns',array($this,'bhaa_manage_users_columns'));
		add_filter('manage_users_custom_column',array($this,'bhaa_manage_users_custom_column'), 10, 3 );
	}
	
	/**
	 * handle the custom admin columns
	 */
	function bhaa_manage_users_columns( $column ) {
		$column['status'] = __('Status', 'status');
		return $column;
	}
	
	function bhaa_manage_users_custom_column( $val, $column_name, $user_id )
	{
		$user = get_userdata( $user_id );
		switch ($column_name) {
			case 'status' :
				return get_user_meta($user_id,Runner::BHAA_RUNNER_STATUS,true);
				break;
			default:
		}
		return $return;
	}
	
// 	/**
// 	 * http://codex.wordpress.org/Function_Reference/get_posts
// 	 * http://codex.wordpress.org/Function_Reference/wp_dropdown_categories
// 	 * http://wordpress.stackexchange.com/questions/34320/dropdown-list-of-a-custom-post-type
// 	 * http://stackoverflow.com/questions/698817/faster-way-to-populate-select-with-javascript
// 	 */
// 	function bhaa_houses_dropdown( $post_type )
// 	{
// 		$posts = get_posts(
// 				array(
// 						'post_type'   => $post_type,
// 						'numberposts' => -1,
// 						'orderby'     => 'title',
// 						'order'       => 'ASC'
// 				)
// 		);
// 		if( ! $posts ) return;
	
// 		$out = '<select name="bhaa_runner_company"><option>Select a Company</option>';
// 		foreach( $posts as $p )
// 		{
// 			$out .= '<option value="' . get_permalink( $p->ID ) . '">' . esc_html( $p->post_title ) . '</option>';
// 		}
// 		$out .= '</select>';
// 		return $out;
// 	}
}
?>