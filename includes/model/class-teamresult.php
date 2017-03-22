<?php
class TeamResult extends BaseModel 
{
	var $race;
	
	function __construct($race) {
		parent::__construct();
		$this->race = $race;
	}
	
	public function getName() {
		return 'wp_bhaa_teamresult';
	}
	
	/**
	 * array(24) { [0]=> string(1) "1" [1]=> string(29) "BHAA Dublin City Council 2013" [2]=> string(13) "Ladies 2 Mile" 
	 * [3]=> string(6) "Womens" [4]=> string(1) "1" [5]=> string(1) "1" [6]=> string(1) "4" [7]=> string(1) "1" 
	 * [8]=> string(2) "48" [9]=> string(20) "Accountants Ladies A" [10]=> string(2) "45" [11]=> string(4) "1047" 
	 * [12]=> string(17) "Mary Purdue-Smyth" [13]=> string(6) "Female" [14]=> string(15) "System Dynamics" 
	 * [15]=> string(2) "20" [16]=> string(8) "00:14:10" [17]=> string(2) "15" [18]=> string(1) "W" [19]=> string(2) "21" 
	 * [20]=> string(3) "431" [21]=> string(2) "48" [22]=> string(1) "1" [23]=> string(5) "5798 " }

     * 0  ,1                  ,2       ,3   ,4  ,5         ,6     ,7         ,8        ,9        ,10      ,11    ,12  ,13    ,14     ,15         ,16         ,17 ,18   ,19     ,20        ,21    ,22     ,23
	 * Pos,RaceName,EventDescr,TeamType,Team Pos,TeamTypeId,TeamId,TempTeamId,Team Time,Team Name,Team Std,RaceNo,Name,Gender,Company,Overall Pos,Finish Time,Std,Class,Team No,Company No,RaceId,EventId,MemberNo
	 * 1,BHAA Dublin City Council 2013,Ladies 2 Mile,Womens,1,1,4,1,48,Accountants Ladies A,45,1789,Ann Marie Coughlan,Female,McInerney Saunders,11,00:13:32,15,W,21,549,48,1,7048
	 */
	public function addResult($row) {
		// calculate the team league points.
		$leaguepoints = 7 - $row[0];
		if($leaguepoints<=1){
			$leaguepoints=1;
		}
		
		$res = $this->getWpdb()->insert(
			$this->getName(),
			array(
				'race'=>$this->race,
				'class'=>$row[18],
				'position'=>$row[0],
				'team'=>$row[19],
				'teamname'=>$row[9],
				'totalpos'=>$row[8],
				'totalstd'=>$row[10],
				'runner'=>$row[23],
				'pos'=>$row[15],
				'std'=>$row[17],
				'racetime'=>$row[16],
				'company'=>$row[20],
				'companyname'=>substr($row[14],0,19),
				'leaguepoints'=> $leaguepoints)
		);
	}
	
	public function deleteResults() {
		return $this->getWpdb()->delete(
			$this->getName(),
			array('race' => $this->race
		));
	}
	
