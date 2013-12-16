<?php

class Standard
{
	var $standard;
	var $slopefactor;
	var $oneKmTimeInSecs;

	function __construct($standard,$slopefactor,$oneKmTimeInSecs) {
		$this->standard = (int) $standard;
		$this->slopefactor = (float) $slopefactor;
		$this->oneKmTimeInSecs = (float) $oneKmTimeInSecs;
	}

	function toString()
	{
		return 'Standard '.$this->standard;
	}
	
	/**
	 * Calculates the expected time for a specific distance for this standard
	 * -- SEC_TO_TIME((((standard.slopefactor) * (sd.km-1)) + oneKmTimeInSecs) * sd.km) as time,
	 * @param unknown $kmDistance
	 * @return string
	 */
	function getKmTime($kmDistance)
	{
		$seconds = (($this->slopefactor * ($kmDistance-1)) + $this->oneKmTimeInSecs) * $kmDistance;
		if($seconds >= 3600)
			return gmdate('H:i:s',$seconds);
		else
			return gmdate('i:s',$seconds);
	}
	
	/**
	 * Returns the required pace for a specific distance
	 * -- SEC_TO_TIME((((standard.slopefactor) * (sd.km-1)) + oneKmTimeInSecs) ) as pace,
	 */
	function getKmPace($kmDistance)
	{
		$seconds = (($this->slopefactor * ($kmDistance-1)) + $this->oneKmTimeInSecs);
		return gmdate('i:s',$seconds);
	}
}
?>