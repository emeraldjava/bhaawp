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
// 		echo $attr['id'];
// 		extract( shortcode_atts( array(
// 				'event' => 'humm'
// 		), $atts ) );
		
		//return "foo = {$foo}";
		
// 		if (isset($wp_query->query_vars['event']))
// 		{
// 			print $wp_query->query_vars['event'];
// 		}
		
		return 'This the event specific page for id :: '.$attr;
	}
	
// 	function listEvents($attr)
// 	{
// 		global $wpdb;
// 		$events = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".$wpdb->event));
// 		$filename = "events";
// 		$out = $this->loadTemplate( 
// 			$filename,
// 			array('events' => $events) 
// 		);
// 		return $out;
// 	}
}
?>