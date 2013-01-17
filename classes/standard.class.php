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
	
	function getTime($kmDistance)
	{
		// SEC_TO_TIME((((standard.slopefactor) * (sd.km-1)) + oneKmTimeInSecs) ) as pace,
		// SEC_TO_TIME((((standard.slopefactor) * (sd.km-1)) + oneKmTimeInSecs) * sd.km) as time,
		return date('i:s', (($this->slopefactor * ($kmDistance-1)) + $this->oneKmTimeInSecs) * $kmDistance ).'/n';
	}
}
$ONE = new Standard(1, 0.442101708254709, 176.435763853992, 174.688875102962);
echo $ONE->toString();
echo $ONE->getTime(1);
echo $ONE->getTime(2);
echo $ONE->getTime(5);
echo $ONE->getTime(42);
?>