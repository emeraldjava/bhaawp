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
		$result = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".$wpdb->race));
		$out = $this->loadTemplate('races',array('result' => $result));
		return $out;
	}
}
?>