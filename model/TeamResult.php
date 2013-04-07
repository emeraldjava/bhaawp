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
		//error_log(var_dump($row));
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
					'companyname'=>$row[14])
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
	
	public function getTeamResults()
	{
		return $this->wpdb->get_results(
			$this->wpdb->prepare('select * from wp_bhaa_teamresult where race=%d order by id',$this->race)
		);
	}
	
	/**
	 * We'll do the html table generation here for the moment.
	 */
	
	public function getTeamTable()
	{
		$results = $this->getTeamResults();
		//var_dump($results);
		$table = '<h2>Team Results '.$this->race.'</h2>';
		$table .= '<table>';
		
		
		// ["id"]=> string(2) "64" ["race"]=> string(4) "2595" ["class"]=> string(1) "W" ["position"]=> string(1) "1" 
		// ["team"]=> string(2) "21" ["teamname"]=> string(20) "Accountants Ladies A" ["totalpos"]=> string(2) "48" 
		// ["totalstd"]=> string(2) "45" ["runner"]=> string(4) "7048" ["pos"]=> string(2) "11" ["std"]=> string(2) "15" 
		// ["racetime"]=> string(8) "00:13:32" ["company"]=> string(3) "549" ["companyname"]=> string(18) "McInerney Saunders" 
		// ["leaguepoints"]=> string(1) "0" } 
		$class='';
		$position=0;
		$count=1;
		foreach($results as $row)
		{
			//var_dump($row);
			if($row->class!=$class)
			{
				$class = $row->class;
				$position = $row->postion;
				$table .= '<h3>Class '.$class.'</h3>';
			}
			
			//first row of a new team
			if($position==0 && $count==1)
			{
				$position = $row->position;
				// start table
				$table .= '<h4>Table '.$class.'</h4>';
			
				// add first row
				$table .= sprintf('<h5>Team %d %s</h5>',$row->position,$row->teamname);
			}
		
			if($count==1)
			{
				$table .= sprintf('<h6>1 Runner %d %s</h6>',$count++,$row->runner);
			}
			else if($count==2)
			{
				$table .= sprintf('<h6>2 Runner %d %s</h6>',$count++,$row->runner);
			}
			else if($count==3)
			{
				// add second/third row
				$table .= sprintf('<h6>3 Runner %d %s</h6>',$count++,$row->runner);
				$count = 1;
				$position = 0;
			}	
			
			// check if new 
		}
		$table .= '</table>';
		return $table;
		
	}
	
	public function generateRow($name='',$raceno='',$time='',$company='',$position='',$standard='')
	{
		return sprintf('<tr>
			<td>%s</td>
			<td>%s</td>
			<td>%s</td>
			<td>%s</td>
			<td>%s</td>
			<td>%s</td>
			</tr>',$name,$raceno,$time,$company,$position,$standard);
	}
}
?>
