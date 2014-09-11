<?php

/**
 * http://plugins.svn.wordpress.org/wp-ajax-search-widget/trunk/wp-ajax-search-widget.php
 * @author oconnellp
 *
 */
class RunnerSearchWidget extends WP_Widget {
	
	function __construct() {
		$widget_ops = array('classname' => 'RunnerSearchWidget', 'description' => __( "A runner search form for your site") );
		parent::__construct('runnersearch', __('Runner Search'), $widget_ops);
	}
	
	function init_widget() {
//		$widget_ops = array('classname' => 'RunnerSearchWidget', 'description' => __( "A runner search form for your site") );
	//	parent::__construct('runnersearch', __('Runner Search'), $widget_ops);
	
		//add_action('init', array( $this, 'wpasw_register_script' ) ) ;
		
		// only load scripts when an instance is active
		//if ( is_active_widget( false, false, $this->id_base ) && !is_admin())
		//	add_action( 'wp_footer', array( $this, 'wpasw_print_script' ) );
		
		//add_action('wp_ajax_wpasw', array( $this, 'wpasw_ajax') );
		//add_action('wp_ajax_nopriv_wpasw', array( $this, 'wpasw_ajax') );
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
		'<span class="text"><input id="runner_search" type="text" value=""/></span>'.
		'</fieldset>'.
		'</form>';
	}
	
	// http://codex.wordpress.org/Class_Reference/WP_User_Query
}
?>