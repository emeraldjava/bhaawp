<?php
class RaceResult extends BaseModel implements Table
{
	var $post_id;
	
	function __construct($post_id)
	{
		parent::__construct();
		$this->post_id = $post_id;
	}
	
	public function getName()
	{
		return $this->wpdb->prefix.'bhaa_raceresult';
	}
	
	public function getCreateSQL()
	{
		return 'id int(11) NOT NULL AUTO_INCREMENT,
		race int(11) NOT NULL,
		runner int(11) NOT NULL,
		racetime time,
		position int(11),
		racenumber int(11),
		category varchar(5),
		standard int(11),
		pace time,
		class varchar(10),
		company int(11),
		PRIMARY KEY (id)';
	}
	
	public function registerRunner($runner,$racenumber)
	{
		//$runner_id = $details[2];
		//$dateofbirth = date("Y-m-d", strtotime($details[8]));
	
// 		if($runner_id=='')
// 		{
// 			// lookup create runner
// 			$runner = new Runner();
// 			$match = $runner->matchRunner($details[5],$details[4],$dateofbirth);
// 			if($match!=0)
// 			{
// 				$runner_id = $match;
// 				error_log('matched existing runner '.$runner_id);
// 			}
// 			else
// 			{
// 				$runner_id = $runner->createNewUser($details[5], $details[4],'',$details[6],$dateofbirth);
// 				error_log('created new runner '.$runner_id);
// 			}
// 		}
		//$this->wpdb->show_errors();
		//error_log($race.''.print_r($details,true));
		$res = $this->wpdb->insert(
				$this->getName(),
				array(
						'race' => $this->post_id,
						'racenumber' => $racenumber,
						'runner' => $runner,
						'class' => 'RACE_REG')
		);
		//$this->wpdb->print_error();
		//$this->wpdb->hide_errors();
		//error_log($res);
		return $res;
	}
	
	/**
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
	public function addRaceResult($details)
	{
		$runner_id = $details[2];
		$dateofbirth = date("Y-m-d", strtotime($details[8]));
		
		if($runner_id=='')
		{
			// lookup create runner
			$runner = new Runner();
			$match = $runner->matchRunner($details[5],$details[4],$dateofbirth);
			if($match!=0)
			{
				$runner_id = $match;
				error_log('matched existing runner '.$runner_id);
			}
			else
			{
				$runner_id = $runner->createNewUser($details[5], $details[4],'',$details[6],$dateofbirth);
				error_log('created new runner '.$runner_id);
			}
		}

		//$this->wpdb->show_errors();
		//error_log($race.''.print_r($details,true));
		$res = $this->wpdb->insert(
			$this->getName(),
			array(
				'race' => $this->post_id,
				'position' => $details[0],
				'racenumber' => $details[1],
				'runner' => $runner_id,
				'racetime' => $details[3],
				'category' => $details[9],
				'standard' => $details[7],
				'class' => 'RAN',
				'company' => $details[11])
		);	
		//$this->wpdb->print_error();
		//$this->wpdb->hide_errors();
		//error_log($res);
		return $res;
	}
	
	function deleteRaceResults()
	{
		//$this->wpdb->show_errors();
		$res = $this->wpdb->delete(
			$this->getName(),
			array('race' => $this->post_id)
		);
		//$this->wpdb->print_error();
		//$this->wpdb->hide_errors();
	}
	
	private function getRace()
	{
		return new Race($this->post_id);
	}
	
	function updateAll()
	{
		$this->updateRacePace();
		$this->updatePostRaceStd();
		$this->updateRacePosInCat();
		$this->updateRacePosInStd();
	}
	
	function updateRacePace()
	{
		// update wp_bhaa_raceresult set actualstandard=getStandard(racetime,getRaceDistanceKm(race)) where race=2504;
		// SEC_TO_TIME(TIME_TO_SEC(_raceTime) / _distance)
		$SQL = sprintf('update %s set pace=SEC_TO_TIME(TIME_TO_SEC(racetime)/%f),actualstandard=getStandard(racetime,getRaceDistanceKm(race)) where race=%d',
			$this->getName(),$this->getRace()->getKmDistance(),$this->post_id);
		//error_log($SQL);
		$this->wpdb->query($this->wpdb->prepare($SQL));
	}
	
	function updatePostRaceStd()
	{
		$this->wpdb->query($this->wpdb->prepare('call updatePostRaceStandard(%d)',$this->post_id));
	}
	
	function updateRacePosInCat()
	{
		$this->wpdb->query($this->wpdb->prepare('call updatePositionInAgeCategory(%d)',$this->post_id));
	}
	
	function updateRacePosInStd()
	{
		$this->wpdb->query($this->wpdb->prepare('call updatePositionInStandard(%d)',$this->post_id));
	}
	
	function updateLeague()
	{
		$this->wpdb->query($this->wpdb->prepare('call updateRaceScoringSets(%d)',$this->post_id));
		$this->wpdb->query($this->wpdb->prepare('call updateRaceLeaguePoints(%d)',$this->post_id));
	}
}
?>