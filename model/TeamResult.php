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
	 * --Pos,RaceName,Event,TeamType,Team Pos,TeTypeId,TeId,TempTeId,Team Total,Team Name,Team Std,RaceNo,Name,Gender,Company,OvPos,FinishTime,Std,Class,Team No,Company No,RaceId,EventId
	   --1,BHAA NCF 5km Night XC 2013,5.2km,BHAA,1,1,26,2,17,Swords Labs,21,1782,Chris Muldoon,Male,Swords Labs,2,00:18:35,6,A,204,204,47,1
	   --4,BHAA NCF 5km Night XC 2013,5.2km,BHAA,8,1,1,2,160,RTE,39,1809,Terry Clarke,Male,Rte,33,00:22:07,11,B,121,121,47,1
	   --0, 1                        ,2    ,3   ,4,5,6,7,8  ,9  ,10,11  ,12          ,13  ,14 ,15,16      ,17,18,19,20 ,21,22
	 */
	public function addResult($row)
	{
		error_log(var_dump($row));
		$this->wpdb->show_errors();
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
					'runner'=>$row[11],
					'pos'=>$row[15],
					'std'=>$row[17],
					'racetime'=>$row[16],
					'company'=>$row[20],
					'companyname'=>$row[14])
		);
		$this->wpdb->print_error();
		$this->wpdb->hide_errors();
		error_log($res);
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
			$this->wpdb->prepare('select * from %s where race=%d order by id desc',$this->getName(),$this->race)
		);
	}
}
?>
