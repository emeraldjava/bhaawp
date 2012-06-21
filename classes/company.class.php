<?php
class Company
{
	function __construct()
	{
	}
	
	function Company()
	{
		$this->__construct();
	}
	
	public function getTableName()
	{
		global $wpdb;
		return $wpdb->prefix .'bhaa_company';
	}
	
	public function createTable()
	{
		$sql = "CREATE TABLE " . $this->getTableName() . " (
			id INT(11) NOT NULL auto_increment,
			name VARCHAR(100) NOT NULL default '',
			web VARCHAR(100),
			image VARCHAR(100),
			PRIMARY KEY  (id)
		);" ;
		dbDelta($sql);
		
		global $wpdb;
		$rows_affected = $wpdb->insert( $this->getTableName(), 
			array( 'name' => 'BHAA', 'web' => 'http://www.bhaa.ie', 'image' => 'http://www.bhaa.ie' ) );
	}
}
?>