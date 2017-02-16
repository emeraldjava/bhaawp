<?php
abstract class BaseModel implements Table
{
	private $wpdb;
	
	function __construct() {
		global $wpdb;
		$this->wpdb = $wpdb;
	}
	
	function getWpdb(){
		return $this->wpdb;
	}
}
?>