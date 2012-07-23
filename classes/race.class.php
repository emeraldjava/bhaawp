<?php
class Race extends Base
{
	function __construct()
	{
	}
	
	function Race()
	{
		$this->__construct();
	}
	
	function listRaces($attr)
	{
		global $wpdb;
		$resultx = $wpdb->get_results($wpdb->prepare("SELECT id,event,distance,unit FROM ".$wpdb->race));
		return $this->loadTemplate('races',array('result' => $resultx));
	}
}
?>