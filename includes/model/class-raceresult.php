<?php
class RaceResult extends BaseModel implements Table
{
	var $post_id;
	
	const RAN = 'RAN';
	const RACE_REG = 'RACE_REG';
	const RACE_ORG = 'RACE_ORG';
	const PRE_REG = 'PRE_REG';
	
	function __construct($post_id) {
		parent::__construct();
		$this->post_id = $post_id;
	}
	
	public function getName() {
		return 'wp_bhaa_raceresult';
	}
	
	public function getCreateSQL() {
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
	
	public function registerRunner($runner,$racenumber,$standard=NULL,$money) {

		/* move validation code to the form */
		/*$runnerCount = $this->wpdb->get_var(
			$this->wpdb->prepare(
				'select exists(select * from wp_bhaa_raceresult where race=%d and runner=%d)',$this->post_id,$runner)
			);
		if($runnerCount)
			return 'Runner with id '.$runner.' is already registered!';

		$numberCount = $this->wpdb->get_var(
			$this->wpdb->prepare(
				'select exists(select * from wp_bhaa_raceresult where race=%d and racenumber=%d)',$this->post_id,$racenumber)
		);
		if($numberCount)
			return 'Race number '.$racenumber.' has already been assigned!';*/
		
		// add age category 
		$res = $this->wpdb->insert(
			$this->getName(),
				array(
					'race' => $this->post_id,
					'racenumber' => $racenumber,
					'runner' => $runner,
					'racetime' => date('H:i:s'),
					'standard' => $standard,
					'standardscoringset' => $money,
					'class' => RaceResult::RACE_REG
			));
		error_log('racedayresult registerRunner '.$res);
		return $res;
	}
	
	function preRegisterRunner($runner,$racenumber,$money)
	{
		$runnerCount = $this->wpdb->get_var(
			$this->wpdb->prepare(
				'select exists(select * from wp_bhaa_raceresult where race=%d and runner=%d and class="RACE_REG")',$this->post_id,$runner)
				//'select COUNT(*) from wp_bhaa_raceresult where race=%d and runner=%d and class="RACE_REG"',$this->post_id,$runner)
		);
		if($runnerCount)
			return 'Runner with id '.$runner.' is already registered!';
		
		$numberCount = $this->wpdb->get_var(
			$this->wpdb->prepare(
				'select exists(select * from wp_bhaa_raceresult where race=%d and racenumber=%d)',$this->post_id,$racenumber)
				//'select COUNT(*) from wp_bhaa_raceresult where race=%d and racenumber=%d',$this->post_id,$racenumber)
		);
		if($numberCount)
			return 'Race number '.$racenumber.' has already been assigned!';
		
		// update existing row
		$res = $this->wpdb->update(
				$this->getName(),
				array(
					'racenumber' => $racenumber,
					'racetime' => date('H:i:s'),
					'class' => RaceResult::RACE_REG,
					'standardscoringset' => $money),
				array(
					'race' => $this->post_id,
					'runner' => $runner,
					'class' => RaceResult::PRE_REG)
				);
		return $res;
	}
	
	public function deleteRunner($runner) {
		return $this->wpdb->delete(
				$this->getName(),
				array('race' => $this->post_id,
						'runner' => $runner,
						'class' => RaceResult::RACE_REG
						));
	}
	
	public function editRaceTime($id,$racetime) {
		return $this->wpdb->update(
			$this->getName(),
			array('racetime' => $racetime),
			array('id' => $id)
		);
	}
	
	/**
	 * select standardscoringset as type, count(*) as count
		from wp_bhaa_raceresult 
		where race=2849
		and class="RACE_REG"
		group by standardscoringset;
	 */
	public function getRegistrationTypes() {
		return $this->wpdb->get_results(
			$this->wpdb->prepare('select standardscoringset as type, count(*) as count
				from wp_bhaa_raceresult 
				where race=%d
				and class="RACE_REG"
				group by standardscoringset',$this->post_id)
		);
	}
	
	/**
	 * Add 10 league points to a user for a race
	 */
	public function addRaceOrganiser($runner) {
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
	public function deleteRaceOrganiser($runner) {
		return $this->wpdb->delete(
				$this->getName(),
				array('race' => $this->post_id,
						'runner' => $runner,
						'leaguepoints' => 10,
						'class' => RaceResult::RACE_ORG));
	}
	
	/**
	 * Insert a new result with default position and time, user will then edit
	 */
	function addDefaultResult() {
		return $this->addRaceResult(array('1','1','1','00:00:01','','','','','','','','','','','',''));
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
		// check if the runner exists
		$runner_id = trim($details[2]);
		$exists = Runner_Manager::get_instance()->runnerExists($runner_id);
		error_log('addRaceResult position '.$details[0].' number '.$details[1].', runner '.$runner_id.', exists '.$exists.'.');
		
		if(!$exists)
		{
			$dateofbirth = date("Y-m-d", strtotime(str_replace('/','-',$details[8])));
			//$match = $runner->matchRunner($details[5],$details[4],$dateofbirth);
			//if($match!=0)
			//{
			//	$runner_id = $match;
			//	error_log('matched existing runner '.' '.$details[5].' '.$details[4].' '.$runner_id);
			//}
			//else
			//{
				error_log('create new user with id "'.$runner_id.'" '.$details[5].' '.$details[4].' '.$dateofbirth);
				$runner_id = Runner_Manager::get_instance()->createNewUser($details[5],$details[4],'',$details[6],$dateofbirth,$runner_id);
				if($details[11]=='')
					update_user_meta( $runner_id, "bhaa_runner_company",1);
				error_log('created new runner '.$runner_id);
			//}
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
	
	function deleteRaceResults() {
		$res = $this->wpdb->delete(
			$this->getName(),
			array('race' => $this->post_id)
		);
	}
	
	private function getRace() {
		return new Race($this->post_id);
	}
	
	function updateAll() {
		$this->updatePositions();
		$this->updateRacePace();
		$this->updateRacePosInCat();
		$this->updateRacePosInStd();
		$this->updatePostRaceStd();
		$this->updateLeague();
	}
	
	function updateRacePace() {
		// update wp_bhaa_raceresult set actualstandard=getStandard(racetime,getRaceDistanceKm(race)) where race=2504;
		// SEC_TO_TIME(TIME_TO_SEC(_raceTime) / _distance)
		$SQL = sprintf('update %s set pace=SEC_TO_TIME(TIME_TO_SEC(racetime)/%f),actualstandard=getStandard(racetime,getRaceDistanceKm(race)) where race=%d',
			$this->getName(),$this->getRace()->getKmDistance(),$this->post_id);
		//error_log($SQL);
		$this->wpdb->query($this->wpdb->prepare($SQL));
	}
	
	/**
	 * Update the runners post race standard.
	 */
	function updatePostRaceStd() {
		$SQL = $this->wpdb->prepare('select position, runner, standard, actualstandard from wp_bhaa_raceresult where race=%d order by position asc',$this->post_id);
		//error_log($SQL);
		$runners = $this->wpdb->get_results($SQL);
		
		$postRaceStandard = 10;
		foreach($runners as $runner) {
			if($runner->standard != 0) {
				if($runner->standard  < $runner->actualstandard) {
					update_user_meta( $runner->runner,'bhaa_runner_standard',$runner->standard+1,$runner->standard);
					$postRaceStandard = $runner->standard+1;
					//error_log($runner->position.' up standard '.$runner->runner.'->'.($runner->standard+1));
				} elseif ($runner->standard > $runner->actualstandard) {
					update_user_meta( $runner->runner,'bhaa_runner_standard',$runner->standard-1,$runner->standard);
					$postRaceStandard = $runner->standard-1;
					//error_log($runner->position.' down standard '.$runner->runner.'->'.($runner->standard-1));
				} elseif($runner->standard == $runner->actualstandard){
					$postRaceStandard = $runner->actualstandard;
					//error_log($runner->position.' same standard '.$runner->runner.'->'.$runner->standard);
				}
			} else {
				update_user_meta( $runner->runner,'bhaa_runner_standard',$runner->actualstandard );
				$postRaceStandard = $runner->actualstandard;
				//error_log($runner->position.' new standard '.$runner->runner.'->'.$runner->actualstandard);
			}

			$UPDATE_POSTSTANDARD_SQL = $this->wpdb->prepare('update wp_bhaa_raceresult set poststandard=%d where race=%d and runner=%d',
				$postRaceStandard,$this->post_id,$runner->runner);
			//error_log($UPDATE_POSTSTANDARD_SQL);
			$this->wpdb->query($UPDATE_POSTSTANDARD_SQL);
		}
	}
	
	function updatePositions() {
		$this->wpdb->query($this->wpdb->prepare('call updatePositions(%d)',$this->post_id));
	}
	
	function updateRacePosInCat() {
		$this->wpdb->query($this->wpdb->prepare('call updatePositionInAgeCategory(%d)',$this->post_id));
	}
	
	function updateRacePosInStd() {
		$this->wpdb->query($this->wpdb->prepare('call updatePositionInStandard(%d)',$this->post_id));
	}
	
	function updateLeague() {
		$this->wpdb->query($this->wpdb->prepare('call updateRaceScoringSets(%d)',$this->post_id));
		$this->wpdb->query($this->wpdb->prepare('call updateRaceLeaguePoints(%d)',$this->post_id));
	}
	
	/**
	 * Method to update the pre and post standard of a runner in a race. Used to fix up the league.
	 * @param $id
	 * @param unknown $race
	 * @param unknown $runner
	 * @param unknown $standard
	 * @param unknown $poststandard
	 */
	function updateRunnersRaceResultStandard($id,$race,$runner,$time,$standard,$poststandard) {
		global $wpdb;
		$res = $wpdb->update(
			'wp_bhaa_raceresult',
			array('runner' => $runner,'race'=>$race,'standard' => $standard,'poststandard'=>$poststandard,'racetime'=>$time),
			array('id' => $id)
		);
		//error_log('updateRunnersRaceResultStandard('.$id.' '.$race.' '.$runner.' '.$standard.' '.$poststandard.') -> '.$res);
	}
}
?>