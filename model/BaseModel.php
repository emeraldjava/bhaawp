<?php
class BaseModel
{
	protected $wpdb;
	
	function __construct()
	{
		global $wpdb;
		$this->wpdb = $wpdb;
	}
}
?>