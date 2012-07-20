<?php
class Event extends Base
{
	function __construct()
	{
	}
	
	function Event()
	{
		$this->__construct();
	}
	
	function getEvent($attr)
	{
		extract( shortcode_atts( array(
				'event' => 'humm'
//				'bar' => 'something else',
		), $atts ) );
		
		//return "foo = {$foo}";
		
		if (isset($wp_query->query_vars['event']))
		{
			print $wp_query->query_vars['event'];
		}
		
		return 'This is an event. :: '.$event.' - get_query_var= '.get_query_var('event');
	}
	
	function listEvents($attr)
	{
		global $wpdb;
		$events = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".$wpdb->event));
		$filename = "events";
		$out = $this->loadTemplate( 
			$filename,
			array('events' => $events) 
		);
		return $out;
	}
}
?>