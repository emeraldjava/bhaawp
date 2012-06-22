<?php
class Runner
{
	function __construct()
	{
	}
	
	function Runner()
	{
		$this->__construct();
	}
	
	
	public function getTableName()
	{
		global $wpdb;
		return $wpdb->prefix .'bhaa_runner';
	}
		
	public function createTable()
	{
		global $wpdb;
		
		$charset_collate = '';
		if ( $wpdb->has_cap( 'collation' ) ) {
			if ( ! empty($wpdb->charset) )
				$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
			if ( ! empty($wpdb->collate) )
				$charset_collate .= " COLLATE $wpdb->collate";
		}
		
		$sql = "CREATE TABLE " . $this->getTableName() . " (
			`id` int(11) NOT NULL auto_increment,
			`firstname` varchar(40) NOT NULL,
			`surnamename` varchar(40) NOT NULL,
			`gender` enum('M','W') DEFAULT 'M',
			`status` varchar(15) NOT NULL,
			`standard` varchar(2) NOT NULL,
			`dateofbirth` date NOT NULL,
			PRIMARY KEY  (`id`)
			) ENGINE=InnoDB $charset_collate;";
		dbDelta($sql);
		
		$wpdb->insert( $this->getTableName(), 
			array( 'id' => '7713',
				 	'firstname'=>'P',
					'surnamename'=>'O C',
					'gender'=>'M',
					'status' => 'Tymon Park',
					'standard' => '4',
					'dateofbirth' => '1977-11-18' ) );
		$wpdb->insert( $this->getTableName(),
				array( 'id' => '2000',
					'firstname'=>'J',
					'surnamename'=>'Bloggs',
					'gender'=>"M",
					'status' => 'M',
					'standard' => '4',
					'dateofbirth' => '1980-01-01' ) );
		$wpdb->insert( $this->getTableName(),
				array( 'id' => '1000',
						'firstname'=>'Jane',
						'surnamename'=>'Doe',
						'gender'=>"W",
						'status' => 'M',
						'standard' => '4',
						'dateofbirth' => '1980-01-01' ) );
	}
}
?>