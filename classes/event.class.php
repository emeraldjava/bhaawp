<?php
class Event
{
	function __construct()
	{
	}
	
	function Event()
	{
		$this->__construct();
	}
	
	public function getTableName()
	{
		global $wpdb;
		return $wpdb->prefix .'bhaa_event';
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
				`name` varchar(40) NOT NULL,
				`tag` varchar(15) NOT NULL,
				`location` varchar(100) NOT NULL,
				`date` date NOT NULL,
				PRIMARY KEY  (`id`)
			) ENGINE=InnoDB $charset_collate;";
		dbDelta($sql);
		
		
		$wpdb->insert( $this->getTableName(), 
			array( 'id' => '201001',
				 	'name'=>'South Dublin County Council',
					'tag'=>'sdcc2012',
					'location' => 'Tymon Park',
					'date' => '2010-01-05' ) );
		$wpdb->insert( $this->getTableName(),
				array( 'id' => '201101',
						'name'=>'RTE',
						'tag'=>'rte2011',
						'location' => 'RTE',
						'date' => '2011-05-01' ) );
		$wpdb->insert( $this->getTableName(),
				array( 'id' => '201205',
						'name'=>'KCLUB',
						'tag'=>'kclub2012',
						'location' => 'k-club',
						'date' => '2012-04-01' ) );
		$wpdb->insert( $this->getTableName(),
				array( 'id' => '201210',
						'name'=>'DublinHalf',
						'tag'=>'dublinhalf2012',
						'location' => 'Park',
						'date' => '2012-07-01' ) );
	}
}
?>