<?php
class RaceResult
{
	function __construct()
	{
	}
	
	function RaceResult()
	{
		$this->__construct();
	}
	
	public function getTableName()
	{
		global $wpdb;
		return $wpdb->prefix .'bhaa_raceresult';
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
			`race` int(11) NOT NULL,
			`runner` int(11) NOT NULL,
			`time` time,
			`number` int(11)
			) ENGINE=InnoDB $charset_collate;";
		dbDelta($sql);
		
		$wpdb->insert( $this->getTableName(), 
			array( 'race' => '201001','runner'=>'7713','time'=>'00:50:00','number'=>'3'),
			array( 'race' => '201001','runner'=>'1000','time'=>'00:51:00','number'=>'1'),
			array( 'race' => '201001','runner'=>'2000','time'=>'00:54:00','number'=>'2')
		);
		$wpdb->insert( $this->getTableName(),
			array( 'race' => '201102','runner'=>'7713','time'=>'00:50:00','number'=>'13'),
			array( 'race' => '201102','runner'=>'1000','time'=>'00:51:00','number'=>'11'),
			array( 'race' => '201102','runner'=>'2000','time'=>'00:54:00','number'=>'12')
		);
		$wpdb->insert( $this->getTableName(),
			array( 'race' => '201210','runner'=>'7713','time'=>'00:50:00','number'=>'23'),
			array( 'race' => '201210','runner'=>'1000','time'=>'00:51:00','number'=>'21'),
			array( 'race' => '201210','runner'=>'2000','time'=>'00:54:00','number'=>'22')
		);
		$wpdb->insert( $this->getTableName(),
			array( 'race' => '201220','runner'=>'7713','time'=>'00:50:00','number'=>'33'),
			array( 'race' => '201220','runner'=>'1000','time'=>'00:51:00','number'=>'31'),
			array( 'race' => '201220','runner'=>'2000','time'=>'00:54:00','number'=>'32')
		);
	}
}
?>