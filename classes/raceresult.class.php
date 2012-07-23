<?php
class RaceResult extends Base
{
	function __construct()
	{
	}
	
	function RaceResult()
	{
		$this->__construct();
	}
	
	function listRaceResult($attr)
	{
		global $wpdb;
		$result = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".$wpdb->raceresult));
		$out = $this->loadTemplate('raceresult',array('result' => $result));
		return $out;
	}
}
?>