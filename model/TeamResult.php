<?php
class TeamResult extends BaseModel
{
	var $race;
	
	function __construct($race)
	{
		parent::__construct();
		$this->race = $race;
	}
	
	public function getName()
	{
		return $this->wpdb->prefix.'bhaa_teamresult';
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
	public function addResult($row)
	{
		//error_log($row[0].'->'.( 6.5-(((int)$row[0])*.5) ) );
		//$this->wpdb->show_errors();
		$res = $this->wpdb->insert(
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
					'companyname'=>$row[14],
					'leaguepoints'=> (6.5-( ((int)$row[0]) *.5))
				)
		);
		//$this->wpdb->print_error();
		//$this->wpdb->hide_errors();
		//error_log($res);
	}
	
	public function deleteResults()
	{
		return $this->wpdb->delete(
			$this->getName(),
			array('race' => $this->race
		));
	}
	
	public function getTeamResults() {
		return $this->wpdb->get_results(
			$this->wpdb->prepare('select wp_bhaa_teamresult.*,wp_users.display_name from wp_bhaa_teamresult
				join wp_users on wp_users.id=wp_bhaa_teamresult.runner
				where race=%d order by id',$this->race)
		);
	}
	
	/**
	 * We'll do the html table generation here for the moment.
	 */
	
	public function getTeamTable() {
		$results = $this->getTeamResults();
		$table = '<h2>Team Results</h2>';
		//$table .= $this->teamSummary();
		$table .= $this->displayClassTable($results,'A');
		$table .= $this->displayClassTable($results,'B');
		$table .= $this->displayClassTable($results,'C');
		$table .= $this->displayClassTable($results,'D');
		$table .= $this->displayClassTable($results,'W');
		return $table;
	}
	
	private function teamSummary(){
		return '[counters_box]
			[counter_box value="5"]A[/counter_box]
			[counter_box value="5"]B[/counter_box]
			[counter_box value="6"]C[/counter_box]
			[counter_box value="8"]D[/counter_box]
			[counter_box value="2"]W[/counter_box]
			[/counters_box]';
	}
	
	private function displayClassTable($results,$class) {
		
		// ["id"]=> string(2) "64" ["race"]=> string(4) "2595" ["class"]=> string(1) "W" ["position"]=> string(1) "1" 
		// ["team"]=> string(2) "21" ["teamname"]=> string(20) "Accountants Ladies A" ["totalpos"]=> string(2) "48" 
		// ["totalstd"]=> string(2) "45" ["runner"]=> string(4) "7048" ["pos"]=> string(2) "11" ["std"]=> string(2) "15" 
		// ["racetime"]=> string(8) "00:13:32" ["company"]=> string(3) "549" ["companyname"]=> string(18) "McInerney Saunders" 
		// ["leaguepoints"]=> string(1) "0" } 
		$table .= '<table class="table-1" width="90%">';
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
					$table .= $this->generateRow('<h4><b>Class '.$row->class.'</b></h4>','','','','');				
				}
				
				//first row of a new team
				if($count==0) {
					$position = $row->position;
					// start table
					$house_url = sprintf('<a href="/?post_type=house&p=%d"><b>%s</b></a>',$row->team,$row->teamname);
					$table .= $this->generateRow('<b>'.$row->class.'-'.$row->position.' '.$house_url.'</b>','','','<b>Position</b>','<b>Standard</b>','');
					// add first row
					$table .= $this->generateRow('<b>Athlete</b>','<b>Race Time</b>','<b>Company</b>','<b>'.$row->totalpos.'<b>','<b>'.$row->totalstd.'<b>');
				}
				
				$runner_url = sprintf('<a href="/runner/?id=%s"><b>%s</b></a>',$row->runner,$row->display_name);
				$table .= $this->generateRow($runner_url,$row->racetime,$row->companyname,$row->pos,$row->std);
				$count++;
				if($count==3) {
					// add second/third row
					$count = 0;
					$position = 0;
				}	
			}
		}
		$table .= '</table>';
		return $table;		
	}
	
	public function generateRow($name='',$time='',$company='',$position='',$standard='',$style='')
	{
		return sprintf('<tr class="%s">
			<td>%s</td>
			<td>%s</td>
			<td>%s</td>
			<td>%s</td>
			<td>%s</td>
			</tr>',$style,$name,$time,$company,$position,$standard);
	}
	
	/**
	 * Add 6 league points to the team
	 */
	public function addTeamOrganiserPoints($team) {
		return $this->wpdb->insert(
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
		return $this->wpdb->delete(
			$this->getName(),
			array('race' => $this->race,
				'team' => $team,
				'leaguepoints' => 6,
				'class' => 'R'));
	}
}
?>