	function getTeamResults() {
		return $this->getWpdb()->get_results(
			$this->getWpdb()->prepare('select wp_bhaa_teamresult.*,wp_users.display_name from wp_bhaa_teamresult
				join wp_users on wp_users.id=wp_bhaa_teamresult.runner
				where race=%d order by class, position',$this->race)
		);
	}
	
	public function getHouseResults($team,$limit=30) {
		return $this->getWpdb()->get_results(
			$this->getWpdb()->prepare('select race.post_title,tr.class,tr.position,MAX(tr.leaguepoints) as leaguepoints from wp_bhaa_teamresult tr
				join wp_posts race on tr.race=race.id
				where team=%d
				group by team,race
				order by race asc
				limit %d',$team,$limit)
		);
	}

//	public function getEventTeamResults($event) {
//		$query = $this->getWpdb()->prepare('
//					SELECT wp_bhaa_teamresult.*,wp_posts.post_title as teamname
//					FROM wp_bhaa_teamresult
//					join wp_posts on wp_posts.post_type="house" and wp_bhaa_teamresult.team=wp_posts.id
//					where race IN (select p2p_to from wp_p2p where p2p_from=%d)
//					order by class, positiontotal',$event);
//		return $this->wbdb->get_results($query,ARRAY_A);
//	}
	
	/**
	 * We'll do the html table generation here for the moment.
	 */
	
	public function getRaceTeamResultTable() {
		$results = $this->getTeamResults();
		$table = '<h2 race="'.$this->race.'">Team Results</h2>';
		//$table .= $this->teamSummary();
		$table .= $this->displayClassTable($results,'A');
		$table .= $this->displayClassTable($results,'B');
		$table .= $this->displayClassTable($results,'C');
		$table .= $this->displayClassTable($results,'D');
		$table .= $this->displayClassTable($results,'W');
		$table .= '<br/>';
		return $table;
	}
	
//	private function teamSummary() {
//		return '[counters_box]
//			[counter_box value="5"]A[/counter_box]
//			[counter_box value="5"]B[/counter_box]
//			[counter_box value="6"]C[/counter_box]
//			[counter_box value="8"]D[/counter_box]
//			[counter_box value="2"]W[/counter_box]
//			[/counters_box]';
//	}
	
	private function displayClassTable($results,$class) {
		
		// ["id"]=> string(2) "64" ["race"]=> string(4) "2595" ["class"]=> string(1) "W" ["position"]=> string(1) "1" 
		// ["team"]=> string(2) "21" ["teamname"]=> string(20) "Accountants Ladies A" ["totalpos"]=> string(2) "48" 
		// ["totalstd"]=> string(2) "45" ["runner"]=> string(4) "7048" ["pos"]=> string(2) "11" ["std"]=> string(2) "15" 
		// ["racetime"]=> string(8) "00:13:32" ["company"]=> string(3) "549" ["companyname"]=> string(18) "McInerney Saunders" 
		// ["leaguepoints"]=> string(1) "0" }
		$table = '';
		$header='';
		$position=0;
		$count=0;
		foreach($results as $row)
		{
			if($row->class==$class) {
				//var_dump($row);
				if($row->class!=$header) {
					$header = $row->class;
					$position = $row->position;
					//$table .= $this->generateRow('<h4><b>Class '.$row->class.'</b></h4>','','','','');
					$table .= '<h2><b>Class '.$row->class.'</b></h2>';

				}
				
				//first row of a new team
				if($count==0) {
					$table .= '<table class="table borderless fixed" width="90%">';
					$position = $row->position;
					// start table
					$house_url = sprintf('<a href="/?post_type=house&p=%d"><b>%s</b></a>',$row->team,$row->teamname);
					$table .= $this->generateHeaderRow('<b>'.$row->class.$row->position.' -> '.$house_url.'</b>','','','<b>Position</b>','<b>Standard</b>','');
					// add first row
					$table .= $this->generateHeaderRow('Athlete','Time','Company',$row->totalpos,$row->totalstd);
				}
				
				$runner_url = sprintf('<a href="/runner/?id=%s"><i>%s</i></a>',$row->runner,$row->display_name);
				$table .= $this->generateRow($runner_url,$row->racetime,$row->companyname,$row->pos,$row->std);
				$count++;
				if($count==3) {
					// add second/third row
					$count = 0;
					$position = 0;
					$table .= '</table>';
				}

			}
		}
		return $table;
	}

	public function generateHeaderRow($name='',$time='',$company='',$position='',$standard='',$style='') {
		return sprintf('<tr class="%s">
			<!-- bgcolor="#FF0000" -->
			<th style="width: 50px;">%s</th>
			<th style="width: 50px;">%s</th>
			<th style="width: 50px;">%s</th>
			<th style="width: 20px;">%s</th>
			<th style="width: 20px;">%s</th>
			</tr>',$style,$name,$time,$company,$position,$standard);
	}
	
	public function generateRow($name='',$time='',$company='',$position='',$standard='',$style='') {
		return sprintf('<tr class="%s">
			<td style="width: 50px;">%s</td>
			<td style="width: 50px;">%s</td>
			<td style="width: 50px;">%s</td>
			<td style="width: 20px;">%s</td>
			<td style="width: 20px;">%s</td>
			</tr>',$style,$name,$time,$company,$position,$standard);
	}
	
	/**
	 * Add 6 league points to the team
	 */
	public function addTeamOrganiserPoints($team) {
		return $this->getWpdb()->insert(
			$this->getName(),
			array('race' => $this->race,
				'team' => $team,
				'leaguepoints' => 6,
				'class' => 'R'));
	}
	
	/**
	 * Delete assigned league points
	 */
	public function deleteTeamOrganiserPoints($team) {
		return $this->getWpdb()->delete(
			$this->getName(),
			array('race' => $this->race,
				'team' => $team,
				'leaguepoints' => 6,
				'class' => 'R'));
	}

	function getTeamResultsByEvent($event) {
		$query = $this->getWpdb()->prepare('
			SELECT wp_bhaa_teamresult.*,wp_posts.post_title as teamname FROM wp_bhaa_teamresult
			join wp_posts on wp_posts.post_type="house" and wp_bhaa_teamresult.team=wp_posts.id
			where race IN (select p2p_to from wp_p2p where p2p_from=%d)
			order by class, positiontotal',$event);
		//echo '<p>'.$query.'</p>';
		//$totalitems = $wpdb->query($query);
		$this->items = $this->getWpdb()->get_results($query,ARRAY_A);
	}
}
?>
