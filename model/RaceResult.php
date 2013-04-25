<?php
class RaceResult extends BaseModel implements Table
{
	var $post_id;
	
	const RAN = 'RAN';
	const RACE_REG = 'RACE_REG';
	const RACE_ORG = 'RACE_ORG';
	const PRE_REG = 'PRE_REG';
	
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
	
	public function registerRunner($runner,$racenumber,$standard=NULL)
	{
		$runnerCount = $this->wpdb->get_var(
			$this->wpdb->prepare(
				'select COUNT(*) from wp_bhaa_raceresult where race=%d and runner=%d',$this->post_id,$runner));
		if($runnerCount!=0)
			return 'Runner with id '.$runner.' is already registered!';

		$numberCount = $this->wpdb->get_var(
			$this->wpdb->prepare(
				'select COUNT(*) from wp_bhaa_raceresult where race=%d and racenumber=%d',$this->post_id,$racenumber));
		if($numberCount!=0)
			return 'Race number '.$racenumber.' has already been assigned!';
		
		// add age category 
		$res = $this->wpdb->insert(
				$this->getName(),
				array(
						'race' => $this->post_id,
						'racenumber' => $racenumber,
						'runner' => $runner,
						'standard' => $standard,
						'class' => RaceResult::RACE_REG
				));
		return $res;
	}
	
	function preRegisterRunner($runner,$racenumber)
	{
		$runnerCount = $this->wpdb->get_var(
			$this->wpdb->prepare(
				'select COUNT(*) from wp_bhaa_raceresult where race=%d and runner=%d and class="RACE_REG"',$this->post_id,$runner));
		if($runnerCount!=0)
			return 'Runner with id '.$runner.' is already registered!';
		
		$numberCount = $this->wpdb->get_var(
			$this->wpdb->prepare(
				'select COUNT(*) from wp_bhaa_raceresult where race=%d and racenumber=%d',$this->post_id,$racenumber));
		if($numberCount!=0)
			return 'Race number '.$racenumber.' has already been assigned!';
		
		// update existing row
		$res = $this->wpdb->update(
				$this->getName(),
				array(
					'racenumber' => $racenumber,
					'class' => RaceResult::RACE_REG),
				array(
					'race' => $this->post_id,
					'runner' => $runner,
					'class' => RaceResult::PRE_REG)
				);
		return $res;
	}
	
	public function deleteRunner($runner)
	{
		return $this->wpdb->delete(
				$this->getName(),
				array('race' => $this->post_id,
						'runner' => $runner,
						'class' => RaceResult::RACE_REG
						));
	}
	
	
	/**
	 * Add 10 league points to a user for a race
	 */
	public function addRaceOrganiser($runner)
	{
		return $this->wpdb->insert(
				$this->getName(),
				array('race' => $this->post_id,
					'runner' => $runner,
					'leaguepoints' => 10,
					'class' => RaceResult::RACE_ORG));
	}

	/**
	 * Delete assigned league points
	 */
	public function deleteRaceOrganiser($runner)
	{
		return $this->wpdb->delete(
				$this->getName(),
				array('race' => $this->post_id,
						'runner' => $runner,
						'leaguepoints' => 10,
						'class' => RaceResult::RACE_ORG));
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
		
		$runner_id = trim($details[2]);
		$dateofbirth = date("Y-m-d", strtotime($details[8]));
		error_log('addRaceResult position '.$details[0].' number '.$details[1].' dob '.$dateofbirth);
		
		if($runner_id=='')
		{
			// lookup create runner
			$runner = new Runner();
			
			$SQL = $this->wpdb->prepare("select wp_em_bookings.person_id as id from wp_em_bookings
join wp_users on wp_users.id=wp_em_bookings.person_id
join wp_usermeta fn on (wp_em_bookings.person_id=fn.user_id and fn.meta_key='first_name')
join wp_usermeta ln on (wp_em_bookings.person_id=ln.user_id and ln.meta_key='last_name')
where wp_em_bookings.event_id=112 
and fn.meta_value=%s and ln.meta_value=%s",$details[5],$details[4]);
			//error_log($SQL);
			
			$id = $this->wpdb->get_var($SQL);
			if(isset($id))
			{
				error_log("matched kclub pre-reg runner ".' '.$details[5].' '.$details[4].' '.$runner_id);
				$runner_id=$id;
			}
			else if($runner_id == '')
			{
				$match = $runner->matchRunner($details[5],$details[4],$dateofbirth);
				if($match!=0)
				{
					$runner_id = $match;
					error_log('matched existing runner '.' '.$details[5].' '.$details[4].' '.$runner_id);
				}
				else
				{
					error_log("create new user ".' '.$details[5].' '.$details[4]);
					$runner_id = $runner->createNewUser($details[5], $details[4],'',$details[6],$dateofbirth);
					if($details[11]=='')
						update_user_meta( $runner_id, "bhaa_runner_company",1);
					error_log('created new runner '.$runner_id);
				}
			}
		}
		else 
		{
			error_log("existing member ".' '.$details[5].' '.$details[4].' '.$runner_id);
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
				'standard' => ($details[7]== '') ? null : $details[7],
				'class' => RaceResult::RAN)
				//'company' => ($company== '') ? 1 : $details[7])
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