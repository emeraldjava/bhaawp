<?php
abstract class BaseModel implements Table
{
	protected $wpdb;
	
	function __construct() {
		global $wpdb;
		$this->wpdb = $wpdb;
	}
}
?>