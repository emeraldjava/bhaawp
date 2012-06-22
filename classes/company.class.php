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
	
	public function listCompanies()
	{
		return "this will be a list of companies!";
	}
	
	public function getTableName()
	{
		global $wpdb;
		return $wpdb->prefix .'bhaa_company';
	}
	
	public function createTable()
	{
		global $wpdb;
		include_once( ABSPATH.'/wp-admin/includes/upgrade.php' );
		
		$charset_collate = '';
		if ( $wpdb->has_cap( 'collation' ) ) {
			if ( ! empty($wpdb->charset) )
				$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
			if ( ! empty($wpdb->collate) )
				$charset_collate .= " COLLATE $wpdb->collate";
		}
		
		$sql = "CREATE TABLE " . $this->getTableName() . " (
			id INT(11) NOT NULL auto_increment,
			name VARCHAR(100) NOT NULL,
			web VARCHAR(100),
			image VARCHAR(100),
			PRIMARY KEY  (id)
		) ENGINE=InnoDB $charset_collate;";
		dbDelta($sql);
				
		$wpdb->insert( $this->getTableName(), 
			array( 'name' => 'BHAA', 'web' => 'http://www.bhaa.ie', 'image' => 'http://www.bhaa.ie' ) );
	}
}
?>