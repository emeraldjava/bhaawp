<?php

class StandardCalculator
{
	protected $standards;

	function __construct() {
		$this->standards = array();
		array_push($this->standards,new Standard(1, 0.442101708254709, 176.435763853992, 174.688875102962));
		array_push($this->standards,new Standard(2, 0.452101708254709, 178.435763853992, 179.688875102962));
	}
	
	function getTimeTable($distance)
	{
		foreach ($this->standards as $k => $v)
		{
			echo $v->standard.':'.$v->getKmTime($distance);
		}
	}
	
	function toString()
	{
		print_r($this->standards);
	}
}
?>