<?php

/**
 * http://plugins.svn.wordpress.org/wp-ajax-search-widget/trunk/wp-ajax-search-widget.php
 * @author oconnellp
 *
 */
class RunnerSearchWidget extends WP_Widget {
	
	function __construct() {
		$widget_ops = array('classname' => 'RunnerSearchWidget', 
			'description' => __( "A runner search form for your site") );
		parent::__construct('runnersearch', __('BHAA Runner Search'), $widget_ops);
	
		add_action('init', array( $this, 'bhaa_rsw_register_script' ) ) ;
		
		// only load scripts when an instance is active
		//if ( is_active_widget( false, false, $this->id_base ) && !is_admin())
		add_action( 'wp_footer', array( $this, 'bhaa_rsw_enqueue_script' ) );
		
		add_action('wp_ajax_bhaa_rsw', array($this,'bhaa_rsw_ajax') );
		add_action('wp_ajax_nopriv_bhaa_rsw', array( $this, 'bhaa_rsw_ajax') );
	}
	
	/**
	 * Add the javascript ajax logic
	 */
	function bhaa_rsw_register_script(){
		wp_register_script( 
			'bhaa_rsw', 
			plugins_url('./../assets/js/bhaa_rsw.js', __FILE__), 
			array('jquery','jquery-ui-autocomplete'), '1.0', true);
		
		wp_localize_script('bhaa_rsw','bhaa_rsw', array(
			'ajax_url' => add_query_arg(
				array('action' => 'bhaa_rsw',
					'_wpnonce' => wp_create_nonce( 'bhaa_rsw' )),
				untrailingslashit(admin_url('admin-ajax.php'))),
		));
		//wp_enqueue_script( 'bhaa_rsw' );
		//error_log('bhaa_rsw_register_script');
	}
	
	function bhaa_rsw_enqueue_script(){
		wp_enqueue_script( 'bhaa_rsw' );
	}
	
	/**
	 * The method that will be called
	 */
	function bhaa_rsw_ajax(){
		
		if ( wp_verify_nonce($_REQUEST['_wpnonce'], 'bhaa_rsw') ) {
			
			// clean up the query
			$match = trim(stripslashes($_REQUEST['match']));
			error_log('bhaa_rsw_ajax '.$match);
			
			$suggestions=array();
			// cancel if no search term is set
			//if ( !$match ) die();
			
			
			// http://wordpress.stackexchange.com/questions/105168/how-can-i-search-for-a-worpress-user-by-display-name-or-a-part-of-it
			$args = array(
			//		'search'         => "%".$match."%",
			//		'search_columns' => array(
			//				'ID',
			//				'user_login',
			//				'user_nicename'
			//				'user_email',
			//				'user_url',
			//		),
					'number' => 10,
					'fields' => 'all',
					'meta_query' => array(
							'relation' => 'AND',
							array('key' => 'nickname','compare' => 'like', 'value' => $match),
							array('key' => 'bhaa_runner_status','compare'=>'!=','value'=>'D')
					)
			);
	
			//error_log(print_r($args,true));
			$user_query = new WP_User_Query( $args );
			$runners = $user_query->get_results();
			if (!empty($runners))
			{
				foreach ($runners as $runner)
				{
					$runner_info = get_userdata($runner->ID);
					$suggestion = array();
					$suggestion['label'] = $runner_info->display_name;
					$suggestion['link'] = sprintf('%s/runner/?bhaaid=%d',get_site_url(),$runner_info->ID);
					$suggestions[]=$suggestion;
				}
			}
			//wp_reset_postdata();
			$response = json_encode(array('matches'=>$suggestions));
			//$users_found = $users->get_results();
			//$response = json_encode(array('matches'=>$users_found));
			echo $response;
			die();
		}
	}
	
	/**
	 * Render the widget
	 * @param unknown $args
	 * @param unknown $instance
	 */
	function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
	
		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;
	
		// Use current theme search form if it exists
		$this->get_user_search_form();
	
		echo $after_widget;
	}
	
	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '') );
		$title = $instance['title'];
		echo '<p><label for="'.$this->get_field_id('title').'">Title:
			<input class="widefat" id="'.$this->get_field_id('title').'". 
				name="'.$this->get_field_name('title').'" type="text". 
				value="'.esc_attr($title).'" /></label></p>';
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$new_instance = wp_parse_args((array) $new_instance, array( 'title' => ''));
		$instance['title'] = strip_tags($new_instance['title']);
		return $instance;
	}
	
	private function get_user_search_form()
	{
		echo '<form class="search_form" action="<?php echo home_url(); ?>/" method="get">'.
		'<fieldset>'.
		'<span class="text"><input id="bhaa_rsw" type="text" value=""/></span>'.
		'</fieldset>'.
		'</form>';
	}
	
	// http://codex.wordpress.org/Class_Reference/WP_User_Query
}
?>