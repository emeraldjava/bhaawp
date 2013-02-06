<?php

class RunnerSearchWidget extends WP_Widget
{
	function __construct() {
		$widget_ops = array('classname' => 'RunnerSearchWidget', 'description' => __( "A runner search form for your site") );
		parent::__construct('runnersearch', __('Runner Search'), $widget_ops);
	}
	
	function register()
	{
		register_widget('RunnerSearchWidget');
	}
	
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
		'<span class="text"><input name="query" id="runner_search" type="text" value="User Search"/></span>'.
		'</fieldset>'.
		'</form>';
	}
	
	// http://codex.wordpress.org/Class_Reference/WP_User_Query
}
?>