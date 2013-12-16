<?php
/**
 * EM_Widget
 * @author oconnellp
 * http://wp.tutsplus.com/tutorials/widgets/create-a-tabbed-widget-for-custom-post-types/
 */
class RaceResult_Widget extends WP_Widget
{
	function RaceResult_Widget()
	{
		parent::WP_Widget(false, $name = 'RaceResults');
	}
	
	function widget($args, $instance) {
		// outputs the content of the widget
		echo 'RaceResult_Widget_widget';
	}
	
	function update($new_instance, $old_instance) {
		// processes widget options to be saved
		echo 'RaceResult_Widget_update';
	}
	
	function form($instance) {
		// outputs the options form on admin
		echo 'RaceResult_Widget_form';
	}
	
	function register_widget()
	{
		register_widget('RaceResult_Widget');
	}
}
?>