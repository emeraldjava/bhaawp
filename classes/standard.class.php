<?php
class Standard
{
	protected $standard;
	protected $slopefactor;
	protected $oneKmTimeInSecs;
	protected $oneKmTimeInSecs2010;

	function __construct($standard,$slopefactor,$oneKmTimeInSecs,$oneKmTimeInSecs2010) {
		$this->standard = (int) $standard;
		$this->slopefactor = (float) $slopefactor;
		$this->oneKmTimeInSecs = (float) $oneKmTimeInSecs;
		$this->oneKmTimeInSecs2010 = (float) $oneKmTimeInSecs2010;
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
$ONE = new Standard(1, 0.442101708254709, 176.435763853992, 174.688875102962);
echo $ONE->toString().',';
echo $ONE->getKmTime(1).',';
echo $ONE->getKmTime(2).',';
echo $ONE->getKmTime(5).',';
echo $ONE->getKmTime(42).',';

echo $ONE->getKmPace(1).',';
echo $ONE->getKmPace(2).',';
echo $ONE->getKmPace(5).',';
echo $ONE->getKmPace(42).',';
?>