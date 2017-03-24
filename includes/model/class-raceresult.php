<?php
class RaceResult extends BaseModel implements Table {

	protected static $instance = null;

	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new RaceResult();
		}
		return self::$instance;
	}

	function __construct() {
		parent::__construct();
	}

	const RAN = 'RAN';
	const RACE_REG = 'RACE_REG';
	const RACE_ORG = 'RACE_ORG';
	const PRE_REG = 'PRE_REG';

	public function getName() {
		return 'wp_bhaa_raceresult';
	}

	public function getRaceResult($racerresult) {
		return $this->getWpdb()->get_row("SELECT * FROM wp_bhaa_raceresult WHERE ID=".$racerresult);
	}

	public function deleteRaceResult($racerresult) {
		return $this->getWpdb()->delete('wp_bhaa_raceresult', array( 'id' => $racerresult ), array('%d'));
	}

	public function registerRunner($runner,$race,$racenumber,$standard=NULL,$money) {

		/* move validation code to the form */
		/*$runnerCount = $this->getWpdb()->get_var(
			$this->getWpdb()->prepare(
				'select exists(select * from wp_bhaa_raceresult where race=%d and runner=%d)',$race,$runner)
			);
		if($runnerCount)
			return 'Runner with id '.$runner.' is already registered!';

		$numberCount = $this->getWpdb()->get_var(
			$this->getWpdb()->prepare(
				'select exists(select * from wp_bhaa_raceresult where race=%d and racenumber=%d)',$race,$racenumber)
		);
		if($numberCount)
			return 'Race number '.$racenumber.' has already been assigned!';*/
		
		// add age category 
		$res = $this->getWpdb()->insert(
			$this->getName(),
				array(
					'race' => $race,
					'racenumber' => $racenumber,
					'runner' => $runner,
					'racetime' => date('H:i:s'),
					'standard' => $standard,
					'standardscoringset' => $money,
					'class' => RaceResult::RACE_REG
			));
		//error_log('racedayresult registerRunner '.$res);
		return $res;
	}
	
	function preRegisterRunner($runner,$race,$racenumber,$money)
	{
		$runnerCount = $this->getWpdb()->get_var(
			$this->getWpdb()->prepare(
				'select exists(select * from wp_bhaa_raceresult where race=%d and runner=%d and class="RACE_REG")',$race,$runner)
				//'select COUNT(*) from wp_bhaa_raceresult where race=%d and runner=%d and class="RACE_REG"',$race,$runner)
		);
		if($runnerCount)
			return 'Runner with id '.$runner.' is already registered!';
		
		$numberCount = $this->getWpdb()->get_var(
			$this->getWpdb()->prepare(
				'select exists(select * from wp_bhaa_raceresult where race=%d and racenumber=%d)',$race,$racenumber)
				//'select COUNT(*) from wp_bhaa_raceresult where race=%d and racenumber=%d',$race,$racenumber)
		);
		if($numberCount)
			return 'Race number '.$racenumber.' has already been assigned!';
		
		// update existing row
		$res = $this->getWpdb()->update(
				$this->getName(),
				array(
					'racenumber' => $racenumber,
					'racetime' => date('H:i:s'),
					'class' => RaceResult::RACE_REG,
					'standardscoringset' => $money),
				array(
					'race' => $race,
					'runner' => $runner,
					'class' => RaceResult::PRE_REG)
				);
		return $res;
	}
	
	public function deleteRunnerFromRace($runner,$race) {
		return $this->getWpdb()->delete(
				$this->getName(),
				array('race' => $race,
						'runner' => $runner,
						'class' => RaceResult::RACE_REG
						));
	}
	
	public function editRaceTime($id,$racetime) {
		return $this->getWpdb()->update(
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
	public function getRegistrationTypes($race) {
		return $this->getWpdb()->get_results(
			$this->getWpdb()->prepare('select standardscoringset as type, count(*) as count
				from wp_bhaa_raceresult 
				where race=%d
				and class="RACE_REG"
				group by standardscoringset',$race)
		);
	}
	
	/**
	 * Add 10 league points to a user for a race
	 */
	public function addRaceOrganiser($race,$runner) {
		return $this->getWpdb()->insert(
				$this->getName(),
				array('race' => $race,
					'runner' => $runner,
					'leaguepoints' => 10,
					'category' => 'S',
					'class' => RaceResult::RACE_ORG));
	}

	/**
	 * Delete assigned league points
	 */
	public function deleteRaceOrganiser($race,$organiser) {
		return $this->getWpdb()->delete(
				$this->getName(),
				array('race' => $race,
						'runner' => $organiser,
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
	public function addRaceResult($race,$details) {
		// check if the runner exists
		$runner_id = trim($details[2]);
		$exists = Runner_Manager::get_instance()->runnerExists($runner_id);
		if(!$exists)
		{
			$dateofbirth = date("Y-m-d", strtotime(str_replace('/','-',$details[8])));
			error_log('addRaceResult position '.$details[0].' number '.$details[1].', runner '.$runner_id.', exists '.$exists.'.');
			//$match = $runner->matchRunner($details[5],$details[4],$dateofbirth);
			//if($match!=0)
			//{
			//	$runner_id = $match;
			//	error_log('matched existing runner '.' '.$details[5].' '.$details[4].' '.$runner_id);
			//}
			//else
			//{
				$runner_id = RunnerAdmin::get_instance()->getNextRunnerId();
				error_log('create new user with id "'.$runner_id.'" '.$details[5].' '.$details[4].' '.$dateofbirth);
				$runner_id = Runner_Manager::get_instance()->createNewUser($details[5],$details[4],'',$details[6],$dateofbirth,$runner_id);
				if($details[11]=='')
					update_user_meta( $runner_id, "bhaa_runner_company",1);
				error_log($details[0].' Created new runner '.$runner_id.', '.$details[5].' '.$details[4]);
			//}
		} else {
			error_log($details[0].' Existing member '.$runner_id.', '.$details[5].' '.$details[4].' '.$runner_id);
		}
		
		// convert Senior to S
		$category = $details[9];
		if($details[9]=='Senior'){
			$category='S';
		}
			
		//$this->getWpdb()->show_errors();
		//error_log($race.''.print_r($details,true));
		if($details[0]!=0) {
			$res = $this->getWpdb()->insert(
				$this->getName(),
				array(
					'race' => $race,
					'position' => $details[0],
					'racenumber' => $details[1],
					'runner' => $runner_id,
					'racetime' => $details[3],
					'category' => $category,
					'standard' => ($details[7] == '') ? null : $details[7],
					'class' => RaceResult::RAN)
			);
		}
		//$this->getWpdb()->print_error();
		//$this->getWpdb()->hide_errors();
		//error_log($res);
		return $res;
	}
	
	function deleteRaceResults($race) {
		$res = $this->getWpdb()->delete(
			$this->getName(),
			array('race' => $race)
		);
	}
	
	private function getRace($race) {
		return new Race($race);
	}
	
	function updateAll($race) {
		$this->updatePositions($race);
		$this->updateRacePace($race);
		$this->updateRacePosInCat($race);
		$this->updateRacePosInStd($race);
		$this->updatePostRaceStd($race);
		$this->updateLeague($race);
	}
	
	function updateRacePace($race) {
		// update wp_bhaa_raceresult set actualstandard=getStandard(racetime,getRaceDistanceKm(race)) where race=2504;
		// SEC_TO_TIME(TIME_TO_SEC(_raceTime) / _distance)
		$SQL = sprintf('update %s set pace=SEC_TO_TIME(TIME_TO_SEC(racetime)/%f),actualstandard=getStandard(racetime,getRaceDistanceKm(race)) where race=%d',
			$this->getName(),$this->getRace($race)->getKmDistance(),$race);
		//error_log($SQL);
		$this->getWpdb()->query($this->getWpdb()->prepare($SQL));
	}
	
	/**
	 * Update the runners post race standard.
	 */
	function updatePostRaceStd($race) {
		$SQL = $this->getWpdb()->prepare('select position, runner, standard, actualstandard from wp_bhaa_raceresult where race=%d order by position asc',$race);
		//error_log($SQL);
		$runners = $this->getWpdb()->get_results($SQL);
		
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

			$UPDATE_POSTSTANDARD_SQL = $this->getWpdb()->prepare('update wp_bhaa_raceresult set poststandard=%d where race=%d and runner=%d',
				$postRaceStandard,$race,$runner->runner);
			//error_log($UPDATE_POSTSTANDARD_SQL);
			$this->getWpdb()->query($UPDATE_POSTSTANDARD_SQL);
		}
	}
	
	function updatePositions($race) {
		$this->getWpdb()->query($this->getWpdb()->prepare('call updatePositions(%d)',$race));
	}
	
	function updateRacePosInCat($race) {
		$this->getWpdb()->query($this->getWpdb()->prepare('call updatePositionInAgeCategory(%d,"M")',$race));
		$this->getWpdb()->query($this->getWpdb()->prepare('call updatePositionInAgeCategory(%d,"W")',$race));
	}
	
	function updateRacePosInStd($race) {
		$this->getWpdb()->query($this->getWpdb()->prepare('call updatePositionInStandard(%d)',$race));
	}
	
	function updateLeague($race) {
		$this->getWpdb()->query($this->getWpdb()->prepare('call updateRaceScoringSets(%d)',$race));
		$this->getWpdb()->query($this->getWpdb()->prepare('call updateRaceLeaguePoints(%d)',$race));
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
		error_log(sprintf('updateRunnersRaceResultStandard %d,%d,%d,%s,%d,%d',$id,$race,$runner,$time,$standard,$poststandard));
		if(isset($id)&&$id!=0) {
			$res = $wpdb->update(
				'wp_bhaa_raceresult',
				array('runner' => $runner,
					'race'=>$race,
					'standard' => $standard,
					'poststandard'=>$poststandard,
					'racetime'=>$time),
				array('id' => $id)
			);
		} else {
			$raceObj = new Race($race);
			$ageCategory = $raceObj->getRunnersAgeCategory($runner);
			$res = $wpdb->insert(
				'wp_bhaa_raceresult',
				array('runner' => $runner,
					'race'=>$race,
					'racetime'=>$time,
					'standard' => $standard,
					'poststandard'=>$poststandard,
					'class'=>"RAN",
					'position'=>1,
					'category'=>$ageCategory
			));
			$this->updatePositions($race);
		}
	}

	/**
	 * 		LPAD(wp_bhaa_raceresult.posincat,2,0) as padded,
	 */
	function getRaceResults($race) {
		$query = 'SELECT wp_bhaa_raceresult.*,wp_users.display_name,
			wp_users.user_nicename,gender.meta_value as gender,
			wp_posts.id as cid,wp_posts.post_title as cname
			FROM wp_bhaa_raceresult
			left join wp_users on wp_users.id=wp_bhaa_raceresult.runner
			left join wp_usermeta gender on (gender.user_id=wp_users.id and gender.meta_key="bhaa_runner_gender")
			left join wp_usermeta company on (company.user_id=wp_users.id and company.meta_key="bhaa_runner_company")
			left join wp_posts on (wp_posts.post_type="house" and company.meta_value=wp_posts.id)
			where race='.$race.' and wp_bhaa_raceresult.class="RAN" and position<=500 ORDER BY position';
		return $this->getWpdb()->get_results($query,OBJECT);
	}

	function getRunnerResults($runner) {
		$query = 'select
			d.event,
			d.eventname,
			d.eventdate,
			d.race,
			concat(d.distance,d.unit) as distance,
			rr.racetime,
			rr.position,
			rr.standard,
			rr.poststandard,
			rr.runner,
			rr.id,
			posts.post_title,
			posts.post_name
			from wp_bhaa_race_detail d
			join wp_bhaa_raceresult rr on (rr.race=d.race and d.leaguetype="I")
			join wp_posts posts on (posts.ID=d.event)
			where rr.runner=' . $runner . ' AND rr.class="RAN" order by d.eventdate desc';
		return $this->getWpdb()->get_results($query, ARRAY_A);
	}

	/**
	 * TODO
	 * -- find raceresult without a runner
	select * from wp_bhaa_raceresult
	left join wp_users on wp_users.id=runner
	where wp_users.id is null
	 */
	function getRaceResultWithoutRunner() {

	}

	function getRunnersPerRace($race) {
		//global $post, $wpdb;
		$rowcount = $this->getWpdb()->get_var("SELECT COUNT(id) FROM wp_bhaa_raceresult rr WHERE race='.$race.'");
		return $rowcount;
	}
}
?>