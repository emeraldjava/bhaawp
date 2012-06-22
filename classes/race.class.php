<?php
class Race
{
	function __construct()
	{
	}
	
	function Race()
	{
		$this->__construct();
	}
	
	public function getTableName()
	{
		global $wpdb;
		return $wpdb->prefix .'bhaa_race';
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
				`event` varchar(40) NOT NULL,
				`distance` varchar(15) NOT NULL,
				`unit` enum('KM','Mile' DEFAULT 'KM',
				PRIMARY KEY  (`id`)
			) ENGINE=InnoDB $charset_collate;";
		dbDelta($sql);
		
		
		$wpdb->insert( $this->getTableName(), 
			array( 'id' => '201001',
					'event'=>'201001',
				 	'distance'=>'5',
					'unit'=>'KM') );
		$wpdb->insert( $this->getTableName(),
				array( 'id' => '201102',
					'event'=>'201001',
				 	'distance'=>'8',
					'unit'=>'KM') );
		$wpdb->insert( $this->getTableName(),
				array( 'id' => '201210',
					'event'=>'201205',
				 	'distance'=>'10',
					'unit'=>'KM') );
		$wpdb->insert( $this->getTableName(),
				array( 'id' => '201220',
						'event'=>'201210',
				 	'distance'=>'9',
					'unit'=>'KM') );
	}
}
?>