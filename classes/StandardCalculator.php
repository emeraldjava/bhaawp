<?php

class StandardCalculator
{
	var $standards;

	var $standardTableSql = 'standard int(11) NOT NULL AUTO_INCREMENT,
		slopefactor double NOT NULL,
		oneKmTimeInSecs double NOT NULL,
		PRIMARY KEY (standard)';
	
	function __construct() {
		$this->standards = array();
		array_push($this->standards,new Standard(1,0.442101708254709,176.435763853992));
		array_push($this->standards,new Standard(2,0.512546485943647,182.54576510527));
		array_push($this->standards,new Standard(3,0.582991263632585,188.741317576442));
		array_push($this->standards,new Standard(4,0.637241839553949,195.113922786538));
		array_push($this->standards,new Standard(5,0.691492415475315,201.57383887222));
		array_push($this->standards,new Standard(6,0.761937193164251,208.027804664097));
		array_push($this->standards,new Standard(7,0.832381970853189,214.567321687178));
		array_push($this->standards,new Standard(8,0.902826748542127,221.192389929881));
		array_push($this->standards,new Standard(9,0.957077324463491,227.998910022736));
		array_push($this->standards,new Standard(10,1.01132790038486,234.89274099145));
		array_push($this->standards,new Standard(11,1.0817726780738,241.776222554858));
		array_push($this->standards,new Standard(12,1.15221745576273,248.745255349742));
		array_push($this->standards,new Standard(13,1.22266223345167,255.799839363977));
		array_push($this->standards,new Standard(14,1.27691280937304,263.04027434579));
		array_push($this->standards,new Standard(15,1.3311633852944,270.368020191336));
		array_push($this->standards,new Standard(16,1.40160816298334,277.681017526275));
		array_push($this->standards,new Standard(17,1.47205294067227,285.079566092963));
		array_push($this->standards,new Standard(18,1.54249771836121,292.563665878729));
		array_push($this->standards,new Standard(19,1.59674829428258,300.238015743573));
		array_push($this->standards,new Standard(20,1.65099887020394,307.999676471878));
		array_push($this->standards,new Standard(21,1.72144364789288,315.742189584928));
		array_push($this->standards,new Standard(22,1.79188842558182,323.57025391684));
		array_push($this->standards,new Standard(23,1.86233320327075,331.483869480825));
		array_push($this->standards,new Standard(24,1.91658377919212,339.592134222012));
		array_push($this->standards,new Standard(25,1.97083435511349,347.787709833076));
		array_push($this->standards,new Standard(26,2.04127913280242,355.95973871793));
		array_push($this->standards,new Standard(27,2.11172391049136,364.217318821373));
		array_push($this->standards,new Standard(28,2.18216868818029,372.560450157162));
		array_push($this->standards,new Standard(29,2.23641926410166,381.102629781108));
		array_push($this->standards,new Standard(30,2.29066984002303,389.732120274931));
	}
	
	/**
	 * Returns an array of expected standard times for a distance 
	 * @param unknown $distance
	 * @return multitype:NULL
	 */
	function getTimeTable($distance)
	{
		$times = array();
		foreach ($this->standards as $k => $v)
		{
			$times[$v->standard] = $v->getKmTime($distance);
		}
		echo $times;
		return $times;
	}
	
	function getEventStandardTable($attr) 
	{
		global $post;
		error_log('getEventStandardTable '.$post->ID);
		
		$eventModel = new EventModel($post->ID);//$attr['id']);
		$standardTable = '<div>';
		$standardTable .= '<h3>BHAA Standard Table</h3><table>';
		$races = $eventModel->getRaces();
		
		// headers
		$standardTable .= '<tr>';
		$standardTable .= '<th>HEADER</th>';
		foreach ($races as $race)
		{
			$standardTable .= '<th>'.$race->getKmDistance().'</th>';
		}
		$standardTable .= '</tr>';
		
		// standard row and distance time
		$standardTable .= '<tr>';
		foreach ($this->standards as $k => $v)
		{
			$standardTable .= '<td>'.$v->standard.'</td>';
			foreach ($races as $race)
			{
				$standardTable .= '<td>'.$v->getKmTime($race->getKmDistance()).'</td>';
			}
		}
		$standardTable .= '</tr>';
		$standardTable .= '</div>';
		echo $standardTable;// print_r($times,false);
	}
	
	function toString()
	{
		return $this->standards;
	}
}
?>