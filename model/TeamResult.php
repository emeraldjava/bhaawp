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
	 * --Pos,RaceName,EventDescr,TeamType,Team Pos,TeamTypeId,TeamId,TempTeamId,Team Total,Team Name,Team Std,RaceNo,Name,Gender,Company,Overall Pos,Finish Time,Std,Class,Team No,Company No,RaceId,EventId
	   --1,BHAA NCF 5km Night XC 2013,5.2km,BHAA,1,1,26,2,17,Swords Labs,21,1782,Chris Muldoon,Male,Swords Labs,2,00:18:35,6,A,204,204,47,1
	   --4,BHAA NCF 5km Night XC 2013,5.2km,BHAA,8,1,1,2,160,RTE,39,1809,Terry Clarke,Male,Rte,33,00:22:07,11,B,121,121,47,1
	 */
	public function addResult($row)
	{
		//error_log(var_dump($res));
		//$this->wpdb->show_errors();
		$res = $this->wpdb->insert(
				$this->getName(),
				array(
					'race'=>$this->race,
					'class'=>$row[18],
					'position'=>$row[0],
					'team'=>$row[8],
					'teamname'=>$row[9],
					'totalpos'=>$row[8],
					'totalstd'=>$row[10],
					'runner'=>$row[11],
					'pos'=>$row[15],
					'std'=>$row[17],
					'racetime'=>$row[16],
					'company'=>$row[19],
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
}
?>
