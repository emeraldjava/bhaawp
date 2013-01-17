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
}
$ONE = new Standard(1, 0.442101708254709, 176.435763853992, 174.688875102962);
echo $ONE->toString();

?>