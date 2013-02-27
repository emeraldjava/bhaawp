<?php
class RaceResult extends BaseModel
{
	function __construct()
	{
		parent::__construct();
	}
	
	function getTableName()
	{
		return $this->wpdb->prefix.'bhaa_raceresult';
	}
	
	/**
	 * id int(11) NOT NULL AUTO_INCREMENT,
		race int(11) NOT NULL,
		runner int(11) NOT NULL,
		racetime time,
		position int(11),
		racenumber int(11),
		category varchar(5),
		standard int(11),
		paceKM time,
		class varchar(10),
		company int(11),
		
		
		[0] => 7
    [1] => 1719
    [2] => 1683
    [3] => 00:13:15
    [4] => McDonnell
    [5] => Karen
    [6] => F
    [7] => 13
    [8] => 25/03/1976
    [9] => 35
    [10] => Gardai
    [11] => 94
    [12] => Gardai
    [13] => 94
	 */
	public function addResult($race,$details)
	{
		$this->wpdb->show_errors();
		//error_log($race.''.print_r($details,true));
		$res = $this->wpdb->insert(
			$this->getTableName(),
			array(
				//'id' => NULL,
				'race' => $race,
				'position' => $details[0],
				'racenumber' => $details[1],
				'runner' => $details[2],
				'racetime' => $details[3],
				'category' => $details[9],
				'standard' => $details[7],
				'class' => 'RAN',
				'company' => $details[11])
		);	
		$this->wpdb->print_error();
		$this->wpdb->hide_errors();
		error_log($res);
		return $res;
	}
}
?>